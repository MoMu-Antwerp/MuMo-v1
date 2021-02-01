-- phpMyAdmin SQL Dump
-- version 4.5.4.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 18, 2021 at 11:27 AM
-- Server version: 5.7.11
-- PHP Version: 5.6.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_ID` int(11) NOT NULL,
  `device_ID` int(11) NOT NULL,
  `alert_type` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `json` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data`
--

CREATE TABLE `data` (
  `data_ID` bigint(20) NOT NULL,
  `device_ID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `frame_counter` int(11) DEFAULT NULL,
  `json` text,
  `connections` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_images`
--

CREATE TABLE `data_images` (
  `data_ID` bigint(20) NOT NULL,
  `device_ID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image` varchar(6000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `groepen`
--

CREATE TABLE `groepen` (
  `group_ID` int(11) NOT NULL,
  `group_name` varchar(15) NOT NULL,
  `parent_group` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_ID` int(11) NOT NULL,
  `device_ID` int(11) NOT NULL,
  `alert_ID` int(11) NOT NULL,
  `measurement_ID` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `notified_users` varchar(50) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `cleared_timestamp` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `notification` text NOT NULL,
  `note` text,
  `note_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sensoren`
--

CREATE TABLE `sensoren` (
  `device_ID` int(11) NOT NULL,
  `device_EUI` varchar(18) NOT NULL,
  `name` text,
  `group_ID` int(11) DEFAULT NULL,
  `code_version` int(11) DEFAULT NULL,
  `last_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `offsets` varchar(100) NOT NULL,
  `type` tinyint(4) NOT NULL,
  `url` varchar(250) DEFAULT NULL,
  `description_note` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_ID` int(11) NOT NULL,
  `user_name` varchar(25) NOT NULL,
  `email` varchar(50) NOT NULL,
  `activated` int(2) NOT NULL DEFAULT '0',
  `user_login` varchar(25) DEFAULT NULL,
  `user_password` varchar(65) DEFAULT NULL,
  `privileges` int(11) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


CREATE TABLE `varia` (
  `varia_ID` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `value` text,
  `timestamp` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_ID`);

--
-- Indexes for table `data`
--
ALTER TABLE `data`
  ADD PRIMARY KEY (`data_ID`);

--
-- Indexes for table `data_images`
--
ALTER TABLE `data_images`
  ADD PRIMARY KEY (`data_ID`);

--
-- Indexes for table `groepen`
--
ALTER TABLE `groepen`
  ADD PRIMARY KEY (`group_ID`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_ID`);

--
-- Indexes for table `sensoren`
--
ALTER TABLE `sensoren`
  ADD PRIMARY KEY (`device_ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_ID`);

--
-- Indexes for table `varia`
--
ALTER TABLE `varia`
  ADD PRIMARY KEY (`varia_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `data`
--
ALTER TABLE `data`
  MODIFY `data_ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `data_images`
--
ALTER TABLE `data_images`
  MODIFY `data_ID` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `groepen`
--
ALTER TABLE `groepen`
  MODIFY `group_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `sensoren`
--
ALTER TABLE `sensoren`
  MODIFY `device_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `varia`
--
ALTER TABLE `varia`
  MODIFY `varia_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_ID`, `device_ID`, `alert_type`, `action`, `json`) VALUES
(1, 0, 0, '["1"]', '[null,null]'),
(2, 0, 1, '["1"]', '[null,null,null,null,null,null,null]'),
(3, 0, 2, '["1"]', '[null,null,null,null,null,null,null]');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_ID`, `user_name`, `email`, `activated`, `user_login`, `user_password`, `privileges`) VALUES
(1, 'admin', 'example@example.com', 1, 'admin', '$2y$10$rL5CX0JADSAH2ZyeFlpj1OWgm4q/m20Ta.fjTA9wj456q8NsXtof2', 2);

--
-- Dumping data for table `varia`
--

INSERT INTO `varia` (`varia_ID`, `name`, `value`, `timestamp`) VALUES
(1, 'last_update', NULL, '2021-01-19 23:20:39');

