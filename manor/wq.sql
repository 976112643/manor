-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2015-10-17 12:17:19
-- 服务器版本： 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `wq`
--

-- --------------------------------------------------------

--
-- 表的结构 `upload_files`
--

CREATE TABLE IF NOT EXISTS `upload_files` (
  `name` varchar(255) DEFAULT NULL,
  `localpath` varchar(255) DEFAULT NULL,
  `type` varchar(168) NOT NULL,
  `md5_value` varchar(32) DEFAULT NULL,
`id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL DEFAULT '0',
  `thumbnail` varchar(50) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `upload_files`
--

INSERT INTO `upload_files` (`name`, `localpath`, `type`, `md5_value`, `id`, `user_id`, `thumbnail`) VALUES
('ludashi.jpg', '1_29a811a08bd3fbba53706370f6b160c7.jpg', 'jpg', 'a50528448ff6492425308ec0a9a98297', 13, 1, '1_29a811a08bd3fbba53706370f6b160c7.jpg'),
('index10-8.psd', '1_406bc3a777ea0c099df68b3ff01bc71d.psd', 'psd', '1c0ff35ed64ab8f5174d49b758ebb8fb', 14, 1, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` bigint(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`user_id`) VALUES
(0),
(1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `upload_files`
--
ALTER TABLE `upload_files`
 ADD PRIMARY KEY (`id`), ADD KEY `id` (`id`), ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `upload_files`
--
ALTER TABLE `upload_files`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=15;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` bigint(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- 限制导出的表
--

--
-- 限制表 `upload_files`
--
ALTER TABLE `upload_files`
ADD CONSTRAINT `upload_files_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
