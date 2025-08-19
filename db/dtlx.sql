-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost
-- Thời gian đã tạo: Th7 28, 2025 lúc 01:11 PM
-- Phiên bản máy phục vụ: 10.11.10-MariaDB-log
-- Phiên bản PHP: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `dtlx`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activation_codes`
--

CREATE TABLE `activation_codes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `device_mobile_id` varchar(255) DEFAULT NULL COMMENT 'ID thiết bị đã kích hoạt',
  `device_web_id` varchar(255) DEFAULT NULL COMMENT 'ID thiết bị đã kích hoạt',
  `activation_code` varchar(255) NOT NULL COMMENT 'mã kích hoạt',
  `buyer_name` varchar(255) NOT NULL COMMENT 'tên người mua',
  `pakage_time` int(10) UNSIGNED NOT NULL COMMENT 'số ngày được sử dụng tính từ thời gian kích hoạt, nếu là 1 thì tính là gói vĩnh viễn, nếu là số khác thì tính là số ngày',
  `activated_at` datetime DEFAULT NULL COMMENT 'ngày kích hoạt',
  `expires_at` datetime DEFAULT NULL COMMENT 'ngày hết hạn',
  `status` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'trạng thái kích hoạt: 0 là chưa kích hoạt, 1 là đã kích hoạt',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `event` varchar(255) DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `batch_uuid` char(36) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `assignments`
--

CREATE TABLE `assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `quiz_set_id` bigint(20) UNSIGNED NOT NULL COMMENT 'id xác định việc add bài tập vào bộ câu hỏi nào',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calendars`
--

CREATE TABLE `calendars` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'Kiểu lịch exam: lịch học, kiểm tra, họp',
  `level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Loại thi ví dụ: 1 là thi lý thuyết, 2 là thi thực hành',
  `name` varchar(255) NOT NULL COMMENT 'Tên lịch',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Trạng thái lịch: 0: chưa hoàn thành, 1: đang diễn ra, 2: đã hoàn thành',
  `priority` enum('Low','Normal','High','Urgent') NOT NULL DEFAULT 'Normal' COMMENT 'Mức độ ưu tiên',
  `date_start` timestamp NULL DEFAULT NULL COMMENT 'Ngày bắt đầu',
  `date_end` timestamp NULL DEFAULT NULL COMMENT 'Ngày kết thúc',
  `duration` int(11) DEFAULT NULL COMMENT 'Thời lượng',
  `description` text DEFAULT NULL COMMENT 'Mô tả',
  `learning_field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_fee` decimal(10,2) DEFAULT NULL,
  `exam_fee_deadline` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL COMMENT 'Địa điểm',
  `stadium_id` bigint(20) UNSIGNED DEFAULT NULL,
  `exam_schedule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `vehicle_id` bigint(20) UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` tinyint(3) UNSIGNED DEFAULT NULL COMMENT '1 = sáng, 2 = chiều, 3 = đêm nếu có'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calendar_course`
--

CREATE TABLE `calendar_course` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calendar_student`
--

CREATE TABLE `calendar_student` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `score` double(8,3) DEFAULT NULL COMMENT 'số % đúng được tính dựa trên số câu đúng',
  `correct_answers` varchar(10) DEFAULT NULL COMMENT 'số câu đúng dưới dạng: số câu đúng/số câu của bài thi',
  `exam_status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'trạng thái thi: 0: chưa thi, 1: đạt, 2: không đạt',
  `attempt_number` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'lưu lại đây là lần thi thứ mấy',
  `exam_number` varchar(255) DEFAULT NULL,
  `pickup` tinyint(1) NOT NULL DEFAULT 0,
  `exam_fee_paid_at` timestamp NULL DEFAULT NULL,
  `remarks` text DEFAULT NULL COMMENT 'Nhận xét ghi vào đây',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Tổng số giờ học',
  `km` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số km chạy được',
  `night_hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số giờ chạy đêm',
  `auto_hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số giờ chạy tự động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calendar_student_exam_field`
--

CREATE TABLE `calendar_student_exam_field` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_student_id` bigint(20) UNSIGNED NOT NULL,
  `exam_field_id` bigint(20) UNSIGNED NOT NULL,
  `attempt_number` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `answer_ratio` varchar(255) DEFAULT NULL,
  `exam_all_status` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'trạng thái thi chung của lịch 0 = chưa thi, 1 = đỗ, 2 = không đỗ',
  `exam_status` tinyint(3) UNSIGNED DEFAULT NULL COMMENT 'trạng thái thi của môn 0 = chưa thi, 1 = đạt, 2 = không đạt',
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `calendar_user`
--

CREATE TABLE `calendar_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `calendar_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `price_at_result` decimal(8,2) UNSIGNED DEFAULT NULL COMMENT 'Giá giảng dạy tại thời điểm nhập kết quả',
  `role` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số giờ dạy của giảng viên trong lịch học',
  `km` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số km giảng viên đã di chuyển',
  `night_hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số giờ dạy đêm của giảng viên',
  `auto_hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số giờ giảng viên dạy tự động'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chapters`
--

