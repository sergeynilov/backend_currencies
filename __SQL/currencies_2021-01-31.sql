-- phpMyAdmin SQL Dump
-- version 4.6.6deb5ubuntu0.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost:3306
-- Время создания: Янв 31 2021 г., 10:20
-- Версия сервера: 5.7.32-0ubuntu0.18.04.1
-- Версия PHP: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `currencies`
--

-- --------------------------------------------------------

--
-- Структура таблицы `currency`
--

CREATE TABLE `currency` (
  `id` tinyint(3) UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `char_code` varchar(3) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_top` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `ordering` int(10) UNSIGNED NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `currency`
--

INSERT INTO `currency` (`id`, `name`, `num_code`, `char_code`, `is_top`, `active`, `ordering`, `created_at`, `updated_at`) VALUES
(1, 'Canadian dollar', '124', 'CAD', 1, 1, 6, '2021-01-27 07:43:32', NULL),
(2, 'Hong Kong dollar', '344', 'HKD', 0, 1, 13, '2021-01-27 07:43:32', NULL),
(3, 'Icelandic króna', '352', 'ISK', 0, 1, 11, '2021-01-27 07:43:32', NULL),
(4, 'Philippine peso', '608', 'PHP', 0, 1, 12, '2021-01-27 07:43:32', NULL),
(5, 'Danish krone', '208', 'DKK', 0, 1, 14, '2021-01-27 07:43:32', NULL),
(6, 'Hungarian forint', '348', 'HUF', 0, 1, 15, '2021-01-27 07:43:32', NULL),
(7, 'Czechoslovak koruna', '200', 'CZK', 0, 1, 16, '2021-01-27 07:43:32', NULL),
(8, 'Australian dollar', '30', 'AUD', 1, 1, 3, '2021-01-27 07:43:32', NULL),
(9, 'Romanian leu', '642', 'RON', 0, 1, 17, '2021-01-27 07:43:32', NULL),
(10, 'Swedish krona/kronor', '752', 'SEK', 0, 1, 18, '2021-01-27 07:43:32', NULL),
(11, 'Indonesian rupiah', '360', 'IDR', 0, 1, 19, '2021-01-27 07:43:32', NULL),
(12, 'Indian rupee', '356', 'INR', 0, 1, 20, '2021-01-27 07:43:32', NULL),
(13, 'Brazilian cruzeiro', '62', 'BRL', 0, 1, 21, '2021-01-27 07:43:32', NULL),
(14, 'Russian ruble', '810', 'RUB', 0, 1, 22, '2021-01-27 07:43:32', NULL),
(15, 'Croatian kuna', '191', 'HRK', 0, 1, 23, '2021-01-27 07:43:32', NULL),
(16, 'Japanese yen', '392', 'JPY', 0, 1, 24, '2021-01-27 07:43:32', NULL),
(17, 'Thai baht', '764', 'THB', 0, 0, 25, '2021-01-27 07:43:32', NULL),
(18, 'Swiss franc', '756', 'CHF', 0, 1, 26, '2021-01-27 07:43:32', NULL),
(19, 'Singapore dollar', '702', 'SGD', 0, 1, 27, '2021-01-27 07:43:32', NULL),
(20, 'Polish zloty', '616', 'PLN', 0, 1, 28, '2021-01-27 07:43:32', NULL),
(21, 'Bulgarian lev', '975', 'BGN', 0, 1, 29, '2021-01-27 07:43:32', NULL),
(22, 'Turkish lira', '979', 'TRY', 0, 1, 30, '2021-01-27 07:43:32', NULL),
(23, 'Chinese yuan', '156', 'CNY', 1, 1, 31, '2021-01-27 07:43:32', NULL),
(24, 'Norwegian krone', '578', 'NOK', 0, 1, 32, '2021-01-27 07:43:32', NULL),
(25, 'New Zealand dollar', '544', 'NZD', 0, 1, 33, '2021-01-27 07:43:32', NULL),
(26, 'South African rand', '710', 'ZAR', 0, 1, 34, '2021-01-27 07:43:32', NULL),
(27, 'United States dollar', '840', 'USD', 1, 1, 1, '2021-01-27 07:43:32', NULL),
(28, 'Mexican peso', '484', 'MXN', 0, 1, 35, '2021-01-27 07:43:32', NULL),
(29, 'Israeli new shekel', '376', 'ILS', 0, 1, 36, '2021-01-27 07:43:32', NULL),
(30, 'Pound sterling', '826', 'GBP', 1, 1, 2, '2021-01-27 07:43:32', NULL),
(31, 'South Korean won', '410', 'KRW', 0, 1, 37, '2021-01-27 07:43:32', NULL),
(32, 'Malaysian ringgit', '458', 'MYR', 0, 0, 38, '2021-01-27 07:43:32', NULL),
(33, 'currency name created 2021-01-27 08:20:55', '0wu', 'EUR', 0, 0, 39, '2021-01-27 08:20:55', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `currency_history`
--

CREATE TABLE `currency_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `day` date DEFAULT NULL,
  `currency_id` tinyint(3) UNSIGNED NOT NULL,
  `nominal` smallint(5) UNSIGNED NOT NULL,
  `value` decimal(18,10) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `currency_history`
--

INSERT INTO `currency_history` (`id`, `day`, `currency_id`, `nominal`, `value`, `created_at`) VALUES
(1, '2021-01-27', 1, 1, '1.0000000000', '2021-01-27 08:20:54'),
(2, '2021-01-27', 2, 1, '6.0950530951', '2021-01-27 08:20:54'),
(3, '2021-01-27', 3, 1, '101.6576016576', '2021-01-27 08:20:54'),
(4, '2021-01-27', 4, 1, '37.8004403004', '2021-01-27 08:20:54'),
(5, '2021-01-27', 5, 1, '4.8167573168', '2021-01-27 08:20:54'),
(6, '2021-01-27', 6, 1, '232.2002072002', '2021-01-27 08:20:54'),
(7, '2021-01-27', 7, 1, '16.8868168868', '2021-01-27 08:20:54'),
(8, '2021-01-27', 30, 1, '0.5743201243', '2021-01-27 08:20:54'),
(9, '2021-01-27', 9, 1, '3.1564361564', '2021-01-27 08:20:54'),
(10, '2021-01-27', 10, 1, '6.5213027713', '2021-01-27 08:20:54'),
(11, '2021-01-27', 11, 1, '11089.6464646460', '2021-01-27 08:20:54'),
(12, '2021-01-27', 12, 1, '57.3397435897', '2021-01-27 08:20:54'),
(13, '2021-01-27', 13, 1, '4.2615902616', '2021-01-27 08:20:54'),
(14, '2021-01-27', 14, 1, '59.0868945869', '2021-01-27 08:20:54'),
(15, '2021-01-27', 15, 1, '4.8970473970', '2021-01-27 08:20:54'),
(16, '2021-01-27', 16, 1, '81.5397565398', '2021-01-27 08:20:54'),
(17, '2021-01-27', 17, 1, '23.5722610723', '2021-01-27 08:20:54'),
(18, '2021-01-27', 18, 1, '0.6985884486', '2021-01-27 08:20:55'),
(19, '2021-01-27', 33, 1, '0.6475006475', '2021-01-27 08:20:55'),
(20, '2021-01-27', 32, 1, '3.1831779332', '2021-01-27 08:20:55'),
(21, '2021-01-27', 21, 1, '1.2663817664', '2021-01-27 08:20:55'),
(22, '2021-01-27', 22, 1, '5.7801735302', '2021-01-27 08:20:55'),
(23, '2021-01-27', 23, 1, '5.0852758353', '2021-01-27 08:20:55'),
(24, '2021-01-27', 24, 1, '6.7257834758', '2021-01-27 08:20:55'),
(25, '2021-01-27', 25, 1, '1.0873478373', '2021-01-27 08:20:55'),
(26, '2021-01-27', 26, 1, '11.9182206682', '2021-01-27 08:20:55'),
(27, '2021-01-27', 27, 1, '0.7862600363', '2021-01-27 08:20:55'),
(28, '2021-01-27', 28, 1, '15.7592592593', '2021-01-27 08:20:55'),
(29, '2021-01-27', 19, 1, '1.0424112924', '2021-01-27 08:20:55'),
(30, '2021-01-27', 8, 1, '1.0171587672', '2021-01-27 08:20:55'),
(31, '2021-01-27', 29, 1, '2.5700595701', '2021-01-27 08:20:55'),
(32, '2021-01-27', 31, 1, '867.3335923336', '2021-01-27 08:20:55'),
(33, '2021-01-27', 20, 1, '2.9438616939', '2021-01-27 08:20:55'),
(34, '2021-01-28', 1, 1, '1.0000000000', '2021-01-28 08:51:08'),
(35, '2021-01-28', 2, 1, '6.0772018378', '2021-01-28 08:51:08'),
(36, '2021-01-28', 3, 1, '101.5983951336', '2021-01-28 08:51:08'),
(37, '2021-01-28', 4, 1, '37.7078884359', '2021-01-28 08:51:08'),
(38, '2021-01-28', 5, 1, '4.8131107228', '2021-01-28 08:51:08'),
(39, '2021-01-28', 6, 1, '233.5468841002', '2021-01-28 08:51:08'),
(40, '2021-01-28', 7, 1, '16.8413900214', '2021-01-28 08:51:08'),
(41, '2021-01-28', 30, 1, '0.5720248495', '2021-01-28 08:51:08'),
(42, '2021-01-28', 9, 1, '3.1549213745', '2021-01-28 08:51:08'),
(43, '2021-01-28', 10, 1, '6.5449427296', '2021-01-28 08:51:08'),
(44, '2021-01-28', 11, 1, '11080.8257296320', '2021-01-28 08:51:08'),
(45, '2021-01-28', 12, 1, '57.2303112664', '2021-01-28 08:51:08'),
(46, '2021-01-28', 13, 1, '4.2041674756', '2021-01-28 08:51:08'),
(47, '2021-01-28', 14, 1, '59.3579240277', '2021-01-28 08:51:08'),
(48, '2021-01-28', 15, 1, '4.8916068077', '2021-01-28 08:51:08'),
(49, '2021-01-28', 16, 1, '81.4534394616', '2021-01-28 08:51:08'),
(50, '2021-01-28', 17, 1, '23.5177635411', '2021-01-28 08:51:08'),
(51, '2021-01-28', 18, 1, '0.6962402123', '2021-01-28 08:51:08'),
(52, '2021-01-28', 33, 1, '0.6471235359', '2021-01-28 08:51:08'),
(53, '2021-01-28', 32, 1, '3.1713583123', '2021-01-28 08:51:08'),
(54, '2021-01-28', 21, 1, '1.2656442115', '2021-01-28 08:51:08'),
(55, '2021-01-28', 22, 1, '5.7944735650', '2021-01-28 08:51:08'),
(56, '2021-01-28', 23, 1, '5.0741603572', '2021-01-28 08:51:08'),
(57, '2021-01-28', 24, 1, '6.7660001294', '2021-01-28 08:51:08'),
(58, '2021-01-28', 25, 1, '1.0900148838', '2021-01-28 08:51:08'),
(59, '2021-01-28', 26, 1, '11.9315990423', '2021-01-28 08:51:08'),
(60, '2021-01-28', 27, 1, '0.7839254514', '2021-01-28 08:51:08'),
(61, '2021-01-28', 28, 1, '15.8219763153', '2021-01-28 08:51:08'),
(62, '2021-01-28', 19, 1, '1.0410923445', '2021-01-28 08:51:08'),
(63, '2021-01-28', 8, 1, '1.0188312949', '2021-01-28 08:51:08'),
(64, '2021-01-28', 29, 1, '2.5597618585', '2021-01-28 08:51:08'),
(65, '2021-01-28', 31, 1, '867.6567656766', '2021-01-28 08:51:08'),
(66, '2021-01-28', 20, 1, '2.9457063353', '2021-01-28 08:51:08'),
(67, '2021-01-29', 1, 1, '1.0000000000', '2021-01-29 04:39:03'),
(68, '2021-01-29', 2, 1, '6.0230017990', '2021-01-29 04:39:03'),
(69, '2021-01-29', 3, 1, '100.2313030069', '2021-01-29 04:39:03'),
(70, '2021-01-29', 4, 1, '37.3464405037', '2021-01-29 04:39:03'),
(71, '2021-01-29', 5, 1, '4.7782061167', '2021-01-29 04:39:03'),
(72, '2021-01-29', 6, 1, '231.5214597790', '2021-01-29 04:39:03'),
(73, '2021-01-29', 7, 1, '16.7784631200', '2021-01-29 04:39:03'),
(74, '2021-01-29', 30, 1, '0.5692816757', '2021-01-29 04:39:03'),
(75, '2021-01-29', 9, 1, '3.1329349782', '2021-01-29 04:39:03'),
(76, '2021-01-29', 10, 1, '6.5120149062', '2021-01-29 04:39:03'),
(77, '2021-01-29', 11, 1, '10967.0842970960', '2021-01-29 04:39:03'),
(78, '2021-01-29', 12, 1, '56.7469802107', '2021-01-29 04:39:03'),
(79, '2021-01-29', 13, 1, '4.2313672578', '2021-01-29 04:39:03'),
(80, '2021-01-29', 14, 1, '59.3576201491', '2021-01-29 04:39:03'),
(81, '2021-01-29', 15, 1, '4.8607041892', '2021-01-29 04:39:03'),
(82, '2021-01-29', 16, 1, '81.0845540992', '2021-01-29 04:39:03'),
(83, '2021-01-29', 17, 1, '23.3326908250', '2021-01-29 04:39:03'),
(84, '2021-01-29', 18, 1, '0.6928167566', '2021-01-29 04:39:03'),
(85, '2021-01-29', 33, 1, '0.6425083526', '2021-01-29 04:39:03'),
(86, '2021-01-29', 32, 1, '3.1427653559', '2021-01-29 04:39:03'),
(87, '2021-01-29', 21, 1, '1.2566178360', '2021-01-29 04:39:03'),
(88, '2021-01-29', 22, 1, '5.7384348497', '2021-01-29 04:39:03'),
(89, '2021-01-29', 23, 1, '5.0260858391', '2021-01-29 04:39:03'),
(90, '2021-01-29', 24, 1, '6.7595091236', '2021-01-29 04:39:03'),
(91, '2021-01-29', 25, 1, '1.0927782061', '2021-01-29 04:39:03'),
(92, '2021-01-29', 26, 1, '11.8449627345', '2021-01-29 04:39:03'),
(93, '2021-01-29', 27, 1, '0.7768568491', '2021-01-29 04:39:03'),
(94, '2021-01-29', 28, 1, '15.8819712156', '2021-01-29 04:39:03'),
(95, '2021-01-29', 19, 1, '1.0356592136', '2021-01-29 04:39:03'),
(96, '2021-01-29', 8, 1, '1.0224877923', '2021-01-29 04:39:03'),
(97, '2021-01-29', 29, 1, '2.5587895143', '2021-01-29 04:39:03'),
(98, '2021-01-29', 31, 1, '867.8745823696', '2021-01-29 04:39:03'),
(99, '2021-01-29', 20, 1, '2.9215497301', '2021-01-29 04:39:03'),
(100, '2021-01-30', 1, 1, '1.0000000000', '2021-01-30 07:01:57'),
(101, '2021-01-30', 2, 1, '6.0626932990', '2021-01-30 07:01:57'),
(102, '2021-01-30', 3, 1, '100.5798969072', '2021-01-30 07:01:57'),
(103, '2021-01-30', 4, 1, '37.5856958763', '2021-01-30 07:01:57'),
(104, '2021-01-30', 5, 1, '4.7918814433', '2021-01-30 07:01:57'),
(105, '2021-01-30', 6, 1, '230.9213917526', '2021-01-30 07:01:57'),
(106, '2021-01-30', 7, 1, '16.7654639175', '2021-01-30 07:01:57'),
(107, '2021-01-30', 30, 1, '0.5694780928', '2021-01-30 07:01:57'),
(108, '2021-01-30', 9, 1, '3.1411082474', '2021-01-30 07:01:57'),
(109, '2021-01-30', 10, 1, '6.5148195876', '2021-01-30 07:01:57'),
(110, '2021-01-30', 11, 1, '10949.8453608250', '2021-01-30 07:01:57'),
(111, '2021-01-30', 12, 1, '56.9793814433', '2021-01-30 07:01:57'),
(112, '2021-01-30', 13, 1, '4.2891752577', '2021-01-30 07:01:57'),
(113, '2021-01-30', 14, 1, '59.2125644330', '2021-01-30 07:01:57'),
(114, '2021-01-30', 15, 1, '4.8748711340', '2021-01-30 07:01:57'),
(115, '2021-01-30', 16, 1, '81.8621134021', '2021-01-30 07:01:57'),
(116, '2021-01-30', 17, 1, '23.3807989691', '2021-01-30 07:01:57'),
(117, '2021-01-30', 18, 1, '0.6957474227', '2021-01-30 07:01:57'),
(118, '2021-01-30', 33, 1, '0.6443298969', '2021-01-30 07:01:57'),
(119, '2021-01-30', 32, 1, '3.1610824742', '2021-01-30 07:01:57'),
(120, '2021-01-30', 21, 1, '1.2601804124', '2021-01-30 07:01:57'),
(121, '2021-01-30', 22, 1, '5.7198453608', '2021-01-30 07:01:57'),
(122, '2021-01-30', 23, 1, '5.0288015464', '2021-01-30 07:01:57'),
(123, '2021-01-30', 24, 1, '6.6643041237', '2021-01-30 07:01:57'),
(124, '2021-01-30', 25, 1, '1.0865335052', '2021-01-30 07:01:57'),
(125, '2021-01-30', 26, 1, '11.7949742268', '2021-01-30 07:01:57'),
(126, '2021-01-30', 27, 1, '0.7819587629', '2021-01-30 07:01:57'),
(127, '2021-01-30', 28, 1, '15.8129510309', '2021-01-30 07:01:57'),
(128, '2021-01-30', 19, 1, '1.0387242268', '2021-01-30 07:01:57'),
(129, '2021-01-30', 8, 1, '1.0192654639', '2021-01-30 07:01:57'),
(130, '2021-01-30', 29, 1, '2.5653350515', '2021-01-30 07:01:57'),
(131, '2021-01-30', 31, 1, '873.0541237113', '2021-01-30 07:01:57'),
(132, '2021-01-30', 20, 1, '2.9190721649', '2021-01-30 07:01:57'),
(133, '2021-01-31', 1, 1, '1.0000000000', '2021-01-31 04:50:41'),
(134, '2021-01-31', 2, 1, '6.0626932990', '2021-01-31 04:50:41'),
(135, '2021-01-31', 3, 1, '100.5798969072', '2021-01-31 04:50:41'),
(136, '2021-01-31', 4, 1, '37.5856958763', '2021-01-31 04:50:41'),
(137, '2021-01-31', 5, 1, '4.7918814433', '2021-01-31 04:50:41'),
(138, '2021-01-31', 6, 1, '230.9213917526', '2021-01-31 04:50:41'),
(139, '2021-01-31', 7, 1, '16.7654639175', '2021-01-31 04:50:41'),
(140, '2021-01-31', 30, 1, '0.5694780928', '2021-01-31 04:50:41'),
(141, '2021-01-31', 9, 1, '3.1411082474', '2021-01-31 04:50:41'),
(142, '2021-01-31', 10, 1, '6.5148195876', '2021-01-31 04:50:41'),
(143, '2021-01-31', 11, 1, '10949.8453608250', '2021-01-31 04:50:41'),
(144, '2021-01-31', 12, 1, '56.9793814433', '2021-01-31 04:50:41'),
(145, '2021-01-31', 13, 1, '4.2891752577', '2021-01-31 04:50:41'),
(146, '2021-01-31', 14, 1, '59.2125644330', '2021-01-31 04:50:41'),
(147, '2021-01-31', 15, 1, '4.8748711340', '2021-01-31 04:50:41'),
(148, '2021-01-31', 16, 1, '81.8621134021', '2021-01-31 04:50:41'),
(149, '2021-01-31', 17, 1, '23.3807989691', '2021-01-31 04:50:41'),
(150, '2021-01-31', 18, 1, '0.6957474227', '2021-01-31 04:50:41'),
(151, '2021-01-31', 33, 1, '0.6443298969', '2021-01-31 04:50:41'),
(152, '2021-01-31', 32, 1, '3.1610824742', '2021-01-31 04:50:41'),
(153, '2021-01-31', 21, 1, '1.2601804124', '2021-01-31 04:50:41'),
(154, '2021-01-31', 22, 1, '5.7198453608', '2021-01-31 04:50:41'),
(155, '2021-01-31', 23, 1, '5.0288015464', '2021-01-31 04:50:41'),
(156, '2021-01-31', 24, 1, '6.6643041237', '2021-01-31 04:50:41'),
(157, '2021-01-31', 25, 1, '1.0865335052', '2021-01-31 04:50:41'),
(158, '2021-01-31', 26, 1, '11.7949742268', '2021-01-31 04:50:41'),
(159, '2021-01-31', 27, 1, '0.7819587629', '2021-01-31 04:50:41'),
(160, '2021-01-31', 28, 1, '15.8129510309', '2021-01-31 04:50:41'),
(161, '2021-01-31', 19, 1, '1.0387242268', '2021-01-31 04:50:41'),
(162, '2021-01-31', 8, 1, '1.0192654639', '2021-01-31 04:50:41'),
(163, '2021-01-31', 29, 1, '2.5653350515', '2021-01-31 04:50:41'),
(164, '2021-01-31', 31, 1, '873.0541237113', '2021-01-31 04:50:41'),
(165, '2021-01-31', 20, 1, '2.9190721649', '2021-01-31 04:50:41');

-- --------------------------------------------------------

--
-- Структура таблицы `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `laravel_logger_activity`
--

CREATE TABLE `laravel_logger_activity` (
  `id` int(10) UNSIGNED NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` longtext COLLATE utf8mb4_unicode_ci,
  `userType` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `route` longtext COLLATE utf8mb4_unicode_ci,
  `ipAddress` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `userAgent` text COLLATE utf8mb4_unicode_ci,
  `locale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referer` longtext COLLATE utf8mb4_unicode_ci,
  `methodType` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `laravel_logger_activity`
--

INSERT INTO `laravel_logger_activity` (`id`, `description`, `details`, `userType`, `userId`, `route`, `ipAddress`, `userAgent`, `locale`, `referer`, `methodType`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'New currency added', 'New currency char code : EUR', 'Guest', NULL, 'http://local-backend-currencies.com', '127.0.0.1', 'Symfony', 'en-us,en;q=0.5', NULL, 'GET', '2021-01-27 06:20:55', '2021-01-27 06:20:55', NULL),
(2, 'Currency rates completed', 'New currencies added  : 1, new currency rates added : 33', 'Guest', NULL, 'http://local-backend-currencies.com', '127.0.0.1', 'Symfony', 'en-us,en;q=0.5', NULL, 'GET', '2021-01-27 06:20:55', '2021-01-27 06:20:55', NULL),
(3, 'Currency rates completed', 'New currencies added  : 0, new currency rates added : 33', 'Guest', NULL, 'http://local-backend-currencies.com', '127.0.0.1', 'Symfony', 'en-us,en;q=0.5', NULL, 'GET', '2021-01-28 06:51:08', '2021-01-28 06:51:08', NULL),
(4, 'Currency rates completed', 'New currencies added  : 0, new currency rates added : 33', 'Guest', NULL, 'http://local-backend-currencies.com', '127.0.0.1', 'Symfony', 'en-us,en;q=0.5', NULL, 'GET', '2021-01-29 02:39:03', '2021-01-29 02:39:03', NULL),
(5, 'Currency rates completed', 'New currencies added  : 0, new currency rates added : 33', 'Guest', NULL, 'http://local-backend-currencies.com', '127.0.0.1', 'Symfony', 'en-us,en;q=0.5', NULL, 'GET', '2021-01-30 05:01:57', '2021-01-30 05:01:57', '2021-01-30 13:38:23'),
(6, 'Currency rates completed', 'New currencies added  : 0, new currency rates added : 33', 'Guest', NULL, 'http://local-backend-currencies.com', '127.0.0.1', 'Symfony', 'en-us,en;q=0.5', NULL, 'GET', '2021-01-31 02:50:41', '2021-01-31 02:50:41', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(20, '2014_10_12_000000_create_users_table', 1),
(21, '2014_10_12_100000_create_password_resets_table', 1),
(22, '2017_11_04_103444_create_laravel_logger_activity_table', 1),
(23, '2019_08_19_000000_create_failed_jobs_table', 1),
(24, '2019_11_15_182215_create_settings_table', 1),
(25, '2020_11_26_072609_create_currency_table', 1),
(26, '2020_11_26_102802_create_currency_history_table', 1);

-- --------------------------------------------------------

--
-- Структура таблицы `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `settings`
--

INSERT INTO `settings` (`id`, `name`, `value`, `created_at`, `updated_at`) VALUES
(1, 'items_per_page', '4', '2021-01-27 07:43:32', NULL),
(2, 'site_name', 'Currency rates with history', '2021-01-27 07:43:32', NULL),
(3, 'copyright_text', '© 2020 - 2021 All rights reserved', '2021-01-27 07:43:32', NULL),
(4, 'site_heading', 'Get Currency rate you need.', '2021-01-27 07:43:32', NULL),
(5, 'base_currency', 'CAD', '2021-01-27 07:43:32', NULL),
(6, 'backend_items_per_page', '10', '2021-01-27 11:59:02', NULL);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Currency Adminer', 'adminer@currency.site.com', NULL, '$2y$10$OpTgkCDilzb9Xzg5gKBi8ehEEZSkf0Qw0BbSS6uf2pHzzg1d.Ll4q', NULL, NULL, NULL);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currency_name_unique` (`name`),
  ADD UNIQUE KEY `currency_num_code_unique` (`num_code`),
  ADD UNIQUE KEY `currency_char_code_unique` (`char_code`),
  ADD KEY `currency_created_at_index` (`created_at`),
  ADD KEY `currencies_id_active_char_code_creator_index` (`active`,`char_code`,`is_top`),
  ADD KEY `currencies_ordering_active_index` (`ordering`,`active`);

--
-- Индексы таблицы `currency_history`
--
ALTER TABLE `currency_history`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `currency_history_day_currency_id_unique` (`day`,`currency_id`),
  ADD KEY `currency_history_created_at_index` (`created_at`),
  ADD KEY `currency_history_currency_id_day_index` (`currency_id`,`day`);

--
-- Индексы таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Индексы таблицы `laravel_logger_activity`
--
ALTER TABLE `laravel_logger_activity`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Индексы таблицы `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_name_unique` (`name`),
  ADD KEY `settings_created_at_index` (`created_at`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `currency`
--
ALTER TABLE `currency`
  MODIFY `id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT для таблицы `currency_history`
--
ALTER TABLE `currency_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;
--
-- AUTO_INCREMENT для таблицы `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `laravel_logger_activity`
--
ALTER TABLE `laravel_logger_activity`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT для таблицы `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `currency_history`
--
ALTER TABLE `currency_history`
  ADD CONSTRAINT `currency_history_currency_id_foreign` FOREIGN KEY (`currency_id`) REFERENCES `currency` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
