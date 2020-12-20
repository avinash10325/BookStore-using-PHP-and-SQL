-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 28, 2020 at 04:00 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bookstore`
--

-- --------------------------------------------------------

--
-- Table structure for table `addressbook`
--

CREATE TABLE `addressbook` (
  `id` int(1) UNSIGNED NOT NULL,
  `userID` int(1) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `addedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `addressbook`
--

INSERT INTO `addressbook` (`id`, `userID`, `address`, `addedAt`) VALUES
(1, 2, 'Fake addres one', '0000-00-00 00:00:00'),
(2, 1, 'Fake address', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `id` int(1) UNSIGNED NOT NULL,
  `booksGenreID` int(1) UNSIGNED NOT NULL,
  `bookThumbnail` text NOT NULL DEFAULT 'thumb.jpg',
  `bookName` char(150) NOT NULL,
  `bookAuthor` char(65) NOT NULL,
  `bookPublishedOn` date DEFAULT NULL,
  `bookPrice` decimal(15,2) NOT NULL DEFAULT 0.00,
  `bookDescription` text NOT NULL,
  `bookStockCount` int(1) UNSIGNED NOT NULL,
  `bookAddedAt` datetime NOT NULL,
  `bookAddedBy` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`id`, `booksGenreID`, `bookThumbnail`, `bookName`, `bookAuthor`, `bookPublishedOn`, `bookPrice`, `bookDescription`, `bookStockCount`, `bookAddedAt`, `bookAddedBy`) VALUES
(1, 1, 'thumb.jpg', 'Demo', 'Demo Author', '2020-11-27', '150.00', 'Demo', 99, '2020-11-27 19:53:55', 1);

-- --------------------------------------------------------

--
-- Table structure for table `booksgenre`
--

CREATE TABLE `booksgenre` (
  `id` int(1) UNSIGNED NOT NULL,
  `genreName` char(65) NOT NULL,
  `genreDescription` text NOT NULL,
  `genreAddedAt` datetime DEFAULT NULL,
  `genreAddedBy` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booksgenre`
--

INSERT INTO `booksgenre` (`id`, `genreName`, `genreDescription`, `genreAddedAt`, `genreAddedBy`) VALUES
(1, 'Action', 'Demo', NULL, 1),
(4, 'Romance', 'Demo', '2020-11-28 15:58:49', 1),
(5, 'Thriller', 'Demo', '2020-11-28 15:58:58', 1),
(6, 'Sci-Fi', 'Demo', '2020-11-28 15:59:28', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bookspurchased`
--

CREATE TABLE `bookspurchased` (
  `id` int(1) UNSIGNED NOT NULL,
  `bookID` int(1) UNSIGNED NOT NULL,
  `addressID` int(1) UNSIGNED NOT NULL,
  `bookPurchasedBy` int(1) UNSIGNED NOT NULL,
  `bookPurchasedOn` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `booksrating`
--

CREATE TABLE `booksrating` (
  `id` int(1) UNSIGNED NOT NULL,
  `bookID` int(1) UNSIGNED NOT NULL,
  `rating` tinyint(5) NOT NULL DEFAULT 1,
  `comment` text NOT NULL,
  `ratedBy` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `booksrating`
--

INSERT INTO `booksrating` (`id`, `bookID`, `rating`, `comment`, `ratedBy`) VALUES
(3, 1, 5, 'demo', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(1) UNSIGNED NOT NULL,
  `fullName` char(65) NOT NULL,
  `username` char(25) NOT NULL,
  `password` char(65) NOT NULL,
  `userRole` enum('admin','user') NOT NULL DEFAULT 'user',
  `accountCreatedAt` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullName`, `username`, `password`, `userRole`, `accountCreatedAt`) VALUES
(1, 'Admin', 'admin', 'admin', 'admin', '2020-11-27 20:17:40'),
(2, 'Demo Account', 'demo', '1234', 'user', '2020-11-27 20:21:01'),
(8, 'Demo Account', 'demo2', 'demo', 'user', '2020-11-27 20:22:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addressbook`
--
ALTER TABLE `addressbook`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booksGenreID` (`booksGenreID`),
  ADD KEY `bookName` (`bookName`),
  ADD KEY `bookStockCount` (`bookStockCount`),
  ADD KEY `bookAddedBy` (`bookAddedBy`),
  ADD KEY `bookThumbnail` (`bookThumbnail`(768));

--
-- Indexes for table `booksgenre`
--
ALTER TABLE `booksgenre`
  ADD PRIMARY KEY (`id`),
  ADD KEY `genreName` (`genreName`),
  ADD KEY `genreAddedBy` (`genreAddedBy`);

--
-- Indexes for table `bookspurchased`
--
ALTER TABLE `bookspurchased`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookID` (`bookID`),
  ADD KEY `bookPurchasedBy` (`bookPurchasedBy`),
  ADD KEY `addressID` (`addressID`);

--
-- Indexes for table `booksrating`
--
ALTER TABLE `booksrating`
  ADD PRIMARY KEY (`id`),
  ADD KEY `bookID` (`bookID`),
  ADD KEY `ratedBy` (`ratedBy`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `userRole` (`userRole`),
  ADD KEY `password` (`password`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addressbook`
--
ALTER TABLE `addressbook`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `booksgenre`
--
ALTER TABLE `booksgenre`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `bookspurchased`
--
ALTER TABLE `bookspurchased`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booksrating`
--
ALTER TABLE `booksrating`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(1) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addressbook`
--
ALTER TABLE `addressbook`
  ADD CONSTRAINT `userID_address` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `bookAddedBy` FOREIGN KEY (`bookAddedBy`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookGenreID` FOREIGN KEY (`booksGenreID`) REFERENCES `booksgenre` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Constraints for table `booksgenre`
--
ALTER TABLE `booksgenre`
  ADD CONSTRAINT `genreAddedBy` FOREIGN KEY (`genreAddedBy`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bookspurchased`
--
ALTER TABLE `bookspurchased`
  ADD CONSTRAINT `addressID_purchased` FOREIGN KEY (`addressID`) REFERENCES `addressbook` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookID_purchased` FOREIGN KEY (`bookID`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userID_purchased` FOREIGN KEY (`bookPurchasedBy`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booksrating`
--
ALTER TABLE `booksrating`
  ADD CONSTRAINT `bookID_rating` FOREIGN KEY (`bookID`) REFERENCES `books` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userID_rating` FOREIGN KEY (`ratedBy`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
