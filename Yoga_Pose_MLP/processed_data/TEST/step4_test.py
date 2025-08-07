import json
import os
import numpy as np
from sklearn.preprocessing import StandardScaler

def load_features(features_file):
    """
    Đọc file features.json và trả về danh sách đặc trưng và tên khung hình.
    """
    with open(features_file, 'r') as f:
        features_data = json.load(f)
    
    frame_names = []
    feature_vectors = []
    
    for frame_name, features in features_data.items():
        # Gộp distances, angles, ratios thành vector đặc trưng (12 + 6 + 2 = 20)
        vector = (features['distances'] + 
                  features['angles'] + 
                  features['ratios'])
        frame_names.append(frame_name)
        feature_vectors.append(vector)
    
    return frame_names, np.array(feature_vectors)

def preprocess_features(features_root_dir='Pose keypoints + MLP/step3', 
                       output_root_dir='Pose keypoints + MLP/step4'):
    """
    Tiền xử lý đặc trưng: chuẩn hóa và gán nhãn, lưu vào file JSON.
    """
    # Tạo danh sách nhãn từ tên thư mục
    labels = sorted(os.listdir(features_root_dir))
    label_map = {label: idx for idx, label in enumerate(labels)}
    print(f"Nhãn: {label_map}")
    
    # Thu thập tất cả đặc trưng từ tất cả video
    all_frame_names = []
    all_feature_vectors = []
    all_labels = []
    video_paths = []
    
    for label in labels:
        label_dir = os.path.join(features_root_dir, label)
        if not os.path.isdir(label_dir):
            continue
        for video_name in os.listdir(label_dir):
            features_file = os.path.join(label_dir, video_name, 'features.json')
            if not os.path.isfile(features_file):
                continue
            print(f"Đang xử lý: {features_file}")
            
            # Đọc đặc trưng
            frame_names, feature_vectors = load_features(features_file)
            if len(feature_vectors) == 0:
                print(f"Không có đặc trưng trong {features_file}")
                continue
            
            # Lưu thông tin
            all_frame_names.extend(frame_names)
            all_feature_vectors.extend(feature_vectors)
            all_labels.extend([label_map[label]] * len(frame_names))
            video_paths.extend([(label, video_name, frame_name) for frame_name in frame_names])
    
    if not all_feature_vectors:
        print("Không có đặc trưng nào được trích xuất.")
        return
    
    # Chuẩn hóa đặc trưng
    scaler = StandardScaler()
    all_feature_vectors = np.array(all_feature_vectors)
    scaled_features = scaler.fit_transform(all_feature_vectors)
    
    # Lưu đặc trưng đã chuẩn hóa theo từng video
    for (label, video_name, frame_name), scaled_vector, label_idx in zip(
        video_paths, scaled_features, all_labels):
        output_dir = os.path.join(output_root_dir, label, video_name)
        os.makedirs(output_dir, exist_ok=True)
        output_file = os.path.join(output_dir, 'preprocessed_features.json')
        
        # Đọc file đầu ra nếu đã tồn tại, hoặc tạo mới
        if os.path.isfile(output_file):
            with open(output_file, 'r') as f:
                preprocessed_data = json.load(f)
        else:
            preprocessed_data = {}
        
        # Lưu đặc trưng và nhãn cho khung hình
        preprocessed_data[frame_name] = {
            'features': scaled_vector.tolist(),
            'label': label_idx
        }
        
        # Ghi lại file
        with open(output_file, 'w') as f:
            json.dump(preprocessed_data, f, indent=4)
        
        print(f"Đã lưu đặc trưng chuẩn hóa vào {output_file}")

    print("Hoàn tất tiền xử lý đặc trưng cho tất cả video.")

if __name__ == "__main__":
    # Tạo thư mục Pose keypoints + MLP/step4 nếu chưa tồn tại
    os.makedirs('Pose keypoints + MLP/step4', exist_ok=True)
    
    preprocess_features(
        features_root_dir='Pose keypoints + MLP/step3',
        output_root_dir='Pose keypoints + MLP/step4'
    )