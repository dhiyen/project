import json
import os
import numpy as np
from math import atan2, degrees

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
    cos_theta = np.clip(cos_theta, -1.0, 1.0)  # Tránh lỗi số học
    angle = degrees(np.arccos(cos_theta))
    return angle

def extract_features_from_keypoints(keypoints):
    """
    Trích xuất vector đặc trưng từ danh sách keypoints của một khung hình.
    """
    # Định nghĩa các cặp điểm để tính khoảng cách
    distance_pairs = [
        (11, 12),  # Vai trái - Vai phải
        (11, 13),  # Vai trái - Khuỷu tay trái
        (13, 15),  # Khuỷu tay trái - Cổ tay trái
        (12, 14),  # Vai phải - Khuỷu tay phải
        (14, 16),  # Khuỷu tay phải - Cổ tay phải
        (11, 23),  # Vai trái - Hông trái
        (12, 24),  # Vai phải - Hông phải
        (23, 24),  # Hông trái - Hông phải
        (23, 25),  # Hông trái - Đầu gối trái
        (25, 27),  # Đầu gối trái - Mắt cá chân trái
        (24, 26),  # Hông phải - Đầu gối phải
        (26, 28),  # Đầu gối phải - Mắt cá chân phải
    ]

    # Định nghĩa các bộ ba điểm để tính góc
    angle_triplets = [
        (11, 13, 15),  # Vai trái - Khuỷu tay trái - Cổ tay trái
        (12, 14, 16),  # Vai phải - Khuỷu tay phải - Cổ tay phải
        (23, 25, 27),  # Hông trái - Đầu gối trái - Mắt cá chân trái
        (24, 26, 28),  # Hông phải - Đầu gối phải - Mắt cá chân phải
        (11, 23, 25),  # Vai trái - Hông trái - Đầu gối trái
        (12, 24, 26),  # Vai phải - Hông phải - Đầu gối phải
    ]

    # Tính khoảng cách
    distances = []
    for idx1, idx2 in distance_pairs:
        if keypoints[idx1]['visibility'] > 0.5 and keypoints[idx2]['visibility'] > 0.5:
            dist = calculate_euclidean_distance(keypoints[idx1], keypoints[idx2])
            distances.append(dist)
        else:
            distances.append(0.0)  # Nếu visibility thấp, đặt khoảng cách là 0

    # Tính góc
    angles = []
    for idx1, idx2, idx3 in angle_triplets:
        if (keypoints[idx1]['visibility'] > 0.5 and 
            keypoints[idx2]['visibility'] > 0.5 and 
            keypoints[idx3]['visibility'] > 0.5):
            angle = calculate_angle(keypoints[idx1], keypoints[idx2], keypoints[idx3])
            angles.append(angle)
        else:
            angles.append(0.0)  # Nếu visibility thấp, đặt góc là 0

    # Tính tỉ lệ (chiều dài cánh tay / chiều dài chân)
    ratios = []
    # Chiều dài cánh tay trái (vai trái -> khuỷu tay trái -> cổ tay trái)
    arm_left_len = (calculate_euclidean_distance(keypoints[11], keypoints[13]) + 
                    calculate_euclidean_distance(keypoints[13], keypoints[15]))
    # Chiều dài cánh tay phải
    arm_right_len = (calculate_euclidean_distance(keypoints[12], keypoints[14]) + 
                     calculate_euclidean_distance(keypoints[14], keypoints[16]))
    # Chiều dài chân trái (hông trái -> đầu gối trái -> mắt cá chân trái)
    leg_left_len = (calculate_euclidean_distance(keypoints[23], keypoints[25]) + 
                    calculate_euclidean_distance(keypoints[25], keypoints[27]))
    # Chiều dài chân phải
    leg_right_len = (calculate_euclidean_distance(keypoints[24], keypoints[26]) + 
                     calculate_euclidean_distance(keypoints[26], keypoints[28]))
    
    # Tính tỉ lệ nếu visibility đủ cao
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

    return {
        'distances': distances,
        'angles': angles,
        'ratios': ratios
    }

def process_features_for_video(keypoints_file, output_features_file):
    """
    Xử lý keypoints của một video và lưu vector đặc trưng vào file JSON.
    """
    os.makedirs(os.path.dirname(output_features_file), exist_ok=True)
    
    with open(keypoints_file, 'r') as f:
        keypoints_data = json.load(f)
    
    features_data = {}
    for frame_name, keypoints in keypoints_data.items():
        features = extract_features_from_keypoints(keypoints)
        features_data[frame_name] = features
    
    if features_data:
        with open(output_features_file, 'w') as f:
            json.dump(features_data, f, indent=4)
        print(f"Đã lưu đặc trưng vào {output_features_file}")
    else:
        print(f"Không có đặc trưng nào được trích xuất từ {keypoints_file}")

def extract_features(keypoints_root_dir='Pose keypoints + MLP/step2', 
                     features_root_dir='Pose keypoints + MLP/step3'):
    """
    Trích xuất đặc trưng cho tất cả các video trong thư mục keypoints.
    """
    for label in os.listdir(keypoints_root_dir):
        label_dir = os.path.join(keypoints_root_dir, label)
        if not os.path.isdir(label_dir):
            continue
        for video_name in os.listdir(label_dir):
            keypoints_file = os.path.join(label_dir, video_name, 'keypoints.json')
            if not os.path.isfile(keypoints_file):
                continue
            output_features_file = os.path.join(features_root_dir, label, video_name, 'features.json')
            print(f"Đang xử lý: {keypoints_file}")
            process_features_for_video(keypoints_file, output_features_file)
    
    print("Hoàn tất trích xuất đặc trưng cho tất cả video.")

if __name__ == "__main__":
    # Tạo thư mục Pose keypoints + MLP/step3 nếu chưa tồn tại
    os.makedirs('Pose keypoints + MLP/step3', exist_ok=True)
    
    extract_features(
        keypoints_root_dir='processed_data/results_posekeypoints_mlp/step2',
        features_root_dir='processed_data/results_posekeypoints_mlp/step3'
    )