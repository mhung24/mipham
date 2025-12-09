-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 09, 2025 lúc 07:05 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `web_server`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `name`, `image`) VALUES
(1, 'Trang Điểm', 'uploads/banner_makeup.jpg'),
(2, 'Son Môi', 'uploads/banner_lipstick.jpg'),
(3, 'Chăm Sóc Da', 'uploads/banner_skincare.jpg'),
(4, 'Chăm Sóc Cơ Thể', 'uploads/banner_bodycare.jpg'),
(5, 'Nước Hoa', 'uploads/banner_perfume.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`) VALUES
(1, 'Black Rouge'),
(2, '3CE'),
(3, 'L\'Oreal'),
(7, 'Paula\'s Choice'),
(8, 'EUCERIN'),
(9, 'CELL FUSION C'),
(10, 'aa'),
(11, 'ttt1'),
(12, 'a'),
(13, '123'),
(14, '123aa'),
(15, '123123');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `quantity`, `created_at`) VALUES
(1, 3, 4, 8, '2025-12-09 17:02:12');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `parent_id` int(11) DEFAULT 0 COMMENT '0: Cấp cha, >0: ID của cha',
  `icon` varchar(50) DEFAULT NULL COMMENT 'Icon Bootstrap (VD: bi-brush)',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự hiển thị'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `parent_id`, `icon`, `sort_order`) VALUES
