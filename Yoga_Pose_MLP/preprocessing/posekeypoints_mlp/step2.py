import cv2
import mediapipe as mp
import os
import json

def extract_keypoints_from_frame(image_path, pose):
    """
    Trích xuất keypoints từ một khung hình sử dụng MediaPipe Pose.
    """
    image = cv2.imread(image_path)
    if image is None:
        print(f"Lỗi: Không thể đọc ảnh {image_path}")
        return None

    # Chuyển đổi ảnh sang RGB vì MediaPipe yêu cầu định dạng RGB
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    results = pose.process(image_rgb)

    if not results.pose_landmarks:
        print(f"Không phát hiện keypoints trong ảnh {image_path}")
        return None

    # Lấy tọa độ (x, y) của các điểm mốc
    keypoints = []
    for landmark in results.pose_landmarks.landmark:
        keypoints.append({
            'x': landmark.x * image.shape[1],  # Chuyển từ tọa độ tỷ lệ sang pixel
            'y': landmark.y * image.shape[0],
            'visibility': landmark.visibility
        })

    return keypoints

def process_keypoints_for_video(frame_folder, output_keypoints_file, pose):
    """
    Xử lý tất cả các khung hình trong một thư mục video và lưu keypoints vào file JSON.
    """
    os.makedirs(os.path.dirname(output_keypoints_file), exist_ok=True)
    keypoints_data = {}

    for frame_file in sorted(os.listdir(frame_folder)):
        if not frame_file.endswith('.jpg'):
            continue
        frame_path = os.path.join(frame_folder, frame_file)
        keypoints = extract_keypoints_from_frame(frame_path, pose)
        if keypoints:
            keypoints_data[frame_file] = keypoints

    if keypoints_data:
        with open(output_keypoints_file, 'w') as f:
            json.dump(keypoints_data, f, indent=4)
        print(f"Đã lưu keypoints vào {output_keypoints_file}")
    else:
        print(f"Không có keypoints nào được trích xuất từ {frame_folder}")

def extract_keypoints(frame_root_dir='processed_data/frames', 
                      keypoint_root_dir='Pose keypoints + MLP/step2'):
    """
    Trích xuất keypoints cho tất cả các video trong thư mục khung hình.
    """
    # Khởi tạo MediaPipe Pose
    mp_pose = mp.solutions.pose
    pose = mp_pose.Pose(static_image_mode=True, min_detection_confidence=0.5)

    for label in os.listdir(frame_root_dir):
        label_dir = os.path.join(frame_root_dir, label)
        if not os.path.isdir(label_dir):
            continue
        for video_name in os.listdir(label_dir):
            frame_folder = os.path.join(label_dir, video_name)
            if not os.path.isdir(frame_folder):
                continue
            output_keypoints_file = os.path.join(keypoint_root_dir, label, video_name, 'keypoints.json')
            print(f"Đang xử lý: {frame_folder}")
            process_keypoints_for_video(frame_folder, output_keypoints_file, pose)

    pose.close()
    print("Hoàn tất trích xuất keypoints cho tất cả video.")

if __name__ == "__main__":
    # Tạo thư mục Pose keypoints + MLP/step2 nếu chưa tồn tại
    os.makedirs('posekeypoints_mlp/step2', exist_ok=True)
    
    extract_keypoints(
        frame_root_dir='processed_data/frames',
        keypoint_root_dir='processed_data/results_posekeypoints_mlp/step2'
    )