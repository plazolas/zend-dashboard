-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 02, 2017 at 03:50 PM
-- Server version: 5.7.19-0ubuntu0.16.04.1
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `allergen_seasons`
--

CREATE TABLE `allergen_seasons` (
  `id` int(11) UNSIGNED NOT NULL,
  `name` varchar(128) NOT NULL,
  `type` varchar(48) NOT NULL,
  `regions` varchar(48) NOT NULL,
  `season` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `allergen_seasons`
--

INSERT INTO `allergen_seasons` (`id`, `name`, `type`, `regions`, `season`) VALUES
(1, 'guillyweed', 'weed', 'NE,SE,NW', 'October-December'),
(2, 'oak', 'tree', 'NW', 'September - December'),
(3, 'CAT', 'animal', 'ALL', 'ALL'),
(4, 'Alternaria', 'mold', 'NW', 'December-February'),
(5, 'Acacia', 'tree', 'SE,SW', 'May-August');

-- --------------------------------------------------------

--
-- Table structure for table `attempts`
--

CREATE TABLE `attempts` (
  `id` int(11) NOT NULL,
  `ip` varchar(39) NOT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `config`
--

CREATE TABLE `config` (
  `setting` varchar(100) NOT NULL,
  `value` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `config`
--

INSERT INTO `config` (`setting`, `value`) VALUES
('attack_mitigation_time', '+30 minutes'),
('attempts_before_ban', '30'),
('attempts_before_verify', '5'),
('bcrypt_cost', '10'),
('cookie_domain', 'localhost'),
('cookie_forget', '+30 minutes'),
('cookie_http', '0'),
('cookie_name', 'authID'),
('cookie_path', '/'),
('cookie_remember', '+1 month'),
('cookie_secure', '0'),
('emailmessage_suppress_activation', '0'),
('emailmessage_suppress_reset', '0'),
('mail_charset', 'UTF-8'),
('password_min_score', '3'),
('request_key_expiration', '+10 minutes'),
('site_activation_page', 'activate'),
('site_email', 'plazolas@yahoo.com'),
('site_key', 'fghuior.)/!/jdUkd8s2!7HVHG7777ghg'),
('site_name', 'dashboard'),
('site_password_reset_page', 'reset'),
('site_timezone', 'America/New_York'),
('site_url', 'https://github.com/PHPAuth/PHPAuth'),
('smtp', '0'),
('smtp_auth', '1'),
('smtp_host', 'smtp.example.com'),
('smtp_password', 'password'),
('smtp_port', '25'),
('smtp_security', NULL),
('smtp_username', 'email@example.com'),
('table_attempts', 'attempts'),
('table_requests', 'requests'),
('table_sessions', 'sessions'),
('table_users', 'users'),
('verify_email_max_length', '100'),
('verify_email_min_length', '5'),
('verify_email_use_banlist', '1'),
('verify_password_min_length', '3');

-- --------------------------------------------------------

--
-- Table structure for table `learning`
--

CREATE TABLE `learning` (
  `id` int(11) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `contact` varchar(128) DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  `address` varchar(128) DEFAULT NULL,
  `address2` varchar(128) DEFAULT NULL,
  `city` varchar(128) DEFAULT NULL,
  `state` varchar(128) DEFAULT NULL,
  `zip` varchar(128) DEFAULT NULL,
  `phone` varchar(128) DEFAULT NULL,
  `region` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `learning`
--

INSERT INTO `learning` (`id`, `name`, `contact`, `email`, `address`, `address2`, `city`, `state`, `zip`, `phone`, `region`) VALUES
(1, 'Hogwarts', 'Albus Dumbeldorf', 'albus@hogwarts.com', '1000 Hogsmead Terrace', 'Castle', 'Hogsmead Heights', 'Scottland', '99912', '555-1212', 'NE'),
(4, 'Boston Medical Center', 'John Banner', 'bob@kendalallergycenter.net', '123 Main St, 301', 'Medical Center', 'Boston', 'MA', '11304', '3055551212', 'NE'),
(5, 'Florida Atlantic University', 'David Stein', 'd.stein@fau.com', '101 Palm Blvd', '301', 'Boca Raton', 'FL', '33448', '3055551212', 'SE'),
(6, 'Harvard University', 'Zuckerberg', 'rich@greed.com', '123 N Boston Blvd', '0', 'Boston', 'MA', '10950', '215 - 555 1212', 'NE'),
(7, 'California State University', 'Mrs Smioth', 'blond@bleached.com', '101 University Drive', '0', 'San Diego', 'CS', '90303', '345-555-1212', 'SW'),
(8, 'UCLA', 'Mr Surf', 'rose@ucla.com', '101 W Sunrise Drive', '0', 'Los Angeles', 'CA', '90210', '345-123-2342', 'sw'),
(9, 'Berkley', 'Mr Berg', 'berg@berkley.com', '123 Pines Drive', '0', 'San Francisco', 'CA', '99887', '234-234-2343', 'NW');

-- --------------------------------------------------------

--
-- Table structure for table `requests`
--

CREATE TABLE `requests` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `rkey` varchar(20) NOT NULL,
  `expire` datetime NOT NULL,
  `type` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `hash` varchar(40) NOT NULL,
  `expiredate` datetime NOT NULL,
  `ip` varchar(39) NOT NULL,
  `agent` varchar(200) NOT NULL,
  `cookie_crc` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `uid`, `hash`, `expiredate`, `ip`, `agent`, `cookie_crc`) VALUES
(102, 3, '46c85919c3ebdb4213d5d818b0685f05aab4e947', '2017-09-28 00:32:04', '127.0.2.2', 'PHPUnit', 'fe99448729f77f932e5839b7fa7cea0c353022ef'),
(103, 1, '042ca6987a4a1aa2682b17a67876ca8e0658c804', '2017-09-28 00:42:17', '::1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36', '35e73f342a83c06417532336b7886b014b84c004');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pid` varchar(60) DEFAULT NULL,
  `role` varchar(60) DEFAULT NULL,
  `name` varchar(60) DEFAULT NULL,
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `password` varchar(128) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `user` varchar(256) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `pid`, `role`, `name`, `dt`, `password`, `user`) VALUES
(1, 'plazolas@yahoo.com', '1', 'admin', 'Oswald Plazola', '2016-12-12 03:07:11', 'AdmDsh2017@', 'plazolas'),
(2, 'madeye@phoenix.com', '1', 'practice', 'Mad Eye', '2017-08-20 15:49:52', 'MadEye123@', 'madeye');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT '0',
  `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `isactive`, `dt`) VALUES
(1, 'plazolas@yahoo.com', '$2y$10$GP7UfTz.tGywLlzGDZ/fwOqnK872dtsWotzQ.5DdU93xkc.lxYgMC', 1, '2016-12-12 02:08:50'),
(2, 'madeye@phoenix.com', '$2y$10$3gHPMvuXoTpiGPfxlyw5AOhJ9X/lRgZA2naFBtO0sqH7GlgeuAjPi', 1, '2017-08-19 20:39:47');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allergen_seasons`
--
ALTER TABLE `allergen_seasons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attempts`
--
ALTER TABLE `attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `config`
--
ALTER TABLE `config`
  ADD UNIQUE KEY `setting` (`setting`);

--
-- Indexes for table `drinfo`
--
ALTER TABLE `drinfo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `learning`
--
ALTER TABLE `learning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allergen_seasons`
--
ALTER TABLE `allergen_seasons`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `attempts`
--
ALTER TABLE `attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `drinfo`
--
ALTER TABLE `drinfo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `learning`
--
ALTER TABLE `learning`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `sessions`
--
ALTER TABLE `sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
