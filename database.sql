-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 08, 2014 at 12:42 PM
-- Server version: 5.5.24-log
-- PHP Version: 5.4.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `carenewsletter2`
--

-- --------------------------------------------------------

--
-- Table structure for table `analytics`
--

CREATE TABLE IF NOT EXISTS `analytics` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(10) unsigned NOT NULL,
  `email_id` int(10) unsigned NOT NULL,
  `recipient` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `apicall_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `country` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `city` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_name` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_type` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `client_os` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `device_type` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reason` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `analytics_subscriber_id_index` (`subscriber_id`),
  KEY `analytics_email_id_index` (`email_id`),
  KEY `analytics_recipient_index` (`recipient`),
  KEY `analytics_apicall_id_index` (`apicall_id`),
  KEY `analytics_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `clicks`
--

CREATE TABLE IF NOT EXISTS `clicks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `subscriber_id` int(10) unsigned NOT NULL,
  `email_id` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `clicks_subscriber_id_index` (`subscriber_id`),
  KEY `clicks_email_id_index` (`email_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `drafts`
--

CREATE TABLE IF NOT EXISTS `drafts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `drafts_user_id_index` (`user_id`),
  KEY `drafts_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  `from` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `reply_to` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_body` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `emails_user_id_index` (`user_id`),
  KEY `emails_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maillists`
--

CREATE TABLE IF NOT EXISTS `maillists` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `maillists_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `maillist_subscriber`
--

CREATE TABLE IF NOT EXISTS `maillist_subscriber` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `maillist_id` int(10) unsigned NOT NULL,
  `subscriber_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `maillist_subscriber_maillist_id_index` (`maillist_id`),
  KEY `maillist_subscriber_subscriber_id_index` (`subscriber_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2013_11_14_122115_create_users_table', 1),
('2013_11_14_122143_create_groups_table', 1),
('2013_11_14_122202_create_users_groups_table', 1),
('2013_11_14_122231_create_throttle_table', 1),
('2013_11_14_122308_create_settings_table', 1),
('2014_02_26_212509_create_subscribers_table', 1),
('2014_03_18_105842_create_maillists_table', 1),
('2014_03_18_110117_create_maillist_subscriber_table', 1),
('2014_03_18_110225_create_tags_table', 1),
('2014_03_18_110347_create_emails_table', 1),
('2014_03_18_110940_create_analytics_table', 1),
('2014_05_08_152514_create_clicks_table', 1),
('2014_05_09_110446_create_tests_table', 1),
('2014_07_08_105044_create_drafts_table', 1),
('2014_07_18_170629_create_pages_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `anchors` text COLLATE utf8_unicode_ci,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `pages_slug_index` (`slug`),
  KEY `pages_user_id_foreign` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `sitename` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE IF NOT EXISTS `subscribers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE IF NOT EXISTS `tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tests`
--

CREATE TABLE IF NOT EXISTS `tests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mailgun_input` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `throttle`
--

CREATE TABLE IF NOT EXISTS `throttle` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `ip_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `suspended` tinyint(1) NOT NULL DEFAULT '0',
  `banned` tinyint(1) NOT NULL DEFAULT '0',
  `last_attempt_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `banned_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `throttle_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8_unicode_ci,
  `activated` tinyint(1) NOT NULL DEFAULT '0',
  `activation_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `activated_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `persist_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `reset_password_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `first_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_activation_code_index` (`activation_code`),
  KEY `users_reset_password_code_index` (`reset_password_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE IF NOT EXISTS `users_groups` (
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `analytics`
--
ALTER TABLE `analytics`
  ADD CONSTRAINT `analytics_email_id_foreign` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `analytics_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `clicks`
--
ALTER TABLE `clicks`
  ADD CONSTRAINT `clicks_email_id_foreign` FOREIGN KEY (`email_id`) REFERENCES `emails` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `clicks_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `drafts`
--
ALTER TABLE `drafts`
  ADD CONSTRAINT `drafts_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `drafts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `emails`
--
ALTER TABLE `emails`
  ADD CONSTRAINT `emails_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `emails_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `maillist_subscriber`
--
ALTER TABLE `maillist_subscriber`
  ADD CONSTRAINT `maillist_subscriber_subscriber_id_foreign` FOREIGN KEY (`subscriber_id`) REFERENCES `subscribers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `maillist_subscriber_maillist_id_foreign` FOREIGN KEY (`maillist_id`) REFERENCES `maillists` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pages`
--
ALTER TABLE `pages`
  ADD CONSTRAINT `pages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