(1, 'Trang điểm', 0, NULL, 0),
(2, 'Trang điểm mặt', 1, NULL, 0),
(3, 'Kem lót', 2, NULL, 0),
(4, 'Kem Nền - BB Cream', 2, NULL, 0),
(5, 'Che Khuyết Điểm', 2, NULL, 0),
(6, 'Phấn Phủ', 2, NULL, 0),
(7, 'Xịt khoá nền', 2, NULL, 0),
(9, 'Phấn Nước - Cushion', 2, NULL, 0),
(10, 'Tạo Khối - Hightlight', 2, NULL, 0),
(12, 'Phấn Má', 2, NULL, 0),
(13, 'Trang điểm mắt', 1, NULL, 0),
(14, 'Phấn Mắt/Nhũ Mắt', 13, NULL, 0),
(15, 'Kẻ mắt', 13, NULL, 0),
(16, 'Kẻ chân mày', 13, NULL, 0),
(17, 'Mascara', 0, NULL, 0),
(18, 'Son Môi', 0, NULL, 0),
(19, 'Son thỏi', 18, NULL, 0),
(20, 'Son Bóng', 18, NULL, 0),
(21, 'Son Dưỡng', 18, NULL, 0),
(22, 'Tẩy Tế Bào Chết Môi', 18, NULL, 0),
(23, 'Son Kem', 18, NULL, 0),
(24, 'Mặt Nạ Ngủ Môi', 18, NULL, 0),
(25, 'Chăm Sóc Da', 0, NULL, 0),
(26, 'Làm sạch', 25, NULL, 0),
(27, 'Tẩy Trang', 26, NULL, 0),
(28, 'Sửa Rửa Mặt', 26, NULL, 0),
(29, 'Tẩy Tế Bào Chết Mặt', 26, NULL, 0),
(30, 'Dưỡng Da', 25, NULL, 0),
(31, 'Nước Hoa Hồng - Toner', 30, NULL, 0),
(32, 'Serum/Tinh Chất', 30, NULL, 0),
(33, 'Lotion/Sữa Dưỡng', 30, NULL, 0),
(34, 'Kem Dưỡng', 30, NULL, 0),
(35, 'Xịt khoáng', 30, NULL, 0),
(36, 'Mặt Nạ', 25, NULL, 0),
(37, 'Mặt nạ giấy', 36, NULL, 0),
(38, 'Mặt Nạ Rửa', 36, NULL, 0),
(39, 'Mặt Nạ Ngủ', 36, NULL, 0),
(40, 'Mặt Nạ Lột', 36, NULL, 0),
(41, 'Kem Chống Nắng', 25, NULL, 0),
(42, 'Đặt Trị', 25, NULL, 0),
(43, 'Trị Mụn', 42, NULL, 0),
(44, 'Trị Sẹo', 42, NULL, 0),
(45, 'Trị nám- Tàn Nhang', 42, NULL, 0),
(46, 'Chăm Sóc Mắt', 25, NULL, 0),
(47, 'Dưỡng Mi', 46, NULL, 0),
(48, 'Mặt Nạ Mắt', 46, NULL, 0),
(49, 'Kem Mắt', 46, NULL, 0),
(50, 'Chăm Sóc Cơ Thể', 0, NULL, 0),
(51, 'Chăm Sóc Răng Miệng', 50, NULL, 0),
(52, 'Dưỡng Thể', 50, NULL, 0),
(53, 'Body Mist - Xịt Thơm', 50, NULL, 0),
(54, 'Kem Trị Rạn / Tan Mỡ', 50, NULL, 0),
(55, 'Kem Tay', 50, NULL, 0),
(56, 'Lăn Xịt Khử Mùi', 50, NULL, 0),
(57, 'Chăm Sóc Vùng Kín', 50, NULL, 0),
(58, 'Dung Dịch Vệ Sinh', 57, NULL, 0),
(59, 'Nước Hoa Vùng Kín', 57, NULL, 0),
(60, 'Băng Vệ Sinh', 57, NULL, 0),
(61, 'Làm Sạch', 50, NULL, 0),
(62, 'Tẩy Da Chết Body', 61, NULL, 0),
(63, 'Sữa Tắm', 61, NULL, 0),
(64, 'Xà Phòng', 61, NULL, 0),
(65, 'Tẩy Lông', 61, NULL, 0),
(66, 'Nước Rửa Tay', 61, NULL, 0),
(67, 'Chăm Sóc Tóc', 0, NULL, 0),
(68, 'Dầu Gội Khô', 67, NULL, 0),
(69, 'Gôm Sáp', 67, NULL, 0),
(70, 'Dầu Gội / Dầu Xả', 67, NULL, 0),
(71, 'Nhuộm Tóc', 67, NULL, 0),
(72, 'Dưỡng / Ủ Tóc', 67, NULL, 0),
(73, 'Tẩy Tế Bào Chết Da Đầu', 72, NULL, 0),
(74, 'Dụng Cụ', 0, NULL, 0),
(75, 'Dụng Cụ Chăm Sóc Da', 74, NULL, 0),
(76, 'Dụng Cụ Làm Tóc', 74, NULL, 0),
(77, 'Dụng Cụ Rửa Mặt', 74, NULL, 0),
(78, 'Dụng Cụ Khác', 74, NULL, 0),
(79, 'Dụng Cụ Trang Điểm', 74, NULL, 0),
(80, 'Mút Trang Điểm', 79, NULL, 0),
(81, 'Cọ Trang Điểm', 79, NULL, 0),
(82, 'Kẹp Mi', 79, NULL, 0),
(83, 'Mi Giả', 79, NULL, 0),
(84, 'Kích Mí', 79, NULL, 0),
(85, 'Dao Cạo', 79, NULL, 0),
(86, 'Bông Tẩy Trang', 79, NULL, 0),
(87, 'Bộ Chiết Mĩ Phẩm', 79, NULL, 0),
(88, 'Giấy Thấm Dầu', 79, NULL, 0),
(89, 'Nước Hoa', 0, NULL, 0),
(90, 'Nước Hoa Nam', 89, NULL, 0),
(91, 'Nước Hoa Nữ', 89, NULL, 0),
(92, 'Mỹ Phẩm High-End', 0, NULL, 0),
(93, 'Chăm Sóc Cơ Thể Cao Cấp', 92, NULL, 0),
(94, 'Chăm Sóc Tóc Cao Cấp', 92, NULL, 0),
(95, 'Chăm Sóc Mặt Cao Cấp', 92, NULL, 0),
(96, 'Trang Điểm Cao Cấp', 92, NULL, 0),
(97, 'Thực Phẩm Chức Năng', NULL, NULL, 0),
(98, 'Sản Phẩm Khác', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `link` varchar(255) DEFAULT '#',
  `is_flash` tinyint(1) DEFAULT 0,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `menus`
--

INSERT INTO `menus` (`id`, `name`, `link`, `is_flash`, `sort_order`) VALUES
(1, 'KHUYẾN MÃI', '#promo', 0, 1),
(2, 'FLASH SALE', '#flash', 1, 2),
(3, 'GÓC REVIEW', '#review', 0, 3),
(4, 'HỆ THỐNG CỬA HÀNG', '#store', 0, 4),
(5, 'LIÊN HỆ', '#contact', 0, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `total_money` decimal(10,0) NOT NULL DEFAULT 0,
  `status` varchar(50) DEFAULT 'Đang xử lý',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `full_name`, `phone`, `address`, `total_money`, `status`, `created_at`) VALUES
(1, 1, 'Nguyen Van A', '0988888888', 'Ha Noi', 550000, 'Đang giao', '2025-12-09 16:28:32'),
(2, 1, 'Nguyen Van A', '0988888888', 'Ha Noi', 1200000, 'Hoàn thành', '2025-12-04 16:28:32'),
(3, 1, 'Nguyen Van A', '0988888888', 'Ha Noi', 320000, 'Đã hủy', '2025-11-29 16:28:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `old_price` decimal(15,2) DEFAULT 0.00,
  `stock_quantity` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` enum('published','draft','out_of_stock') DEFAULT 'draft',
  `ingredients` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_hot` tinyint(1) DEFAULT 0 COMMENT '1: Hot, 0: Bình thường'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `brand_id`, `category_id`, `name`, `sku`, `price`, `old_price`, `stock_quantity`, `image`, `status`, `ingredients`, `created_at`, `updated_at`, `is_hot`) VALUES
(2, 1, NULL, 'Nước Tẩy Trang L\'Oreal Micellar Water Làm Sạch Sâu Trang Điểm 400ml', '6902395498919', 229000.00, 150000.00, 150, NULL, 'published', 'Nước Tẩy Trang L\'Oreal Micellar Water 400ml Làm Mát Da Cho Da Dầu/Da Hỗn Hợp:\r\n\r\nAqua / Water, Hexylene Glycol, Glycerin, Poloxamer 184, Disodium Cocoamphodiacetate, Disodium Edta, BHT , Polyaminopropyl Biguanide.\r\n\r\nTrong đó:\r\n\r\nHexylene Glycol: chất nhũ hóa và hoạt động bề mặt, giúp làm sạch sâu và dưỡng da.\r\nGlycerin: hydrat hóa lớp sừng của da, tạo hàng rào bảo vệ da và các đặc tính cơ học của da, chống lại các tác nhân gây hại cho da, thúc đẩy quá trình phục hồi của da, dưỡng ẩm và cân bằng độ ẩm, cho da mịn màng.\r\nPoloxamer 184: nhũ hóa và làm sạch dịu nhẹ.\r\nNước Tẩy Trang L\'Oreal Micellar Water 400ml Dưỡng Ẩm Cho Da Thường/Da Khô:\r\n\r\nAqua / Water, Hexylene Glycol, Glycerin, Rosa Gallica Flower Extract, Sorbitol, Poloxamer 184, Disodium Cocoamphodiacetate, Disodium Edta, Propylene Glycol, BHT , Polyaminopropyl Biguanide.\r\n\r\nTrong đó:\r\n\r\nHexylene Glycol: chất nhũ hóa và hoạt động bề mặt, giúp làm sạch sâu và dưỡng da.\r\nGlycerin: hydrat hóa lớp sừng của da, tạo hàng rào bảo vệ da và các đặc tính cơ học của da, chống lại các tác nhân gây hại cho da, thúc đẩy quá trình phục hồi của da, dưỡng ẩm và cân bằng độ ẩm, cho da mịn màng.\r\nRosa Gallica Flower Extract: chiết xuất hoa hồng Pháp, giúp giảm viêm, làm se khít lỗ chân lông, dưỡng ẩm, làm mềm da, chống lại các gốc tự do gây hại cho da và thúc đẩy quá trình chữa lành da.\r\nNước Tẩy Trang L\'Oreal Micellar Water 400ml Làm Sạch Sâu Trang Điểm:\r\n\r\nAqua / Water, Cyclopentasiloxane, Isohexadecane, Potassium Phosphate, Sodium Chloride, Dipotassium Phosphate, Disodium Edta, Decyl Glucoside, Hexylene Glycol, Polyaminopropyl Biguanide, CI 61565 / Green 6.\r\n\r\nHexylene Glycol: chất nhũ hóa và hoạt động bề mặt, giúp làm sạch sâu và dưỡng da.\r\nCyclopentasiloxane: cải thiện kết cấu sản phẩm, dưỡng ẩm và tạo hàng rào bảo vệ da.\r\nIsohexadecane: cải thiện cảm giác của sản phẩm, làm mềm và mịn da.', '2025-11-20 16:56:33', '2025-11-21 05:07:46', 1),
(4, 7, 30, 'Tẩy Tế Bào Chết Paula\'s Choice 2% BHA 30ml', 'MP251205831', 299000.00, 399000.00, 10, '', 'published', 'Water (Aqua), Methylpropanediol (hydration), Butylene Glycol (hydration), Salicylic Acid (beta hydroxy acid/exfoliant), Polysorbate 20 (stabilizer), Camellia Oleifera Leaf Extract (green tea/skin calming/antioxidant), Sodium Hydroxide (pH balancer), Tetrasodium EDTA (stabilizer).\r\n\r\nSalicylic Acid: Giúp làm sạch nhẹ nhàng tế bào chết trên da, làm mờ các vết thâm mụn, nám da, dưỡng sáng da đều màu, mịn màng hơn. Bên cạnh đó, thành phần này còn có khả năng kháng khuẩn, làm khô thoáng lỗ chân lông, cải thiện tình trạng mụn, giảm sưng, kháng viêm, ngăn ngừa sự hình thành mụn, ngăn ngừa các nguy cơ gây lão hoá da. \r\nMethylpropanediol và Butylene Glycol: Cấp nước, đưa nước vào trong các tế bào da và giữ nước hiệu quả, ngăn ngừa lão hoá da, duy trì làn da mềm mại, ẩm mượt, không gây khô da, hạn chế kích ứng sau quá trình tẩy da chết, đồng thời chống oxy hóa, ngăn ngừa lão hoá hiệu quả. \r\nChiết xuất Trà Xanh: Làm dịu da, kháng khuẩn, giảm mẩn đỏ, làm se các nhân mụn, làm mờ các vết thâm,  dưỡng da sáng mịn đều màu.', '2025-12-05 15:50:34', '2025-12-05 16:04:48', 1),
(5, 8, 31, 'Kem Dưỡng Eucerin ProAcne Solution A.I Matt Fluid Giảm Mụn 50ml', 'MP251205508', 493000.00, 519000.00, 10, NULL, 'published', 'Là cái tên quen thuộc trong lĩnh vực dược mỹ phẩm, Eucerin được nhiều khách hàng biết đến qua những sản phẩm dưỡng da như sữa rửa mặt Eucerin pH5 Facial Cleanser, Nước hoa hồng Eucerin Pro Acne Solution Toner, Kem chống nắng dành cho da dầu mụn Eucerin Sun Gel Creme Oil Control SPF50+.... Các sản phẩm đều phải trải qua quy trình nghiên cứu ở phòng thí nghiệm cũng như thử nghiệm lâm sàng để đảm bảo rằng có thể thích ứng được với hầu hết mọi loại da cũng như giải quyết được mọi vấn đề về da. \r\n\r\nVới những nghiên cứu khoa học thành công của mình về sản phẩm, Eucerin ngày càng khẳng định được vị thế của mình và có mặt trên 60 quốc gia tại nhiều nước khác nhau, được hàng trăm nghìn bác sĩ da liễu khuyên dùng. ', '2025-12-05 15:57:09', '2025-12-05 16:04:14', 0),
(6, 9, 2, 'Kem Chống Nắng Cell Fusion C Toning Sunscreen 100 Nâng Tông 50ml - Hồng', 'MP251205128', 266000.00, 295000.00, 100, NULL, 'published', 'Kem Chống Nắng Cell Fusion C Toning Sunscreen 100 Nâng Tông 50ml là sản phẩm đình đám đến từ thương hiệu chống nắng Hàn Quốc Cell Fusion C, nổi bật với khả năng chống nắng mạnh mẽ và đem lại hiệu ứng nâng tông tự nhiên, không để lại vệt trắng trên da.', '2025-12-05 16:03:31', '2025-12-05 16:03:31', 1);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_content_blocks`
--

CREATE TABLE `product_content_blocks` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `section_type` enum('use','usage','description','review','feedback') NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `content_text` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_content_blocks`
--

INSERT INTO `product_content_blocks` (`id`, `product_id`, `section_type`, `image_url`, `content_text`, `sort_order`) VALUES
(1, 2, 'use', NULL, 'Công dụng\r\nLàm sạch da dịu nhẹ.\r\nCân bằng độ ẩm và dưỡng ẩm cho da, cho da căng bóng, mềm mịn.\r\nCủng cố hàng rào bảo vệ da, cho da khỏe mạnh.\r\nMỗi màu phù hợp với mỗi loại da khác nhau.', 0),
(2, 2, 'usage', 'uploads/img_691f4841b821f9.48605096.jpg', 'Bước 1: Lắc đều trước khi sử dụng.\r\n\r\nBước 2: Thấm một lượng vừa đủ sản phẩm Nước Tẩy Trang L\'Oreal Micellar Water 400ml vào bông tẩy trang rồi nhẹ nhàng lau đều lên khuôn mặt theo chuyển động tròn.\r\n\r\nBước 3: Không cần rửa lại với nước.\r\n\r\n* Lưu ý:\r\n\r\nSử dụng sản phẩm mỗi ngày (trước khi skincare) để đạt được hiệu quả tối ưu nhất.\r\n\r\nKhi xảy ra các hiện tượng kích ứng, dị ứng, mẩn đỏ,... cần tạm ngưng sử dụng sản phẩm và tham khảo ý kiến của các chuyên gia trước khi quyết định dùng lại.\r\nĐóng nắp sau khi sử dụng.\r\nBảo quản nơi khô ráo, thoáng mát.\r\nTránh tiếp xúc trực tiếp với ánh nắng mặt trời.\r\nĐể xa tầm tay trẻ em, không sử dụng sản phẩm cho trẻ em dưới 3 tuổi.\r\nHạn sử dụng: 3 năm kể từ ngày sản xuất, 6 tháng kể từ khi mở nắp.\r\nNgày sản xuất: Xem trên bao bì sản phẩm.', 0),
(3, 2, 'description', 'uploads/img_691f4841b86077.24733145.jpg', 'Mô tả sản phẩm\r\nL\'Oréal Paris Micellar Water 3-in-1 Refreshing (xanh dương nhạt) là dòng nước tẩy trang được thiết kế chuyên biệt cho da dầu, da hỗn hợp và cả làn da nhạy cảm. Sản phẩm ứng dụng công nghệ Mixen hiện đại, giúp làm sạch hiệu quả lớp trang điểm và bụi bẩn mà vẫn giữ được độ ẩm tự nhiên trên da, mang lại cảm giác mát dịu, không khô căng sau khi sử dụng.\r\n\r\nCông thức lành tính, không chứa dầu, hương liệu, ethanol hay paraben, giúp làm sạch mà không gây kích ứng. Đặc biệt, sản phẩm còn chứa nước khoáng tinh khiết từ những ngọn núi tại Pháp, giúp làm dịu da, mang lại cảm giác mát lành và thư giãn sau mỗi lần sử dụng.\r\n\r\nChất nước trong, mát lạnh thấm nhanh vào da, cuốn trôi bụi bẩn và lớp trang điểm mà không để lại cảm giác nhờn rít.\r\n\r\nNgoài Cocolux vẫn còn các dòng tẩy trang Loreal \r\n\r\nNước Tẩy Trang L\'Oreal Micellar Water 400ml Dưỡng Ẩm Cho Da Thường/Da Khô (Hồng)\r\n\r\nNước Tẩy Trang L\'Oreal Micellar Water 3-in-1 Moisturizing Even For Sensitive Skin giúp làm sạch bụi bẩn và lớp trang điểm. Với thành phần chính được chiết xuất từ hoa hồng Pháp, giúp loại bỏ các tác nhân gây hại ra khỏi da, đồng thời dưỡng ẩm và dưỡng da từ sâu bên trong. Sản phẩm thích hợp dùng cho da thường và da khô.', 0),
(4, 2, 'description', 'uploads/img_691f4841b8caf6.72349252.jpg', 'Mô tả sản phẩm\r\nL\'Oréal Paris Micellar Water 3-in-1 Refreshing (xanh dương nhạt) là dòng nước tẩy trang được thiết kế chuyên biệt cho da dầu, da hỗn hợp và cả làn da nhạy cảm. Sản phẩm ứng dụng công nghệ Mixen hiện đại, giúp làm sạch hiệu quả lớp trang điểm và bụi bẩn mà vẫn giữ được độ ẩm tự nhiên trên da, mang lại cảm giác mát dịu, không khô căng sau khi sử dụng.\r\n\r\nCông thức lành tính, không chứa dầu, hương liệu, ethanol hay paraben, giúp làm sạch mà không gây kích ứng. Đặc biệt, sản phẩm còn chứa nước khoáng tinh khiết từ những ngọn núi tại Pháp, giúp làm dịu da, mang lại cảm giác mát lành và thư giãn sau mỗi lần sử dụng.\r\n\r\nChất nước trong, mát lạnh thấm nhanh vào da, cuốn trôi bụi bẩn và lớp trang điểm mà không để lại cảm giác nhờn rít.\r\n\r\nNgoài Cocolux vẫn còn các dòng tẩy trang Loreal \r\n\r\nNước Tẩy Trang L\'Oreal Micellar Water 400ml Dưỡng Ẩm Cho Da Thường/Da Khô (Hồng)\r\n\r\nNước Tẩy Trang L\'Oreal Micellar Water 3-in-1 Moisturizing Even For Sensitive Skin giúp làm sạch bụi bẩn và lớp trang điểm. Với thành phần chính được chiết xuất từ hoa hồng Pháp, giúp loại bỏ các tác nhân gây hại ra khỏi da, đồng thời dưỡng ẩm và dưỡng da từ sâu bên trong. Sản phẩm thích hợp dùng cho da thường và da khô.', 0),
(5, 2, 'description', 'uploads/img_691f4841b8e5e7.18224848.jpg', 'Nước Tẩy Trang Loreal Revitalift Hyaluronic Acid Micellar Water Căng Mịn Da 400ml (Màu tím)\r\n\r\nNước Tẩy Trang L\'Oreal Paris Revitalift Hyaluronic Acid Hydrating Micellar Water sử dụng công nghệ Micellar để làm sạch da một cách hiệu quả, loại bỏ hoàn toàn bụi bẩn, bã nhờn, tế bào chết và lớp trang điểm. Đồng thời, thành phần Hyaluronic Acid giúp dưỡng ẩm sâu, mang lại làn da mềm mịn và căng bóng từ bên trong. Ngay sau khi sử dụng, da trở nên sạch mát, tươi mới và mềm mại, mà không hề gây cảm giác khô căng. Sản phẩm thích hợp với da khô và da hỗn hợp.', 0),
(6, 2, 'review', 'uploads/img_691f4841b91260.57626083.jpg', 'Công dụng\r\nLàm sạch da dịu nhẹ.\r\nCân bằng độ ẩm và dưỡng ẩm cho da, cho da căng bóng, mềm mịn.\r\nCủng cố hàng rào bảo vệ da, cho da khỏe mạnh.\r\nMỗi màu phù hợp với mỗi loại da khác nhau.', 0),
(7, 4, 'use', 'uploads/img_6932ff4aad1fa0.75178555.png', 'Dung Dịch Loại Bỏ Tế Bào Chết 2% BHA Paula\'s Choice có công dụng: \r\n\r\nLoại bỏ tế bào chết hiệu quả: Tẩy tế bào chết nhẹ nhàng, làm sạch sâu lỗ chân lông, giúp da khô thoáng và mịn màng.\r\nDưỡng ẩm và duy trì độ ẩm: Cung cấp và giữ ẩm cho da, ngăn ngừa tình trạng khô căng hoặc bong tróc sau khi tẩy tế bào chết, giữ cho làn da luôn mềm mại.\r\nKháng viêm và kháng khuẩn: Có khả năng làm dịu da, giảm sưng viêm, kháng khuẩn hiệu quả, hỗ trợ giảm mụn và các vấn đề liên quan đến vi khuẩn.\r\nChống oxy hóa và ngăn ngừa lão hóa: Cung cấp các hoạt chất chống oxy hóa mạnh mẽ, ngăn ngừa dấu hiệu lão hóa, giúp da trông trẻ trung và khỏe mạnh hơn.\r\nLàm sáng da và cải thiện màu da: Làm mờ vết thâm, nám, đốm nâu, đồng thời giúp làm đều màu da và làm sáng da tự nhiên.\r\nSe khít lỗ chân lông: Hỗ trợ làm se lỗ chân lông, giảm tình trạng tích tụ bã nhờn và vi khuẩn, ngăn ngừa mụn hình thành.\r\nHỗ trợ tái tạo da: Kích thích quá trình tái tạo da, giúp cải thiện kết cấu da và làm mềm da.\r\nPhục hồi da sau tổn thương: Giúp phục hồi làn da sau tổn thương, giảm mẩn đỏ và tăng cường sức khỏe da.', 0),
(8, 4, 'usage', 'uploads/img_6932ff4aad5470.17741763.png', 'Cách dùng\r\nBước 1: Làm sạch mặt với nước tẩy trang và sữa rửa mặt.\r\nBước 2: Sử dụng toner để cân bằng da.\r\nBước 3: Thoa một lượng sản phẩm vừa đủ lên da đã làm khô. \r\nLưu ý: \r\n\r\nNếu sử dụng ban ngày, nên dùng thêm sản phẩm kem dưỡng và chống nắng có chỉ số SPF ít nhất 30.\r\nDùng 2 – 3 lần một tuần để đạt hiệu quả tốt nhất.\r\nNên dùng vào buổi tối để tránh tác hại từ ánh nắng mặt trời.', 0),
(9, 4, 'description', 'uploads/img_6932ff4aad8091.88248729.png', 'Dung Dịch Loại Bỏ Tế Bào Chết 2% BHA Paula\'s Choice được biết đến là sản phẩm loại bỏ tế bào chết hoá học hiệu quả bậc nhất hiện nay. Đây là một trong những best seller của thương hiệu Paula\'s Choice đình đám đến từ Mỹ. Nhờ công thức đột phá bổ sung lượng Salicylic Acid 2%, sản phẩm đem lại hiệu quả làm sạch sâu lớp da chết, giúp lỗ chân lông khô thoáng, đem lại làn da sạch mịn.', 0),
(10, 4, 'review', 'uploads/img_6932ff4aadfc91.88526036.png', 'Loại bỏ sạch sẽ lớp tế bào chết dưới da, giúp da khô thoáng. \r\nKích thích tái tạo da, giúp da trẻ hoá, ngăn ngừa lão hoá hiệu quả. \r\nLàm dịu da, không gây kích ứng, không bào mòn da, kháng viêm vượt trội. \r\nHỗ trợ se khít lỗ chân lông, ngăn ngừa sự tích tụ vi khuẩn gây mụn.\r\nPhục hồi da hiệu quả sau tổn thương, không gây mẩn đỏ.\r\nCấp ẩm hiệu quả, giữ nước tốt, tránh tình trạng khô da sau khi tẩy da chết. \r\nLàm mờ nếp nhăn, chống lão hoá da. \r\nDưỡng da sáng mịn đều màu, giảm thâm nám, đốm nâu. \r\nKết cấu dạng lỏng, không màu, thẩm thấu nhanh, không bít lỗ chân lông, không chứa chất bào mòn, không kích ứng, phù hợp với mọi loại da. ', 0),
(11, 5, 'use', 'uploads/img_693300d6005f83.79361888.png', 'Kem dưỡng Eucerin giảm mụn ProAcne Solution A.I Matt Fluid 50ml giúp giải quyết 5 vấn đề về da như Dầu thừa, vi khuẩn mụn, nốt mụn, thâm mụn và lỗ chân lông to. Cụ thể như sau:\r\n\r\nCung cấp độ ẩm cho da khiến da luôn mềm mịn, tự nhiên.\r\nGiúp giải quyết các tình trạng bã nhờn cũng như kiềm dầu cho da lên đến 8 tiếng, \r\nNgăn ngừa vi khuẩn có hại xâm nhập vào làn da từ đó hạn chế sự hình thành mụn. \r\nTác động vào những lớp tế bào chết gây bít tắc lỗ chân lông.\r\nGiúp làm giảm những vết thâm mụn, dưỡng da trắng sáng. \r\nCó khả năng làm se khít lỗ chân lông to, từ đó vi khuẩn, bụi bẩn không có cơ hội xâm nhập. ', 0),
(12, 5, 'usage', 'uploads/img_693300d600c664.67963675.png', 'Bạn sử dụng Kem dưỡng Eucerin giảm mụn ProAcne Solution A.I Matt Fluid 50ml giảm mụn và kiểm soát bã nhờn vào mỗi buổi sáng và tối với các bước thực hiện: Tẩy trang -> Sữa rửa mặt -> Toner -> Serum -> Kem dưỡng ẩm -> Kem chống nắng (Dùng cho buổi sáng).\r\n\r\nBước 1: Bấm nhẹ đầu kem dưỡng lấy một lượng kem vừa đủ ra mu bàn tay.\r\n\r\nBước 2: Chấm lên các vị trí của khuôn mặt rồi tán đều xung quanh để kem thẩm thấu vào da. \r\n\r\nBước 3: Thực hiện các bước dưỡng da/makeup tiếp theo (nếu có).', 0),
(13, 5, 'description', 'uploads/img_693300d6017185.13780106.png', 'Là cái tên quen thuộc trong lĩnh vực dược mỹ phẩm, Eucerin được nhiều khách hàng biết đến qua những sản phẩm dưỡng da như sữa rửa mặt Eucerin pH5 Facial Cleanser, Nước hoa hồng Eucerin Pro Acne Solution Toner, Kem chống nắng dành cho da dầu mụn Eucerin Sun Gel Creme Oil Control SPF50+.... Các sản phẩm đều phải trải qua quy trình nghiên cứu ở phòng thí nghiệm cũng như thử nghiệm lâm sàng để đảm bảo rằng có thể thích ứng được với hầu hết mọi loại da cũng như giải quyết được mọi vấn đề về da. \r\n\r\nVới những nghiên cứu khoa học thành công của mình về sản phẩm, Eucerin ngày càng khẳng định được vị thế của mình và có mặt trên 60 quốc gia tại nhiều nước khác nhau, được hàng trăm nghìn bác sĩ da liễu khuyên dùng. ', 0),
(14, 5, 'review', 'uploads/img_693300d6019c97.83908500.png', 'Kem dưỡng Eucerin giảm mụn ProAcne Solution A.I Matt Fluid 50ml sở hữu những ưu điểm vượt trội như: \r\n\r\nThiết kế nhỏ gọn, phần đầu bấm kem dưỡng tiện lợi và giúp bảo quản dễ dàng.\r\nNgoài cung cấp độ ẩm tối ưu cho da thì sản phẩm còn giúp kiểm soát bã nhờn bằng cách điều tiết bã nhờn ngăn ngừa tình trạng tắc nghẽn lỗ chân lông; loại bỏ bụi bẩn, tạp chất tích tụ lâu ngày trong lỗ chân lông giúp da thông thoáng, sạch khuẩn. \r\nBảng thành phần lành tính phù hợp với da dầu, da mụn, da nhạy cảm.\r\nGiúp làm giảm vết thâm mụn trong thời gian ngắn. \r\nChất kem có màu vàng nhẹ, thấm nhanh, dễ tán và không gây bết dính, nhờn rít.', 0),
(15, 6, 'use', 'uploads/img_69330253ae90c2.11314514.jpg', 'Kem Chống Nắng Cell Fusion C Toning Sunscreen 100 Nâng Tông 50ml là sản phẩm đình đám đến từ thương hiệu chống nắng Hàn Quốc Cell Fusion C, nổi bật với khả năng chống nắng mạnh mẽ và đem lại hiệu ứng nâng tông tự nhiên, không để lại vệt trắng trên da.', 0),
(16, 6, 'usage', 'uploads/img_69330253af2498.99438945.jpg', 'Kem Chống Nắng Cell Fusion C Toning Sunscreen 100 Nâng Tông 50ml là sản phẩm đình đám đến từ thương hiệu chống nắng Hàn Quốc Cell Fusion C, nổi bật với khả năng chống nắng mạnh mẽ và đem lại hiệu ứng nâng tông tự nhiên, không để lại vệt trắng trên da.', 0),
(17, 6, 'description', 'uploads/img_69330253af5531.19940646.jpg', 'Kem Chống Nắng Cell Fusion C Toning Sunscreen 100 Nâng Tông 50ml là sản phẩm đình đám đến từ thương hiệu chống nắng Hàn Quốc Cell Fusion C, nổi bật với khả năng chống nắng mạnh mẽ và đem lại hiệu ứng nâng tông tự nhiên, không để lại vệt trắng trên da.', 0),
(18, 6, 'review', 'uploads/img_69330253af77d0.14642284.jpg', 'Kem Chống Nắng Cell Fusion C Toning Sunscreen 100 Nâng Tông 50ml là sản phẩm đình đám đến từ thương hiệu chống nắng Hàn Quốc Cell Fusion C, nổi bật với khả năng chống nắng mạnh mẽ và đem lại hiệu ứng nâng tông tự nhiên, không để lại vệt trắng trên da.', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_gallery`
--

CREATE TABLE `product_gallery` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_gallery`
--

INSERT INTO `product_gallery` (`id`, `product_id`, `image_url`) VALUES
(1, 2, 'uploads/img_691f4841b69013.21286078.jpg'),
(2, 2, 'uploads/img_691f4841b73617.92528939.jpg'),
(3, 2, 'uploads/img_691f4841b75cc0.11443144.jpg'),
(4, 2, 'uploads/img_691f4841b7c584.78226723.jpg'),
(5, 2, 'uploads/img_691f4841b7e4d7.30455250.jpg'),
(6, 4, 'uploads/img_6932ff4aacdcf6.24655003.png'),
(7, 5, 'uploads/img_693300d5f3e040.10896363.png'),
(8, 6, 'uploads/img_69330253add352.68892005.jpg'),
(9, 6, 'uploads/img_69330253ae2368.88531633.jpg'),
(10, 4, 'uploads/img_693300d5f38508.26553083.png'),
(11, 5, 'uploads/img_6932ff4aac48a8.82652169.png');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_reviews`
--

CREATE TABLE `product_reviews` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `rating` int(11) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `comment_date` datetime DEFAULT current_timestamp(),
  `is_admin_seed` tinyint(1) DEFAULT 1,
  `status` enum('approved','pending','hidden') DEFAULT 'approved'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_specifications`
--

CREATE TABLE `product_specifications` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `spec_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_specifications`
--

INSERT INTO `product_specifications` (`id`, `product_id`, `spec_name`, `spec_value`) VALUES
(1, 2, 'Nơi sản xuất', 'Pháp'),
(2, 2, 'Thương hiệu', 'L\'ORÉAL'),
(3, 2, 'Đặc tính', 'Ngày Và Đêm'),
(4, 2, 'Xuất xứ thương hiệu', 'Pháp');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `phone`, `address`, `password`, `role`, `created_at`) VALUES
(1, 'Quản trị viên', 'admin@gmail.com', NULL, NULL, '$2y$10$wS1.0/./././././././././././././././././././././././.', 'admin', '2025-12-09 15:51:10'),
(2, 'Hugnf', 'hung@gmail.com', NULL, NULL, '$2y$10$g3si9tqk5Mk9RA51FA8Qku45ViY5av4okFwr0PXXGZ9f4LCnW7dAW', 'user', '2025-12-09 16:11:48'),
(3, 'hug1', 'hung1@gmail.com', NULL, NULL, '$2y$10$/Of/hudLhnk4Q0EwEpZV1eHNqr3VmkCDdBma3UlhLNKVqISbqor3C', 'user', '2025-12-09 16:14:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL COMMENT 'Mã giảm giá (VD: FREE75K)',
  `discount_amount` decimal(10,2) NOT NULL COMMENT 'Giá trị giảm (VD: 75000.00)',
  `discount_text` varchar(50) NOT NULL COMMENT 'Văn bản giảm giá (VD: Giảm 75.000đ)',
  `min_order_amount` decimal(10,2) NOT NULL COMMENT 'Giá trị đơn hàng tối thiểu để áp dụng (VD: 799000.00)',
  `condition_text` varchar(255) NOT NULL COMMENT 'Mô tả điều kiện (VD: Website đơn từ 799K...)',
  `quantity` int(10) UNSIGNED DEFAULT 0 COMMENT 'Số lượng mã được phát hành',
  `used_count` int(10) UNSIGNED DEFAULT 0 COMMENT 'Số lượng mã đã được sử dụng',
  `start_date` date NOT NULL COMMENT 'Ngày bắt đầu hiệu lực',
  `end_date` date NOT NULL COMMENT 'Ngày hết hạn',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Trạng thái hoạt động (1: Hoạt động, 0: Ngưng)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `discount_amount`, `discount_text`, `min_order_amount`, `condition_text`, `quantity`, `used_count`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'DISC75K', 75000.00, 'Giảm 75.000đ', 799000.00, 'Website đơn từ 799K, áp dụng với một số sản phẩm nhất định', 1000, 90, '2025-11-20', '2025-12-20', 1, '2025-11-20 15:00:06', '2025-11-20 15:00:06'),
(2, 'DISC55K', 55000.00, 'Giảm 55.000đ', 599000.00, 'Website đơn từ 599K, áp dụng với một số sản phẩm nhất định', 800, 480, '2025-11-20', '2025-12-20', 1, '2025-11-20 15:00:06', '2025-11-20 15:00:06'),
(3, 'DISC35K', 35000.00, 'Giảm 35.000đ', 399000.00, 'Website đơn từ 399K, áp dụng với một số sản phẩm nhất định', 1500, 675, '2025-11-20', '2025-12-20', 1, '2025-11-20 15:00:06', '2025-11-20 15:00:06'),
(4, 'DISC25K', 25000.00, 'Giảm 25.000đ', 299000.00, 'Website đơn từ 299K, áp dụng với một số sản phẩm nhất định', 2000, 1400, '2025-11-20', '2025-12-20', 1, '2025-11-20 15:00:06', '2025-11-20 15:00:06');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sku` (`sku`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `product_content_blocks`
--
ALTER TABLE `product_content_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_end_date` (`end_date`),
  ADD KEY `idx_active` (`is_active`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=99;

--
-- AUTO_INCREMENT cho bảng `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `product_content_blocks`
--
ALTER TABLE `product_content_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `product_specifications`
--
ALTER TABLE `product_specifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `product_content_blocks`
--
ALTER TABLE `product_content_blocks`
  ADD CONSTRAINT `product_content_blocks_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_gallery`
--
ALTER TABLE `product_gallery`
  ADD CONSTRAINT `product_gallery_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `product_reviews_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_specifications`
--
ALTER TABLE `product_specifications`
  ADD CONSTRAINT `product_specifications_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