CREATE TABLE `chapters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ranking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL COMMENT 'Mã khóa học',
  `shlx_course_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID của khóa học trên hệ thống sát hạch',
  `course_system_code` varchar(255) DEFAULT NULL COMMENT 'Mã khóa học trên hệ thống sát hạch',
  `curriculum_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Loại khóa học như cơ bản nâng cao',
  `number_bc` varchar(255) DEFAULT NULL COMMENT 'Số hồ sơ quản lý khóa học, báo cáo',
  `date_bci` date DEFAULT NULL COMMENT 'Ngày bắt đầu báo cáo khóa học',
  `start_date` date DEFAULT NULL COMMENT 'Ngày bắt đầu khóa học',
  `end_date` date DEFAULT NULL COMMENT 'Ngày kết thúc khóa học',
  `duration_days` int(11) DEFAULT NULL,
  `dat_date` date DEFAULT NULL,
  `cabin_date` date DEFAULT NULL,
  `number_students` int(11) DEFAULT NULL COMMENT 'Số lượng học viên',
  `decision_kg` varchar(255) DEFAULT NULL COMMENT 'Lưu thông tin quyết định giấy tờ liên quan của khóa học',
  `duration` int(11) DEFAULT NULL COMMENT 'Tổng số giờ học của khóa học',
  `km` decimal(8,3) NOT NULL DEFAULT 0.000 COMMENT 'số km cần để hoàn thành khóa học',
  `required_km` decimal(8,2) NOT NULL DEFAULT 0.00 COMMENT 'số km cần để hoàn thành khóa học',
  `min_automatic_car_hours` double(8,2) NOT NULL DEFAULT 0.00,
  `min_night_hours` double(8,2) NOT NULL DEFAULT 0.00,
  `tuition_fee` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Học phí khóa học',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Trạng thái khóa học (active, inactive)',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_exam_field`
--

CREATE TABLE `course_exam_field` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `exam_field_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_learning_field`
--

CREATE TABLE `course_learning_field` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `learning_field_id` bigint(20) UNSIGNED NOT NULL,
  `hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số giờ cần phải chạy cho môn học này của khóa học',
  `km` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'Số km cần phải chạy cho môn học này của khóa học',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_students`
--

CREATE TABLE `course_students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `stadium_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reserved_chip_hours` time NOT NULL DEFAULT '00:00:00' COMMENT 'số giờ đặt tặng',
  `gifted_chip_hours` time NOT NULL DEFAULT '00:00:00' COMMENT 'số giờ chip tặng',
  `contract_date` timestamp NULL DEFAULT NULL COMMENT 'Ngày ký hợp đồng',
  `contract_image` varchar(255) DEFAULT NULL,
  `graduation_date` timestamp NULL DEFAULT NULL COMMENT 'Ngày tốt nghiệp',
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `note` text DEFAULT NULL COMMENT 'Ghi chú thông tin đặc biệt liên quan đến khóa học và học viên',
  `health_check_date` timestamp NULL DEFAULT NULL COMMENT 'Ngày khám sức khỏe',
  `sale_id` bigint(20) UNSIGNED DEFAULT NULL,
  `hours` double(8,2) NOT NULL DEFAULT 0.00 COMMENT 'Số giờ đã học',
  `km` decimal(8,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT 'Trạng thái khóa học (active, inactive)',
  `tuition_fee` bigint(20) DEFAULT NULL COMMENT 'Học phí khóa học',
  `start_date` date DEFAULT NULL COMMENT 'Ngày khai giảng',
  `end_date` date DEFAULT NULL COMMENT 'Ngày bế giảng',
  `cabin_learning_date` date DEFAULT NULL COMMENT 'Ngày học cabin',
  `exam_field_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_user`
--

CREATE TABLE `course_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `curriculums`
--

CREATE TABLE `curriculums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên giáo trình',
  `type` bigint(20) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0 là xe máy, 1 là ô tô',
  `title` varchar(255) NOT NULL COMMENT 'Loại giáo trình: Cơ bản / Nâng cao',
  `description` text DEFAULT NULL COMMENT 'Mô tả về giáo trình: là nội dung hiển thị để giới thiệu về khóa học ở client',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `driving_sessions`
--

CREATE TABLE `driving_sessions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `instructor_id` bigint(20) UNSIGNED NOT NULL,
  `session_id` char(36) NOT NULL COMMENT 'id của phiên học',
  `start_time` timestamp NOT NULL COMMENT 'thời gian bắt đầu',
  `end_time` timestamp NULL DEFAULT NULL COMMENT 'thời gian kết thúc',
  `duration` int(11) NOT NULL COMMENT 'thời gian chạy',
  `distance` decimal(8,3) NOT NULL COMMENT 'quãng đường chạy',
  `start_lat` varchar(50) DEFAULT NULL COMMENT 'vĩ độ bắt đầu',
  `start_lng` varchar(50) DEFAULT NULL COMMENT 'kinh độ bắt đầu',
  `end_lat` varchar(50) DEFAULT NULL COMMENT 'vĩ độ kết thúc',
  `end_lng` varchar(50) DEFAULT NULL COMMENT 'kinh độ kết thúc',
  `trainee_id` bigint(20) UNSIGNED NOT NULL COMMENT 'id của học viên trên hệ thống sát hạch',
  `trainee_name` varchar(255) NOT NULL COMMENT 'tên của học viên',
  `instructor_name` varchar(255) NOT NULL COMMENT 'tên của giảng viên',
  `vehicle_plate` varchar(255) NOT NULL COMMENT 'biển số xe',
  `ten_khoa_hoc` varchar(255) NOT NULL COMMENT 'tên khóa học',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `emails`
--

CREATE TABLE `emails` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `to` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `template` varchar(255) NOT NULL,
  `params` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`params`)),
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `log` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam_fields`
--

CREATE TABLE `exam_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên lĩnh vực thi',
  `is_practical` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL COMMENT 'Mô tả lĩnh vực thi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam_schedules`
--

