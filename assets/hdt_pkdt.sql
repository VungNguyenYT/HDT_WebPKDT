-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 12, 2024 lúc 07:11 AM
-- Phiên bản máy phục vụ: 10.4.27-MariaDB
-- Phiên bản PHP: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `hdt_pkdt`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `cart`
--

INSERT INTO `cart` (`CartID`, `UserID`, `ProductID`, `Quantity`) VALUES
(1, 3, 1, 2),
(2, 3, 3, 1),
(3, 4, 2, 1),
(4, 4, 5, 2),
(5, 5, 4, 3);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `CategoryID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`CategoryID`, `CategoryName`) VALUES
(5, 'Cáp Sạc'),
(4, 'Kính Cường Lực'),
(1, 'Ốp Lưng'),
(2, 'Sạc Điện Thoại'),
(3, 'Tai Nghe');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orderdetails`
--

CREATE TABLE `orderdetails` (
  `OrderDetailID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `ProductID` int(11) DEFAULT NULL,
  `Quantity` int(11) NOT NULL,
  `Price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orderdetails`
--

INSERT INTO `orderdetails` (`OrderDetailID`, `OrderID`, `ProductID`, `Quantity`, `Price`) VALUES
(1, 1, 1, 2, '120000.00'),
(2, 1, 3, 1, '950000.00'),
(3, 2, 2, 1, '550000.00'),
(4, 2, 5, 2, '150000.00'),
(5, 3, 4, 3, '70000.00');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(11) NOT NULL,
  `UserID` int(11) DEFAULT NULL,
  `OrderDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `TotalAmount` decimal(10,2) NOT NULL,
  `Status` enum('Pending','Completed','Cancelled') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`OrderID`, `UserID`, `OrderDate`, `TotalAmount`, `Status`) VALUES
(1, 3, '2024-12-04 16:26:16', '670000.00', 'Pending'),
(2, 4, '2024-12-04 16:26:16', '850000.00', 'Completed'),
(3, 5, '2024-12-04 16:26:16', '210000.00', 'Pending');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payments`
--

CREATE TABLE `payments` (
  `PaymentID` int(11) NOT NULL,
  `OrderID` int(11) DEFAULT NULL,
  `PaymentMethod` enum('COD','Bank Transfer') NOT NULL,
  `PaymentDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payments`
--

INSERT INTO `payments` (`PaymentID`, `OrderID`, `PaymentMethod`, `PaymentDate`) VALUES
(1, 1, 'COD', '2024-12-04 16:26:42'),
(2, 2, '', '2024-12-04 16:26:42'),
(3, 3, 'COD', '2024-12-04 16:26:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(100) NOT NULL,
  `Description` text DEFAULT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Stock` int(11) DEFAULT 0,
  `ImageURL` varchar(255) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `Description`, `Price`, `Stock`, `ImageURL`, `CategoryID`) VALUES
(1, 'Ốp Lưng Silicone', 'Ốp lưng cao cấp chất liệu silicone', '25000.00', 50, 'oplung-silicon.jpg', 1),
(2, 'Sạc Không Dây', 'Đế sạc nhanh không dây', '1090000.00', 30, 'skd-magpro.jpg', 2),
(3, 'Tai Nghe Bluetooth', 'Tai nghe không dây, chống ồn', '370000.00', 20, 'tainghebt-baseuswm02.jpg', 3),
(4, 'Kính Cường Lực', 'Kính cường lực chất lượng cao', '100000.00', 100, 'kinhcuongluc.jpg', 4),
(5, 'Cáp Sạc USB-C', 'Cáp sạc nhanh dài 1m', '40000.00', 70, 'capsacsamsung.jpg', 5),
(6, 'Ốp Lưng Nhựa Trong', 'Ốp lưng trong suốt chống sốc', '20000.00', 40, 'oplung-trong.jpg', 1),
(7, 'Sạc Dự Phòng 10000mAh', 'Sạc dự phòng nhỏ gọn', '220000.00', 25, 'sacdpava.jpg', 2),
(8, 'Tai Nghe Có Dây', 'Tai nghe có dây chất lượng cao', '240000.00', 35, 'taingheday.jpg', 3),
(9, 'Cáp Lightning', 'Cáp Lightning chính hãng', '550000.00', 50, 'capsaclightning.jpeg', 5),
(10, 'Kính Bảo Vệ Camera', 'Kính cường lực bảo vệ camera', '60000.00', 120, 'kinhcameraip12.jpg', 4);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `Role` enum('Admin','Employee','Customer') NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `PhoneNumber` varchar(15) DEFAULT NULL,
  `Address` mediumtext DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`UserID`, `Username`, `Password`, `PasswordHash`, `Role`, `FullName`, `Email`, `Phone`, `PhoneNumber`, `Address`, `CreatedAt`) VALUES
(1, 'admin', '', 'hash123', 'Admin', 'Quản Trị Viên', 'admin@example.com', NULL, '0123456789', 'Văn phòng chính', '2024-12-04 16:23:00'),
(2, 'nhanvien1', '', 'hash234', 'Employee', 'Nguyễn Văn An', 'nhanvien1@example.com', NULL, '0987654321', 'Chi nhánh Hà Nội', '2024-12-04 16:23:00'),
(3, 'khachhang1', '', 'hash345', 'Customer', 'Trần Thị Bích Ngọc', 'khachhang1@example.com', NULL, '0912345678', '123 Đường Lê Lợi, Quận 1, TP.HCM', '2024-12-04 16:23:00'),
(4, 'khachhang2', '', 'hash456', 'Customer', 'Lê Minh Tuấn', 'khachhang2@example.com', NULL, '0923456789', '456 Đường Phan Chu Trinh, TP. Đà Nẵng', '2024-12-04 16:23:00'),
(5, 'khachhang3', '', 'hash567', 'Customer', 'Phạm Hồng Hạnh', 'khachhang3@example.com', NULL, '0934567890', '789 Đường Trần Hưng Đạo, Hà Nội', '2024-12-04 16:23:00'),
(6, 'VungNguyenYT', '$2y$10$6C4h0QPChG72UcdSa/Iq5eHfONUYNM..rg9qlXVH.V8hIg85CLcGu', '', 'Customer', 'Nguyễn Văn Vửng', 'nguyenvanvung252@gmail.com', '0347482012', NULL, 'Ấp Phú Lân, Xã Song Lộc, huyện Châu Thành, tỉnh Trà Vinh', '2024-12-05 11:39:18');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CategoryID`),
  ADD UNIQUE KEY `CategoryName` (`CategoryName`);

--
-- Chỉ mục cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD PRIMARY KEY (`OrderDetailID`),
  ADD KEY `OrderID` (`OrderID`),
  ADD KEY `ProductID` (`ProductID`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `UserID` (`UserID`);

--
-- Chỉ mục cho bảng `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`PaymentID`),
  ADD KEY `OrderID` (`OrderID`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `Username` (`Username`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  MODIFY `OrderDetailID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `payments`
--
ALTER TABLE `payments`
  MODIFY `PaymentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`);

--
-- Các ràng buộc cho bảng `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `orderdetails_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `orderdetails_ibfk_2` FOREIGN KEY (`ProductID`) REFERENCES `products` (`ProductID`);

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Các ràng buộc cho bảng `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`);

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `categories` (`CategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
