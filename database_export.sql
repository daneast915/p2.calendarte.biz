-- phpMyAdmin SQL Dump
-- version 4.0.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 03, 2013 at 01:14 AM
-- Server version: 5.5.33
-- PHP Version: 5.5.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `calendar_p2_calendarte_biz`
--

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`post_id`, `created`, `modified`, `user_id`, `content`) VALUES
(6, 1382802256, 1382802256, 15, 'Life here on the frontier is very hard.'),
(7, 1382802286, 1382802286, 15, 'I grew up in Pennsylvania, but now I live in Kentucky.'),
(8, 1382802353, 1382802353, 16, 'I''m a school teacher and I love working with the kids.'),
(9, 1382802392, 1382802392, 16, 'Last night I got a call from a parent at 9:20pm saying she had stepped on a school-owned cello and had broken it.'),
(12, 1383092733, 1383092733, 17, 'This is brien''s first post'),
(13, 1383092843, 1383092843, 17, 'This is Brien''s 2nd post'),
(15, 1383430127, 1383430127, 21, 'This is my first post'),
(16, 1383430139, 1383430139, 21, 'This is my 2nd post'),
(17, 1383430524, 1383430524, 22, 'My first post. ''With "strange" characters.''');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `modified` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `last_login` int(11) NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `created`, `modified`, `token`, `password`, `last_login`, `timezone`, `first_name`, `last_name`, `email`) VALUES
(15, 1382802113, 1382802113, 'ddb6827904a946e24b5c68f0d1fab67de12debf9', '09e08f67de901dca540b5c52035a484af07c9aac', 0, '', 'Daniel', 'Boone', 'danboone@fake.org'),
(16, 1382802322, 1382802322, '46df5412efcd551e1d7c7ccdd0fcbc054226330c', '09e08f67de901dca540b5c52035a484af07c9aac', 0, '', 'Jen', 'Meyer', 'jenmeyer@fake.org'),
(17, 1383090640, 1383090640, '85f2892f21e56cf96dd8aca3f829b3cf0f5b8c86', '09e08f67de901dca540b5c52035a484af07c9aac', 0, '', 'Brien', 'Bastings', 'brien@fake.org'),
(19, 1383096835, 1383096835, 'e23881953e8837151a6993fca49be0848ef55e89', '09e08f67de901dca540b5c52035a484af07c9aac', 0, '', 'Barry', 'Bentley', 'barry@fake.org'),
(21, 1383429837, 1383430363, 'd0d4ebf17e4267509e5aa4653f58ed61e3e658f7', 'eb02feb7b7bebfe1879c569892203f75cd5b55d3', 0, '', 'Daniel', 'East', 'daneast915@gmail.com'),
(22, 1383430461, 1383430574, '9e05bb0dfe19c29be99dc762cd520c0be7a13d44', '09e08f67de901dca540b5c52035a484af07c9aac', 0, '', 'FirstName', 'LastName', 'email@fake.org');

-- --------------------------------------------------------

--
-- Table structure for table `users_users`
--

CREATE TABLE `users_users` (
  `user_user_id` int(11) NOT NULL AUTO_INCREMENT,
  `created` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_followed` int(11) NOT NULL,
  PRIMARY KEY (`user_user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=44 ;

--
-- Dumping data for table `users_users`
--

INSERT INTO `users_users` (`user_user_id`, `created`, `user_id`, `user_id_followed`) VALUES
(4, 1382821708, 16, 16),
(33, 1383092890, 17, 17),
(34, 1383092892, 17, 14),
(36, 1383424246, 16, 14),
(38, 1383429919, 21, 15),
(39, 1383429920, 21, 19),
(40, 1383429921, 21, 17),
(41, 1383430376, 21, 21),
(42, 1383430489, 22, 22),
(43, 1383430490, 22, 16);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users_users`
--
ALTER TABLE `users_users`
  ADD CONSTRAINT `users_users_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