CREATE TABLE `exam_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `stadium_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Sân thi',
  `description` text DEFAULT NULL,
  `status` enum('scheduled','canceled') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam_schedule_exam_field`
--

CREATE TABLE `exam_schedule_exam_field` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `exam_field_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam_schedule_ranking`
--

CREATE TABLE `exam_schedule_ranking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_schedule_id` bigint(20) UNSIGNED NOT NULL,
  `ranking_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam_sets`
--

CREATE TABLE `exam_sets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'tên bộ đề',
  `time_do` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `license_level` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL COMMENT 'kiểu bộ đề: đề thi thử, đề ôn tập, câu hỏi ôn tập,etc...',
  `description` text DEFAULT NULL COMMENT 'mô tả về bộ đề',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `lesson_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `exam_set_question`
--

CREATE TABLE `exam_set_question` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `exam_set_id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `fees`
--

CREATE TABLE `fees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Ngày thanh toán',
  `amount` bigint(20) UNSIGNED NOT NULL COMMENT 'Số tiền đã đóng',
  `fee_type` tinyint(3) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Tài khoản học viên',
  `is_received` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Trạng thái thanh toán',
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Khóa học người dùng tham gia',
  `collector_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_bookmarks`
--

CREATE TABLE `law_bookmarks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bookmark_code` varchar(50) NOT NULL,
  `bookmark_type_id` bigint(20) UNSIGNED NOT NULL,
  `bookmark_description` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_bookmark_types`
--

CREATE TABLE `law_bookmark_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bookmark_name` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_topics`
--

CREATE TABLE `law_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_name` text NOT NULL,
  `subtitle` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_vehicle_types`
--

CREATE TABLE `law_vehicle_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_violations`
--

CREATE TABLE `law_violations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `description` text NOT NULL,
  `entities` text NOT NULL,
  `fines` text NOT NULL,
  `additional_penalties` text DEFAULT NULL,
  `remedial` text DEFAULT NULL,
  `other_penalties` text DEFAULT NULL,
  `type_id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `image` text DEFAULT NULL,
  `keyword` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `violation_no` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_violation_bookmarks`
--

CREATE TABLE `law_violation_bookmarks` (
  `violation_id` bigint(20) UNSIGNED NOT NULL,
  `bookmark_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `law_violation_relations`
--

