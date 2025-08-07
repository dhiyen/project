import json
import os
import numpy as np
from sklearn.preprocessing import StandardScaler
from math import cos, sin, radians

def translate_keypoints(keypoints, max_shift=0.05):
    """
    Dịch chuyển ngẫu nhiên keypoints với độ lệch tối đa (tỷ lệ với tọa độ).
    """
    shift_x = np.random.uniform(-max_shift, max_shift) * 1000  # Giả sử khung ảnh ~1000px
    shift_y = np.random.uniform(-max_shift, max_shift) * 1000
    augmented_keypoints = []
    for kp in keypoints:
        augmented_kp = {
            'x': kp['x'] + shift_x,
            'y': kp['y'] + shift_y,
            'visibility': kp['visibility']
        }
        augmented_keypoints.append(augmented_kp)
    return augmented_keypoints

def rotate_keypoints(keypoints, max_angle=10):
    """
    Xoay ngẫu nhiên keypoints quanh trung điểm vai trái-vai phải với góc tối đa (độ).
    """
    # Tìm trung điểm vai trái (11) và vai phải (12)
    shoulder_left = keypoints[11]
    shoulder_right = keypoints[12]
    if shoulder_left['visibility'] < 0.5 or shoulder_right['visibility'] < 0.5:
        return keypoints  # Không xoay nếu vai không rõ
    
    center_x = (shoulder_left['x'] + shoulder_right['x']) / 2
    center_y = (shoulder_left['y'] + shoulder_right['y']) / 2
    
    # Chọn góc xoay ngẫu nhiên
    angle = np.random.uniform(-max_angle, max_angle)
    angle_rad = radians(angle)
    cos_a = cos(angle_rad)
    sin_a = sin(angle_rad)
    
    augmented_keypoints = []
    for kp in keypoints:
        # Dịch chuyển về gốc, xoay, rồi dịch ngược lại
        x_rel = kp['x'] - center_x
        y_rel = kp['y'] - center_y
        x_new = x_rel * cos_a - y_rel * sin_a
        y_new = x_rel * sin_a + y_rel * cos_a
        augmented_kp = {
            'x': x_new + center_x,
            'y': y_new + center_y,
            'visibility': kp['visibility']
        }
        augmented_keypoints.append(augmented_kp)
    return augmented_keypoints

def calculate_euclidean_distance(point1, point2):
    """
    Tính khoảng cách Euclidean giữa hai điểm (x, y).
    """
    return np.sqrt((point1['x'] - point2['x'])**2 + (point1['y'] - point2['y'])**2)

def calculate_angle(point1, point2, point3):
    """
    Tính góc (độ) giữa ba điểm: point1-point2-point3, với point2 là đỉnh.
    """
    vector1 = np.array([point1['x'] - point2['x'], point1['y'] - point2['y']])
    vector2 = np.array([point3['x'] - point2['x'], point3['y'] - point2['y']])
    cos_theta = np.dot(vector1, vector2) / (np.linalg.norm(vector1) * np.linalg.norm(vector2) + 1e-10)
    cos_theta = np.clip(cos_theta, -1.0, 1.0)
    angle = np.degrees(np.arccos(cos_theta))
    return angle

def extract_features_from_keypoints(keypoints):
    """
    Trích xuất vector đặc trưng từ danh sách keypoints (tương tự Bước 3).
    """
    distance_pairs = [
        (11, 12), (11, 13), (13, 15), (12, 14), (14, 16),
        (11, 23), (12, 24), (23, 24), (23, 25), (25, 27),
        (24, 26), (26, 28)
    ]
    angle_triplets = [
        (11, 13, 15), (12, 14, 16), (23, 25, 27),
        (24, 26, 28), (11, 23, 25), (12, 24, 26)
    ]
    
    distances = []
    for idx1, idx2 in distance_pairs:
        if keypoints[idx1]['visibility'] > 0.5 and keypoints[idx2]['visibility'] > 0.5:
            dist = calculate_euclidean_distance(keypoints[idx1], keypoints[idx2])
            distances.append(dist)
        else:
            distances.append(0.0)
    
    angles = []
    for idx1, idx2, idx3 in angle_triplets:
        if (keypoints[idx1]['visibility'] > 0.5 and 
            keypoints[idx2]['visibility'] > 0.5 and 
            keypoints[idx3]['visibility'] > 0.5):
            angle = calculate_angle(keypoints[idx1], keypoints[idx2], keypoints[idx3])
            angles.append(angle)
        else:
            angles.append(0.0)
    
    ratios = []
    arm_left_len = (calculate_euclidean_distance(keypoints[11], keypoints[13]) + 
                    calculate_euclidean_distance(keypoints[13], keypoints[15]))
    arm_right_len = (calculate_euclidean_distance(keypoints[12], keypoints[14]) + 
                     calculate_euclidean_distance(keypoints[14], keypoints[16]))
    leg_left_len = (calculate_euclidean_distance(keypoints[23], keypoints[25]) + 
                    calculate_euclidean_distance(keypoints[25], keypoints[27]))
    leg_right_len = (calculate_euclidean_distance(keypoints[24], keypoints[26]) + 
                     calculate_euclidean_distance(keypoints[26], keypoints[28]))
    
    if (keypoints[11]['visibility'] > 0.5 and keypoints[13]['visibility'] > 0.5 and 
        keypoints[15]['visibility'] > 0.5 and keypoints[23]['visibility'] > 0.5 and 
        keypoints[25]['visibility'] > 0.5 and keypoints[27]['visibility'] > 0.5):
        ratio_arm_left_leg_left = arm_left_len / (leg_left_len + 1e-10)
        ratios.append(ratio_arm_left_leg_left)
    else:
        ratios.append(0.0)
    
    if (keypoints[12]['visibility'] > 0.5 and keypoints[14]['visibility'] > 0.5 and 
        keypoints[16]['visibility'] > 0.5 and keypoints[24]['visibility'] > 0.5 and 
        keypoints[26]['visibility'] > 0.5 and keypoints[28]['visibility'] > 0.5):
        ratio_arm_right_leg_right = arm_right_len / (leg_right_len + 1e-10)
        ratios.append(ratio_arm_right_leg_right)
    else:
        ratios.append(0.0)
    
    return distances + angles + ratios