CREATE TABLE `law_violation_relations` (
  `violation_id` bigint(20) UNSIGNED NOT NULL,
  `related_violation_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lead_sources`
--

CREATE TABLE `lead_sources` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên nguồn',
  `description` text DEFAULT NULL COMMENT 'Mô tả về nguồn',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `learning_fields`
--

CREATE TABLE `learning_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'tên lĩnh vực học',
  `price` decimal(8,2) UNSIGNED NOT NULL DEFAULT 0.00 COMMENT 'giá thành giảng dạy trên giờ',
  `teaching_mode` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0: 1 kèm 1, 1: Dạy nhiều người',
  `is_practical` tinyint(1) NOT NULL DEFAULT 0,
  `applies_to_all_rankings` tinyint(1) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lessons`
--

CREATE TABLE `lessons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `curriculum_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Giáo trình id',
  `title` varchar(255) NOT NULL COMMENT 'Tên bài học',
  `description` text DEFAULT NULL COMMENT 'Mô tả chi tiết về bài học',
  `sequence` int(10) UNSIGNED NOT NULL COMMENT 'Thứ tự của bài học trong giáo trình',
  `visibility` enum('private','public') NOT NULL DEFAULT 'private',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lesson_ranking`
--

CREATE TABLE `lesson_ranking` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `ranking_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lesson_student`
--

CREATE TABLE `lesson_student` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('open','closed') NOT NULL DEFAULT 'closed' COMMENT 'set mở cho bài học tiếp theo khi hoàn thành bài học trước đó',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `materials`
--

CREATE TABLE `materials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL COMMENT 'Tiêu đề tài liệu',
  `type` varchar(255) DEFAULT NULL COMMENT 'Loại tài liệu',
  `file_path` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn tới tài liệu',
  `url` varchar(255) DEFAULT NULL COMMENT 'Link tới tài liệu nếu là tài liệu trực tuyến',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `chapter_id` bigint(20) UNSIGNED NOT NULL,
  `total_time` varchar(255) DEFAULT NULL,
  `start_time` varchar(255) DEFAULT NULL,
  `end_time` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'Kiểu thông báo',
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL COMMENT 'Dữ liệu thông báo',
  `read_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian đọc thông báo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pages`
--

CREATE TABLE `pages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `chapter_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quizzes`
--

CREATE TABLE `quizzes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_set_id` bigint(20) UNSIGNED NOT NULL COMMENT 'Bộ câu hỏi',
  `question` text NOT NULL COMMENT 'Câu hỏi',
  `name` varchar(255) DEFAULT NULL COMMENT 'Tên câu hỏi',
  `image` text DEFAULT NULL COMMENT 'Ảnh của câu hỏi',
  `explanation` text DEFAULT NULL COMMENT 'Nội dung giải thích về câu hỏi đó',
  `mandatory` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'quy định câu hỏi là câu bắt buộc đúng hay không',
  `wrong` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'câu hỏi hay bị sai',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tip` text DEFAULT NULL COMMENT 'cách làm nhanh, nhận diện đáp án cho câu hỏi đó',
  `tip_image` varchar(255) DEFAULT NULL COMMENT 'hình ảnh mô tả việc nhận diện đáp án đúng cho câu hỏi đó'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_options`
--

CREATE TABLE `quiz_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quiz_id` bigint(20) UNSIGNED NOT NULL COMMENT 'ID câu hỏi',
  `option_text` text NOT NULL COMMENT 'Các lựa chọn của quiz',
  `is_correct` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Đáp án của quiz',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quiz_sets`
--

CREATE TABLE `quiz_sets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `lesson_id` bigint(20) UNSIGNED NOT NULL COMMENT 'id cho biết thuộc về bài nào',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rankings`
--

CREATE TABLE `rankings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'tên hạng bằng',
  `fee_ranking` decimal(10,2) DEFAULT NULL,
  `vehicle_type` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: Xe máy, 1: Ô tô',
  `min_automatic_car_hours` double(8,2) NOT NULL DEFAULT 0.00,
  `min_night_hours` double(8,2) NOT NULL DEFAULT 0.00,
  `min_hours` double(8,2) NOT NULL DEFAULT 0.00,
  `min_km` double(8,3) NOT NULL DEFAULT 0.000,
  `description` text DEFAULT NULL COMMENT 'mô tả hạng bằng',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ranking_user`
--

CREATE TABLE `ranking_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ranking_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `simulations`
--

CREATE TABLE `simulations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL COMMENT 'Tên bộ đề mô phỏng',
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `simulation_material`
--

CREATE TABLE `simulation_material` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `simulation_id` bigint(20) UNSIGNED NOT NULL,
  `material_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `stadiums`
--

CREATE TABLE `stadiums` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `location` text NOT NULL,
  `google_maps_url` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_code` varchar(255) NOT NULL COMMENT 'Mã học viên',
  `ranking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `card_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'Mã số thẻ được đính kèm khi thực hiện sát hạch',
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'Date of Birth',
  `identity_card` varchar(20) DEFAULT NULL COMMENT 'Identity Card Number',
  `address` varchar(255) DEFAULT NULL,
  `date_of_profile_set` date DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'User status',
  `status_lead` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL COMMENT 'Mô tả học viên, có thể lưu các thông tin khác như có quan tâm đến các bằng khác không, mức độ quan tâm',
  `became_student_at` timestamp NULL DEFAULT NULL,
  `is_student` tinyint(1) NOT NULL DEFAULT 0,
  `is_lead` tinyint(1) NOT NULL DEFAULT 0,
  `sale_support` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'người chăm sóc khách hàng',
  `interest_level` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `lead_source` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'nguồn khách hàng',
  `converted_by` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'người xác nhận đóng phí và chuyển đổi tài khoản khách hàng thành học viên',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `trainee_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'id của học viên trên hệ thống sát hạch'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student_assignments`
--

CREATE TABLE `student_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `assignment_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'chưa hoàn thành',
  `score` double(8,2) DEFAULT 0.00,
  `note` text DEFAULT NULL COMMENT 'có thể lưu lại khi người dùng đã quá hạn và bị đánh false',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student_exam_fields`
--

CREATE TABLE `student_exam_fields` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `exam_field_id` bigint(20) UNSIGNED NOT NULL,
  `type_exam` int(10) UNSIGNED DEFAULT NULL COMMENT 'Loại thi: 1 thi hết môn thực hành, 2 thi hết môn lý thuyết, 3 thi tốt nghiệp, 4 thi sát hạch',
  `attempt_number` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'Số lần đã thi',
  `status` tinyint(4) NOT NULL DEFAULT 0 COMMENT 'lưu lại trạng thái môn thi đó: 0 là chưa hoàn thành, 1 là hoàn thành',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `student_statuses`
--

CREATE TABLE `student_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `learning_field_id` bigint(20) UNSIGNED NOT NULL,
  `hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'tổng số giờ học',
  `km` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'số km chạy được',
  `night_hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'số giờ chạy đêm',
  `auto_hours` double(8,3) NOT NULL DEFAULT 0.000 COMMENT 'số giờ chạy tự động',
  `status` tinyint(3) UNSIGNED NOT NULL DEFAULT 0 COMMENT '0: chưa hoàn thành, 1: đã hoàn thành, 2: đã bỏ',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tips`
--

CREATE TABLE `tips` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tip_type` tinyint(3) UNSIGNED NOT NULL,
  `quiz_set_id` bigint(20) UNSIGNED DEFAULT NULL,
  `page_id` bigint(20) UNSIGNED DEFAULT NULL,
  `content` text NOT NULL,
  `question` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`question`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `traffic_signs`
--

CREATE TABLE `traffic_signs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(3) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `dob` date DEFAULT NULL COMMENT 'Date of Birth',
  `identity_card` varchar(20) DEFAULT NULL COMMENT 'Identity Card Number',
  `license_number` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active' COMMENT 'User status',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `instructor_id` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'id của giảng viên trên hệ thống sát hạch',
  `vehicle_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vehicles`
--

CREATE TABLE `vehicles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `license_plate` varchar(255) NOT NULL COMMENT 'biển số',
  `model` varchar(255) NOT NULL,
  `ranking_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'loại xe: xe con, xe 7 chỗ, xe ? chỗ,...',
  `color` varchar(255) NOT NULL COMMENT 'màu sắc của xe',
  `training_license_number` varchar(255) DEFAULT NULL COMMENT 'số giấy phép tập lái',
  `manufacture_year` year(4) NOT NULL COMMENT 'năm sản xuất',
  `description` text DEFAULT NULL COMMENT 'mô tả',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vehicle_expenses`
--

CREATE TABLE `vehicle_expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `vehicle_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('simulation','refuel','maintenance','inspection','tire_replacement','other') NOT NULL,
  `time` datetime NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `activation_codes`
--
ALTER TABLE `activation_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `activation_codes_activation_code_unique` (`activation_code`);

--
-- Chỉ mục cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `activity_log_subject_type_subject_id_index` (`subject_type`,`subject_id`),
  ADD KEY `activity_log_causer_type_causer_id_index` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Chỉ mục cho bảng `assignments`
--
ALTER TABLE `assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `assignments_quiz_set_id_foreign` (`quiz_set_id`);

--
-- Chỉ mục cho bảng `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendars_learning_field_id_foreign` (`learning_field_id`),
  ADD KEY `calendars_exam_field_id_foreign` (`exam_field_id`),
  ADD KEY `calendars_stadium_id_foreign` (`stadium_id`),
  ADD KEY `calendars_exam_schedule_id_foreign` (`exam_schedule_id`),
  ADD KEY `calendars_vehicle_id_foreign` (`vehicle_id`);

--
-- Chỉ mục cho bảng `calendar_course`
--
ALTER TABLE `calendar_course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_course_calendar_id_foreign` (`calendar_id`),
  ADD KEY `calendar_course_course_id_foreign` (`course_id`);

--
-- Chỉ mục cho bảng `calendar_student`
--
ALTER TABLE `calendar_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_student_calendar_id_foreign` (`calendar_id`),
  ADD KEY `calendar_student_student_id_foreign` (`student_id`);

--
-- Chỉ mục cho bảng `calendar_student_exam_field`
--
ALTER TABLE `calendar_student_exam_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `calendar_student_exam_unique` (`calendar_student_id`,`exam_field_id`),
  ADD KEY `calendar_student_exam_field_exam_field_id_foreign` (`exam_field_id`);

--
-- Chỉ mục cho bảng `calendar_user`
--
ALTER TABLE `calendar_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_user_calendar_id_foreign` (`calendar_id`),
  ADD KEY `calendar_user_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_code_unique` (`code`),
  ADD KEY `courses_curriculum_id_foreign` (`curriculum_id`),
  ADD KEY `courses_ranking_id_foreign` (`ranking_id`);

--
-- Chỉ mục cho bảng `course_exam_field`
--
ALTER TABLE `course_exam_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_exam_field_course_id_foreign` (`course_id`),
  ADD KEY `course_exam_field_exam_field_id_foreign` (`exam_field_id`);

--
-- Chỉ mục cho bảng `course_learning_field`
--
ALTER TABLE `course_learning_field`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_learning_field_course_id_foreign` (`course_id`),
  ADD KEY `course_learning_field_learning_field_id_foreign` (`learning_field_id`);

--
-- Chỉ mục cho bảng `course_students`
--
ALTER TABLE `course_students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_students_student_id_foreign` (`student_id`),
  ADD KEY `course_students_course_id_foreign` (`course_id`),
  ADD KEY `course_students_teacher_id_foreign` (`teacher_id`),
  ADD KEY `course_students_sale_id_foreign` (`sale_id`),
  ADD KEY `course_students_exam_field_id_foreign` (`exam_field_id`),
  ADD KEY `course_students_stadium_id_foreign` (`stadium_id`);

--
-- Chỉ mục cho bảng `course_user`
--
ALTER TABLE `course_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_user_course_id_foreign` (`course_id`),
  ADD KEY `course_user_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `curriculums`
--
ALTER TABLE `curriculums`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `driving_sessions`
--
ALTER TABLE `driving_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `driving_sessions_session_id_unique` (`session_id`);

--
-- Chỉ mục cho bảng `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `exam_fields`
--
ALTER TABLE `exam_fields`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_fields_name_unique` (`name`);

--
-- Chỉ mục cho bảng `exam_schedules`
--
ALTER TABLE `exam_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_schedules_stadium_id_foreign` (`stadium_id`);

--
-- Chỉ mục cho bảng `exam_schedule_exam_field`
--
ALTER TABLE `exam_schedule_exam_field`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_schedule_exam_field_exam_schedule_id_exam_field_id_unique` (`exam_schedule_id`,`exam_field_id`),
  ADD KEY `exam_schedule_exam_field_exam_field_id_foreign` (`exam_field_id`);

--
-- Chỉ mục cho bảng `exam_schedule_ranking`
--
ALTER TABLE `exam_schedule_ranking`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `exam_schedule_ranking_exam_schedule_id_ranking_id_unique` (`exam_schedule_id`,`ranking_id`),
  ADD KEY `exam_schedule_ranking_ranking_id_foreign` (`ranking_id`);

--
-- Chỉ mục cho bảng `exam_sets`
--
ALTER TABLE `exam_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_sets_lesson_id_foreign` (`lesson_id`),
  ADD KEY `exam_sets_license_level_foreign` (`license_level`);

--
-- Chỉ mục cho bảng `exam_set_question`
--
ALTER TABLE `exam_set_question`
  ADD PRIMARY KEY (`id`),
  ADD KEY `exam_set_question_exam_set_id_foreign` (`exam_set_id`),
  ADD KEY `exam_set_question_question_id_foreign` (`quiz_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `fees`
--
ALTER TABLE `fees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fees_student_id_foreign` (`student_id`),
  ADD KEY `fees_collector_id_foreign` (`collector_id`);

--
-- Chỉ mục cho bảng `law_bookmarks`
--
ALTER TABLE `law_bookmarks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `law_bookmarks_bookmark_type_id_foreign` (`bookmark_type_id`);

--
-- Chỉ mục cho bảng `law_bookmark_types`
--
ALTER TABLE `law_bookmark_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `law_topics`
--
ALTER TABLE `law_topics`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `law_vehicle_types`
--
ALTER TABLE `law_vehicle_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `law_violations`
--
ALTER TABLE `law_violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `law_violations_type_id_foreign` (`type_id`),
  ADD KEY `law_violations_topic_id_foreign` (`topic_id`);

--
-- Chỉ mục cho bảng `law_violation_bookmarks`
--
ALTER TABLE `law_violation_bookmarks`
  ADD PRIMARY KEY (`violation_id`,`bookmark_id`),
  ADD KEY `law_violation_bookmarks_bookmark_id_foreign` (`bookmark_id`);

--
-- Chỉ mục cho bảng `law_violation_relations`
--
ALTER TABLE `law_violation_relations`
  ADD PRIMARY KEY (`violation_id`,`related_violation_id`),
  ADD KEY `law_violation_relations_related_violation_id_foreign` (`related_violation_id`);

--
-- Chỉ mục cho bảng `lead_sources`
--
ALTER TABLE `lead_sources`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lead_sources_name_unique` (`name`);

--
-- Chỉ mục cho bảng `learning_fields`
--
ALTER TABLE `learning_fields`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `lessons_curriculum_id_sequence_unique` (`curriculum_id`,`sequence`);

--
-- Chỉ mục cho bảng `lesson_ranking`
--
ALTER TABLE `lesson_ranking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_ranking_lesson_id_foreign` (`lesson_id`),
  ADD KEY `lesson_ranking_ranking_id_foreign` (`ranking_id`);

--
-- Chỉ mục cho bảng `lesson_student`
--
ALTER TABLE `lesson_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lesson_student_lesson_id_foreign` (`lesson_id`),
  ADD KEY `lesson_student_student_id_foreign` (`student_id`);

--
-- Chỉ mục cho bảng `materials`
--
ALTER TABLE `materials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `materials_chapter_id_foreign` (`chapter_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Chỉ mục cho bảng `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Chỉ mục cho bảng `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quizzes_quiz_set_id_foreign` (`quiz_set_id`);

--
-- Chỉ mục cho bảng `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_options_quiz_id_foreign` (`quiz_id`);

--
-- Chỉ mục cho bảng `quiz_sets`
--
ALTER TABLE `quiz_sets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_sets_lesson_id_foreign` (`lesson_id`);

--
-- Chỉ mục cho bảng `rankings`
--
ALTER TABLE `rankings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rankings_name_unique` (`name`);

--
-- Chỉ mục cho bảng `ranking_user`
--
ALTER TABLE `ranking_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ranking_user_user_id_foreign` (`user_id`),
  ADD KEY `ranking_user_ranking_id_foreign` (`ranking_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Chỉ mục cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Chỉ mục cho bảng `simulations`
--
ALTER TABLE `simulations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `simulation_material`
--
ALTER TABLE `simulation_material`
  ADD PRIMARY KEY (`id`),
  ADD KEY `simulation_material_simulation_id_foreign` (`simulation_id`),
  ADD KEY `simulation_material_material_id_foreign` (`material_id`);

--
-- Chỉ mục cho bảng `stadiums`
--
ALTER TABLE `stadiums`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_student_code_unique` (`student_code`),
  ADD UNIQUE KEY `students_email_unique` (`email`),
  ADD UNIQUE KEY `students_phone_unique` (`phone`),
  ADD KEY `students_sale_support_foreign` (`sale_support`),
  ADD KEY `students_lead_source_foreign` (`lead_source`),
  ADD KEY `students_converted_by_foreign` (`converted_by`),
  ADD KEY `students_ranking_id_foreign` (`ranking_id`);

--
-- Chỉ mục cho bảng `student_assignments`
--
ALTER TABLE `student_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_assignments_student_id_foreign` (`student_id`),
  ADD KEY `student_assignments_assignment_id_foreign` (`assignment_id`);

--
-- Chỉ mục cho bảng `student_exam_fields`
--
ALTER TABLE `student_exam_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_exam_fields_student_id_foreign` (`student_id`),
  ADD KEY `student_exam_fields_course_id_foreign` (`course_id`),
  ADD KEY `student_exam_fields_exam_field_id_foreign` (`exam_field_id`);

--
-- Chỉ mục cho bảng `student_statuses`
--
ALTER TABLE `student_statuses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_statuses_student_id_foreign` (`student_id`),
  ADD KEY `student_statuses_course_id_foreign` (`course_id`),
  ADD KEY `student_statuses_learning_field_id_foreign` (`learning_field_id`);

--
-- Chỉ mục cho bảng `tips`
--
ALTER TABLE `tips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tips_quiz_set_id_foreign` (`quiz_set_id`),
  ADD KEY `tips_page_id_foreign` (`page_id`);

--
-- Chỉ mục cho bảng `traffic_signs`
--
ALTER TABLE `traffic_signs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `traffic_signs_code_unique` (`code`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_vehicle_id_foreign` (`vehicle_id`);

--
-- Chỉ mục cho bảng `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehicles_license_plate_unique` (`license_plate`),
  ADD UNIQUE KEY `vehicles_training_license_number_unique` (`training_license_number`),
  ADD KEY `vehicles_ranking_id_foreign` (`ranking_id`);

--
-- Chỉ mục cho bảng `vehicle_expenses`
--
ALTER TABLE `vehicle_expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vehicle_expenses_vehicle_id_foreign` (`vehicle_id`),
  ADD KEY `vehicle_expenses_user_id_foreign` (`user_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `activation_codes`
--
ALTER TABLE `activation_codes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `assignments`
--
ALTER TABLE `assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `calendar_course`
--
ALTER TABLE `calendar_course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `calendar_student`
--
ALTER TABLE `calendar_student`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `calendar_student_exam_field`
--
ALTER TABLE `calendar_student_exam_field`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `calendar_user`
--
ALTER TABLE `calendar_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `course_exam_field`
--
ALTER TABLE `course_exam_field`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `course_learning_field`
--
ALTER TABLE `course_learning_field`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `course_students`
--
ALTER TABLE `course_students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `course_user`
--
ALTER TABLE `course_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `curriculums`
--
ALTER TABLE `curriculums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `driving_sessions`
--
ALTER TABLE `driving_sessions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `emails`
--
ALTER TABLE `emails`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `exam_fields`
--
ALTER TABLE `exam_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `exam_schedules`
--
ALTER TABLE `exam_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `exam_schedule_exam_field`
--
ALTER TABLE `exam_schedule_exam_field`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `exam_schedule_ranking`
--
ALTER TABLE `exam_schedule_ranking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `exam_sets`
--
ALTER TABLE `exam_sets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `exam_set_question`
--
ALTER TABLE `exam_set_question`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `fees`
--
ALTER TABLE `fees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `law_bookmarks`
--
ALTER TABLE `law_bookmarks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `law_bookmark_types`
--
ALTER TABLE `law_bookmark_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `law_topics`
--
ALTER TABLE `law_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `law_vehicle_types`
--
ALTER TABLE `law_vehicle_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `law_violations`
--
ALTER TABLE `law_violations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lead_sources`
--
ALTER TABLE `lead_sources`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `learning_fields`
--
ALTER TABLE `learning_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lesson_ranking`
--
ALTER TABLE `lesson_ranking`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `lesson_student`
--
ALTER TABLE `lesson_student`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `materials`
--
ALTER TABLE `materials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `pages`
--
ALTER TABLE `pages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `quiz_options`
--
ALTER TABLE `quiz_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `quiz_sets`
--
ALTER TABLE `quiz_sets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `rankings`
--
ALTER TABLE `rankings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `ranking_user`
--
ALTER TABLE `ranking_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `simulations`
--
ALTER TABLE `simulations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `simulation_material`
--
ALTER TABLE `simulation_material`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `stadiums`
--
ALTER TABLE `stadiums`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `student_assignments`
--
ALTER TABLE `student_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `student_exam_fields`
--
ALTER TABLE `student_exam_fields`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `student_statuses`
--
ALTER TABLE `student_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `tips`
--
ALTER TABLE `tips`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `traffic_signs`
--
ALTER TABLE `traffic_signs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `vehicle_expenses`
--
ALTER TABLE `vehicle_expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `assignments`
--
ALTER TABLE `assignments`
  ADD CONSTRAINT `assignments_quiz_set_id_foreign` FOREIGN KEY (`quiz_set_id`) REFERENCES `quiz_sets` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `calendars`
--
ALTER TABLE `calendars`
  ADD CONSTRAINT `calendars_exam_field_id_foreign` FOREIGN KEY (`exam_field_id`) REFERENCES `exam_fields` (`id`),
  ADD CONSTRAINT `calendars_exam_schedule_id_foreign` FOREIGN KEY (`exam_schedule_id`) REFERENCES `exam_schedules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `calendars_learning_field_id_foreign` FOREIGN KEY (`learning_field_id`) REFERENCES `learning_fields` (`id`),
  ADD CONSTRAINT `calendars_stadium_id_foreign` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `calendars_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `calendar_course`
--
ALTER TABLE `calendar_course`
  ADD CONSTRAINT `calendar_course_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calendar_course_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `calendar_student`
--
ALTER TABLE `calendar_student`
  ADD CONSTRAINT `calendar_student_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calendar_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `calendar_student_exam_field`
--
ALTER TABLE `calendar_student_exam_field`
  ADD CONSTRAINT `calendar_student_exam_field_calendar_student_id_foreign` FOREIGN KEY (`calendar_student_id`) REFERENCES `calendar_student` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calendar_student_exam_field_exam_field_id_foreign` FOREIGN KEY (`exam_field_id`) REFERENCES `exam_fields` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `calendar_user`
--
ALTER TABLE `calendar_user`
  ADD CONSTRAINT `calendar_user_calendar_id_foreign` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `calendar_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courses_ranking_id_foreign` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_exam_field`
--
ALTER TABLE `course_exam_field`
  ADD CONSTRAINT `course_exam_field_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_exam_field_exam_field_id_foreign` FOREIGN KEY (`exam_field_id`) REFERENCES `exam_fields` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_learning_field`
--
ALTER TABLE `course_learning_field`
  ADD CONSTRAINT `course_learning_field_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_learning_field_learning_field_id_foreign` FOREIGN KEY (`learning_field_id`) REFERENCES `learning_fields` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_students`
--
ALTER TABLE `course_students`
  ADD CONSTRAINT `course_students_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_students_exam_field_id_foreign` FOREIGN KEY (`exam_field_id`) REFERENCES `exam_fields` (`id`),
  ADD CONSTRAINT `course_students_stadium_id_foreign` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `course_students_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_user`
--
ALTER TABLE `course_user`
  ADD CONSTRAINT `course_user_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `exam_schedules`
--
ALTER TABLE `exam_schedules`
  ADD CONSTRAINT `exam_schedules_stadium_id_foreign` FOREIGN KEY (`stadium_id`) REFERENCES `stadiums` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `exam_schedule_exam_field`
--
ALTER TABLE `exam_schedule_exam_field`
  ADD CONSTRAINT `exam_schedule_exam_field_exam_field_id_foreign` FOREIGN KEY (`exam_field_id`) REFERENCES `exam_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_schedule_exam_field_exam_schedule_id_foreign` FOREIGN KEY (`exam_schedule_id`) REFERENCES `exam_schedules` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `exam_schedule_ranking`
--
ALTER TABLE `exam_schedule_ranking`
  ADD CONSTRAINT `exam_schedule_ranking_exam_schedule_id_foreign` FOREIGN KEY (`exam_schedule_id`) REFERENCES `exam_schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_schedule_ranking_ranking_id_foreign` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `exam_sets`
--
ALTER TABLE `exam_sets`
  ADD CONSTRAINT `exam_sets_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_sets_license_level_foreign` FOREIGN KEY (`license_level`) REFERENCES `rankings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `exam_set_question`
--
ALTER TABLE `exam_set_question`
  ADD CONSTRAINT `exam_set_question_exam_set_id_foreign` FOREIGN KEY (`exam_set_id`) REFERENCES `exam_sets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_set_question_question_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `fees`
--
ALTER TABLE `fees`
  ADD CONSTRAINT `fees_collector_id_foreign` FOREIGN KEY (`collector_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fees_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `law_bookmarks`
--
ALTER TABLE `law_bookmarks`
  ADD CONSTRAINT `law_bookmarks_bookmark_type_id_foreign` FOREIGN KEY (`bookmark_type_id`) REFERENCES `law_bookmark_types` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `law_violations`
--
ALTER TABLE `law_violations`
  ADD CONSTRAINT `law_violations_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `law_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `law_violations_type_id_foreign` FOREIGN KEY (`type_id`) REFERENCES `law_vehicle_types` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `law_violation_bookmarks`
--
ALTER TABLE `law_violation_bookmarks`
  ADD CONSTRAINT `law_violation_bookmarks_bookmark_id_foreign` FOREIGN KEY (`bookmark_id`) REFERENCES `law_bookmarks` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `law_violation_bookmarks_violation_id_foreign` FOREIGN KEY (`violation_id`) REFERENCES `law_violations` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `law_violation_relations`
--
ALTER TABLE `law_violation_relations`
  ADD CONSTRAINT `law_violation_relations_related_violation_id_foreign` FOREIGN KEY (`related_violation_id`) REFERENCES `law_violations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `law_violation_relations_violation_id_foreign` FOREIGN KEY (`violation_id`) REFERENCES `law_violations` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lessons`
--
ALTER TABLE `lessons`
  ADD CONSTRAINT `lessons_curriculum_id_foreign` FOREIGN KEY (`curriculum_id`) REFERENCES `curriculums` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lesson_ranking`
--
ALTER TABLE `lesson_ranking`
  ADD CONSTRAINT `lesson_ranking_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_ranking_ranking_id_foreign` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lesson_student`
--
ALTER TABLE `lesson_student`
  ADD CONSTRAINT `lesson_student_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lesson_student_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `materials`
--
ALTER TABLE `materials`
  ADD CONSTRAINT `materials_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_quiz_set_id_foreign` FOREIGN KEY (`quiz_set_id`) REFERENCES `quiz_sets` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quiz_options`
--
ALTER TABLE `quiz_options`
  ADD CONSTRAINT `quiz_options_quiz_id_foreign` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `quiz_sets`
--
ALTER TABLE `quiz_sets`
  ADD CONSTRAINT `quiz_sets_lesson_id_foreign` FOREIGN KEY (`lesson_id`) REFERENCES `lessons` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `ranking_user`
--
ALTER TABLE `ranking_user`
  ADD CONSTRAINT `ranking_user_ranking_id_foreign` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ranking_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `simulation_material`
--
ALTER TABLE `simulation_material`
  ADD CONSTRAINT `simulation_material_material_id_foreign` FOREIGN KEY (`material_id`) REFERENCES `materials` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `simulation_material_simulation_id_foreign` FOREIGN KEY (`simulation_id`) REFERENCES `simulations` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_converted_by_foreign` FOREIGN KEY (`converted_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_lead_source_foreign` FOREIGN KEY (`lead_source`) REFERENCES `lead_sources` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_ranking_id_foreign` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `students_sale_support_foreign` FOREIGN KEY (`sale_support`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `student_assignments`
--
ALTER TABLE `student_assignments`
  ADD CONSTRAINT `student_assignments_assignment_id_foreign` FOREIGN KEY (`assignment_id`) REFERENCES `assignments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_assignments_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `student_exam_fields`
--
ALTER TABLE `student_exam_fields`
  ADD CONSTRAINT `student_exam_fields_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_exam_fields_exam_field_id_foreign` FOREIGN KEY (`exam_field_id`) REFERENCES `exam_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_exam_fields_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `student_statuses`
--
ALTER TABLE `student_statuses`
  ADD CONSTRAINT `student_statuses_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_statuses_learning_field_id_foreign` FOREIGN KEY (`learning_field_id`) REFERENCES `learning_fields` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `student_statuses_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tips`
--
ALTER TABLE `tips`
  ADD CONSTRAINT `tips_page_id_foreign` FOREIGN KEY (`page_id`) REFERENCES `pages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tips_quiz_set_id_foreign` FOREIGN KEY (`quiz_set_id`) REFERENCES `quiz_sets` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `vehicles`
--
ALTER TABLE `vehicles`
  ADD CONSTRAINT `vehicles_ranking_id_foreign` FOREIGN KEY (`ranking_id`) REFERENCES `rankings` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `vehicle_expenses`
--
ALTER TABLE `vehicle_expenses`
  ADD CONSTRAINT `vehicle_expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `vehicle_expenses_vehicle_id_foreign` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