def augment_data(keypoints_file, preprocessed_file, output_file, num_augmentations=2):
    """
    Tăng cường dữ liệu cho một video và lưu vào file JSON.
    """
    os.makedirs(os.path.dirname(output_file), exist_ok=True)
    
    # Đọc keypoints gốc
    with open(keypoints_file, 'r') as f:
        keypoints_data = json.load(f)
    
    # Đọc nhãn từ file preprocessed
    with open(preprocessed_file, 'r') as f:
        preprocessed_data = json.load(f)
    
    # Thu thập đặc trưng và nhãn
    all_feature_vectors = []
    all_frame_names = []
    all_labels = []
    
    for frame_name in keypoints_data:
        if frame_name not in preprocessed_data:
            continue
        label = preprocessed_data[frame_name]['label']
        
        # Dữ liệu gốc
        keypoints = keypoints_data[frame_name]
        features = extract_features_from_keypoints(keypoints)
        all_feature_vectors.append(features)
        all_frame_names.append(frame_name)
        all_labels.append(label)
        
        # Dữ liệu tăng cường
        for i in range(num_augmentations):
            # Dịch chuyển
            trans_keypoints = translate_keypoints(keypoints, max_shift=0.05)
            # Xoay
            aug_keypoints = rotate_keypoints(trans_keypoints, max_angle=10)
            # Tính đặc trưng
            aug_features = extract_features_from_keypoints(aug_keypoints)
            all_feature_vectors.append(aug_features)
            all_frame_names.append(f"{frame_name}_aug_{i}")
            all_labels.append(label)
    
    if not all_feature_vectors:
        print(f"Không có dữ liệu để tăng cường trong {keypoints_file}")
        return
    
    # Chuẩn hóa đặc trưng
    scaler = StandardScaler()
    all_feature_vectors = np.array(all_feature_vectors)
    scaled_features = scaler.fit_transform(all_feature_vectors)
    
    # Lưu vào file JSON
    augmented_data = {}
    for frame_name, scaled_vector, label in zip(all_frame_names, scaled_features, all_labels):
        augmented_data[frame_name] = {
            'features': scaled_vector.tolist(),
            'label': label
        }
    
    with open(output_file, 'w') as f:
        json.dump(augmented_data, f, indent=4)
    print(f"Đã lưu dữ liệu tăng cường vào {output_file}")

def process_augmentation(keypoints_root_dir='processed_data/results_posekeypoints_mlp/step2',
                         preprocessed_root_dir='processed_data/results_posekeypoints_mlp/step4',
                         output_root_dir='processed_data/results_posekeypoints_mlp/step5',
                         num_augmentations=2):
    """
    Tăng cường dữ liệu cho tất cả video.
    """
    for label in os.listdir(preprocessed_root_dir):
        label_dir = os.path.join(preprocessed_root_dir, label)
        if not os.path.isdir(label_dir):
            continue
        for video_name in os.listdir(label_dir):
            preprocessed_file = os.path.join(label_dir, video_name, 'preprocessed_features.json')
            keypoints_file = os.path.join(keypoints_root_dir, label, video_name, 'keypoints.json')
            output_file = os.path.join(output_root_dir, label, video_name, 'augmented_features.json')
            
            if not (os.path.isfile(preprocessed_file) and os.path.isfile(keypoints_file)):
                continue
            
            print(f"Đang tăng cường: {preprocessed_file}")
            augment_data(keypoints_file, preprocessed_file, output_file, num_augmentations)
    
    print("Hoàn tất tăng cường dữ liệu cho tất cả video.")

if __name__ == "__main__":
    # Tạo thư mục Pose keypoints + MLP/step5 nếu chưa tồn tại
    os.makedirs('processed_data/results_posekeypoints_mlp/step5', exist_ok=True)
    
    process_augmentation(
        keypoints_root_dir='processed_data/results_posekeypoints_mlp/step2',
        preprocessed_root_dir='processed_data/results_posekeypoints_mlp/step4',
        output_root_dir='processed_data/results_posekeypoints_mlp/step5',
        num_augmentations=2
    )