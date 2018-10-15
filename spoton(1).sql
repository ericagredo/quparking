-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: parkit.csjmuuzdavhy.us-east-1.rds.amazonaws.com:3306
-- Generation Time: Dec 11, 2017 at 12:29 PM
-- Server version: 5.6.10
-- PHP Version: 7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `spoton`
--

-- --------------------------------------------------------

--
-- Table structure for table `action_master`
--

CREATE TABLE `action_master` (
  `action_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `controller` varchar(200) NOT NULL,
  `action` varchar(200) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `visible` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `check_login` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `comman_action` enum('Yes','No') NOT NULL DEFAULT 'No'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `action_master`
--

INSERT INTO `action_master` (`action_id`, `parent_id`, `menu_id`, `name`, `controller`, `action`, `sort_order`, `visible`, `check_login`, `comman_action`) VALUES
(1, 0, 1, 'Dashboard', 'admin', 'dashboard', 0, 'Yes', 'Yes', 'No'),
(2, 0, 2, 'AdminUser', 'admin', 'adminusers', 0, 'Yes', 'Yes', 'No'),
(3, 0, 2, 'AddUser', 'admin', 'createAdminUser', 0, 'Yes', 'Yes', 'No'),
(4, 0, 2, 'EditUser', 'admin', 'editadminUser', 0, 'Yes', 'Yes', 'No'),
(5, 0, 3, 'Static Pages', 'staticpages', 'staticpagelist', 0, 'Yes', 'Yes', 'No'),
(6, 0, 3, 'Add Static Page', 'staticpages', 'createStaticPage', 0, 'Yes', 'Yes', 'No'),
(7, 0, 3, 'Edit Static Page', 'staticpages', 'editStaticPage', 0, 'Yes', 'Yes', 'No'),
(8, 0, 4, 'Email Templates ', 'emailtemplate', 'emailtemplatelist', 0, 'Yes', 'Yes', 'No'),
(9, 0, 4, 'Add Email Template', 'emailtemplate', 'createemailtemplate', 0, 'Yes', 'Yes', 'No'),
(10, 0, 4, 'Edit Email Template', 'emailtemplate', 'editemailtemplate', 0, 'Yes', 'Yes', 'No'),
(11, 0, 5, 'App Settings', 'appsettings', 'editappsettings', 1, 'Yes', 'Yes', 'No'),
(12, 0, 6, 'Users List', 'users', 'adminUsersList', 1, 'Yes', 'Yes', 'No'),
(13, 0, 6, 'Add Users', 'users', 'createadminUsers', 2, 'Yes', 'Yes', 'No'),
(14, 0, 6, 'Edit Users', 'users', 'editadminUsers', 3, 'Yes', 'Yes', 'No'),
(15, 0, 6, 'Delete Users', 'users', 'deleteadminUsers', 4, 'Yes', 'Yes', 'No'),
(16, 0, 6, 'Active/Inactive Users', 'users', 'activeInactiveadminUsers', 5, 'Yes', 'Yes', 'No'),
(17, 0, 7, 'Parking Spot List', 'parkingspot', 'parkingspotList', 1, 'Yes', 'Yes', 'No'),
(18, 0, 7, 'Add Parking Spot', 'parkingspot', 'createparkingspot', 2, 'Yes', 'Yes', 'No'),
(19, 0, 7, 'Edit Parking Spot', 'parkingspot', 'editparkingspot', 3, 'Yes', 'Yes', 'No'),
(20, 0, 7, 'Delete Parking Spot', 'parkingspot', 'deleteparkingspot', 4, 'Yes', 'Yes', 'No'),
(21, 0, 7, 'Active/Inactive Parking Spot', 'parkingspot', 'activeInactiveparkingspot', 5, 'Yes', 'Yes', 'No'),
(22, 0, 8, 'Pricing List', 'pricing', 'pricingList', 1, 'Yes', 'Yes', 'No'),
(23, 0, 8, 'Add Pricing', 'pricing', 'createpricing', 2, 'Yes', 'Yes', 'No'),
(24, 0, 8, 'Edit Pricing', 'pricing', 'editpricing', 3, 'Yes', 'Yes', 'No'),
(25, 0, 8, 'Delete Pricing', 'pricing', 'deletepricing', 4, 'Yes', 'Yes', 'No'),
(26, 0, 8, 'Active/Inactive Pricing', 'pricing', 'activeInactivepricing', 5, 'Yes', 'Yes', 'No'),
(27, 0, 9, 'Edit Surcharge Amount', 'surchargeamount', 'editsurchargeamount', 1, 'Yes', 'Yes', 'No'),
(28, 0, 10, 'Edit General Settings', 'generalsettings', 'editgeneralsettings', 1, 'Yes', 'Yes', 'No'),
(29, 0, 11, 'Review Questionnaire List', 'review', 'reviewList', 1, 'Yes', 'Yes', 'No'),
(30, 0, 11, 'Add Review Questionnaire', 'review', 'createreview', 2, 'Yes', 'Yes', 'No'),
(31, 0, 11, 'Edit Review Questionnaire', 'review', 'editreview', 3, 'Yes', 'Yes', 'No'),
(32, 0, 11, 'Delete Review Questionnaire', 'review', 'deletereview', 4, 'Yes', 'Yes', 'No'),
(33, 0, 11, 'Active/Inactive Review Questionnaire', 'review', 'activeInactivereview', 5, 'Yes', 'Yes', 'No'),
(34, 0, 12, 'Promocode List', 'promocode', 'promocodeList', 1, 'Yes', 'Yes', 'No'),
(35, 0, 12, 'Add Promocode', 'promocode', 'createpromocode', 2, 'Yes', 'Yes', 'No'),
(36, 0, 12, 'Edit Promocode', 'promocode', 'editpromocode', 3, 'Yes', 'Yes', 'No'),
(37, 0, 12, 'Delete Promocode', 'promocode', 'deletepromocode', 4, 'Yes', 'Yes', 'No'),
(38, 0, 12, 'Active/Inactive Promocode', 'promocode', 'activeInactivepromocode', 5, 'Yes', 'Yes', 'No'),
(39, 0, 13, 'Contact Us List', 'contactus', 'contactusList', 1, 'Yes', 'Yes', 'No'),
(40, 0, 13, 'Reply to User Contactus', 'contactus', 'replytousercontactus', 2, 'Yes', 'Yes', 'No'),
(41, 0, 13, 'Delete Contactus', 'contactus', 'deletecontactus', 3, 'Yes', 'Yes', 'No'),
(42, 0, 14, 'Admin Notification List', 'adminnotification', 'adminnotificationList', 14, 'Yes', 'Yes', 'No'),
(43, 0, 14, 'Edit Admin Notification', 'adminnotification', 'updatenotification', 14, 'Yes', 'Yes', 'No'),
(44, 0, 7, 'Manage Parking Spot Images', 'parkingspot', 'manageparkingspotGallery', 0, 'Yes', 'Yes', 'No'),
(45, 0, 15, 'Manage Country', 'country', 'managecountry', 15, 'Yes', 'Yes', 'No'),
(46, 0, 16, 'Manage State', 'state', 'managestate', 16, 'Yes', 'Yes', 'No'),
(47, 0, 17, 'Manage Bookig', 'booking', 'managebooking', 17, 'Yes', 'Yes', 'No'),
(48, 0, 18, 'Manage User Notification', 'usernotification', 'manageusernotification', 14, 'Yes', 'Yes', 'No'),
(49, 0, 19, 'Manage Monthly Reports', 'reports', 'reportListAdmin', 18, 'Yes', 'Yes', 'No'),
(50, 0, 20, 'Manage Refund', 'refund', 'RefundListAdmin', 19, 'Yes', 'Yes', 'No');

-- --------------------------------------------------------

--
-- Table structure for table `admin_fund_managment`
--

CREATE TABLE `admin_fund_managment` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `report_by_month` varchar(255) NOT NULL,
  `total_booking_amount` varchar(255) NOT NULL,
  `cancellation_fee_by_host` varchar(255) NOT NULL,
  `cancellation_fee_by_renter` varchar(255) NOT NULL,
  `surcharge_amount` varchar(255) NOT NULL,
  `refunded_amount` varchar(255) NOT NULL,
  `admin_commission_amount` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `upload_bank_receipt` varchar(255) NOT NULL,
  `payment_status` enum('Pending','Funded') NOT NULL DEFAULT 'Pending',
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notification`
--

CREATE TABLE `admin_notification` (
  `id` int(11) NOT NULL,
  `notification_title` varchar(255) NOT NULL,
  `notification_mode` enum('ON','OFF') NOT NULL DEFAULT 'ON',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_notification_management`
--

CREATE TABLE `admin_notification_management` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `notification_for` enum('new_spot','new_booking','cancelled_booking','surcharge_amount','') NOT NULL,
  `is_show` enum('Yes','No') NOT NULL,
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_notification_management`
--

INSERT INTO `admin_notification_management` (`id`, `users_id`, `parking_spot_id`, `booking_id`, `notification_for`, `is_show`, `created_date`, `created_by`) VALUES
(150, 61, 151, 0, 'new_spot', 'Yes', '2017-12-11 06:57:44', 61),
(151, 62, 151, 189, 'new_booking', 'Yes', '2017-12-11 07:18:02', 62),
(152, 62, 151, 190, 'new_booking', 'Yes', '2017-12-11 08:23:14', 62),
(153, 62, 151, 190, 'cancelled_booking', 'Yes', '2017-12-11 08:23:50', 62),
(154, 62, 151, 191, 'new_booking', 'No', '2017-12-11 08:28:42', 62),
(155, 62, 151, 191, 'cancelled_booking', 'Yes', '2017-12-11 08:28:55', 62),
(156, 62, 151, 192, 'new_booking', 'No', '2017-12-11 08:34:44', 62),
(157, 61, 151, 192, 'cancelled_booking', 'Yes', '2017-12-11 08:35:36', 61),
(158, 62, 151, 193, 'new_booking', 'No', '2017-12-11 08:40:16', 62),
(159, 61, 151, 193, 'cancelled_booking', 'Yes', '2017-12-11 08:40:35', 61),
(160, 62, 151, 194, 'new_booking', 'No', '2017-12-11 08:42:43', 62),
(161, 62, 151, 194, 'cancelled_booking', 'Yes', '2017-12-11 08:43:01', 62);

-- --------------------------------------------------------

--
-- Table structure for table `app_setting`
--

CREATE TABLE `app_setting` (
  `id` int(11) NOT NULL,
  `tbl_adminuser_id` int(11) NOT NULL,
  `app_name` varchar(255) NOT NULL,
  `app_logo` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `auto_rent`
--

CREATE TABLE `auto_rent` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `from_time` time NOT NULL DEFAULT '00:00:00',
  `to_time` time NOT NULL DEFAULT '00:00:00',
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bank_details`
--

CREATE TABLE `bank_details` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_account_number` varchar(255) NOT NULL,
  `bank_routing_number` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_details`
--

INSERT INTO `bank_details` (`id`, `users_id`, `bank_name`, `bank_account_number`, `bank_routing_number`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(25, 62, 'SBI', '9206446466644', 'SBI007', '2017-12-11 07:17:35', '0000-00-00 00:00:00', 62, 0),
(26, 61, 'HDFC', '924464646466464', 'HDFC007', '2017-12-11 07:18:44', '0000-00-00 00:00:00', 61, 0);

-- --------------------------------------------------------

--
-- Table structure for table `bank_receipt`
--

CREATE TABLE `bank_receipt` (
  `id` int(11) NOT NULL,
  `booking_refund_id` int(11) NOT NULL,
  `admin_fund_managment_id` int(11) NOT NULL,
  `uploaded_receipt` varchar(255) NOT NULL,
  `receipt_for` enum('Refund','AdminFund') NOT NULL,
  `created_date` datetime NOT NULL,
  `updated_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `bank_receipt`
--

INSERT INTO `bank_receipt` (`id`, `booking_refund_id`, `admin_fund_managment_id`, `uploaded_receipt`, `receipt_for`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(11, 25, 0, '082539Koala.jpg', 'Refund', '2017-12-11 08:25:39', '0000-00-00 00:00:00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `space_managment_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `entry_date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exit_date_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `total_time` varchar(255) NOT NULL,
  `booking_status` enum('Upcoming','Completed','Cancelled') NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `booking_amount` int(11) NOT NULL,
  `booking_date` date NOT NULL DEFAULT '0000-00-00',
  `booking_time` time NOT NULL DEFAULT '00:00:00',
  `booking_start_date_time` datetime NOT NULL,
  `booking_end_date_time` datetime NOT NULL,
  `generated_booking_id` varchar(50) NOT NULL,
  `booking_hours` int(11) NOT NULL,
  `booking_days` int(11) NOT NULL,
  `booking_month` int(11) NOT NULL,
  `booking_type` enum('Hours','days','Months') NOT NULL,
  `cancelled_by` enum('','User','Host') NOT NULL DEFAULT '',
  `cancellation_fee` int(11) NOT NULL,
  `cancellation_date` datetime NOT NULL,
  `is_additional_credited_amount` enum('Yes','No') NOT NULL DEFAULT 'No',
  `additional_credited_amount` int(11) NOT NULL,
  `paid_amount` int(11) NOT NULL,
  `is_surcharge` enum('Yes','No') NOT NULL DEFAULT 'No',
  `surcharge_amount` int(11) NOT NULL,
  `is_surcharge_paid` enum('Yes','No','') NOT NULL DEFAULT '',
  `surcharge_transaction_id` varchar(255) NOT NULL,
  `booking_transaction_id` varchar(255) NOT NULL,
  `is_delete` enum('Yes','No') NOT NULL DEFAULT 'No',
  `is_delete_reservation` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`id`, `parking_spot_id`, `space_managment_id`, `users_id`, `entry_date_time`, `exit_date_time`, `total_time`, `booking_status`, `status`, `booking_amount`, `booking_date`, `booking_time`, `booking_start_date_time`, `booking_end_date_time`, `generated_booking_id`, `booking_hours`, `booking_days`, `booking_month`, `booking_type`, `cancelled_by`, `cancellation_fee`, `cancellation_date`, `is_additional_credited_amount`, `additional_credited_amount`, `paid_amount`, `is_surcharge`, `surcharge_amount`, `is_surcharge_paid`, `surcharge_transaction_id`, `booking_transaction_id`, `is_delete`, `is_delete_reservation`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(189, 151, 209, 62, '2017-12-11 12:57:11', '2017-12-11 13:51:19', '1 Hours', 'Completed', 'Active', 44, '2017-12-11', '11:46:00', '2017-12-11 11:46:00', '2017-12-11 12:46:00', 'HL6Z90MT8', 1, 0, 0, 'Hours', '', 11, '0000-00-00 00:00:00', 'No', 0, 44, 'No', 0, '', '', 'g21ssz74', 'No', 'No', '2017-12-11 07:18:02', '0000-00-00 00:00:00', 62, 0),
(190, 151, 209, 62, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2 Hours', 'Cancelled', 'Active', 44, '2017-12-12', '13:52:00', '2017-12-12 13:52:00', '2017-12-12 15:52:00', 'DZNMTXO7E', 2, 0, 0, 'Hours', 'User', 22, '2017-12-11 13:53:50', 'No', 0, 88, 'No', 0, '', '', '7m2j2ycm', 'Yes', 'No', '2017-12-11 08:23:14', '0000-00-00 00:00:00', 62, 0),
(191, 151, 209, 62, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1 Hours', 'Cancelled', 'Active', 44, '2017-12-13', '13:58:00', '2017-12-13 13:58:00', '2017-12-13 14:58:00', '4LOQA98Z1', 1, 0, 0, 'Hours', 'User', 11, '2017-12-11 13:58:55', 'No', 0, 44, 'No', 0, '', '', 'm9xba7sz', 'Yes', 'No', '2017-12-11 08:28:42', '0000-00-00 00:00:00', 62, 0),
(192, 151, 209, 62, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2 Hours', 'Cancelled', 'Active', 44, '2017-12-14', '14:04:00', '2017-12-14 14:04:00', '2017-12-14 16:04:00', 'FV9HZL1XQ', 2, 0, 0, 'Hours', 'Host', 22, '2017-12-11 14:05:36', 'No', 0, 88, 'No', 0, '', '', 'gmhg0g9h', 'Yes', 'No', '2017-12-11 08:34:44', '0000-00-00 00:00:00', 62, 0),
(193, 151, 209, 62, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '1 Hours', 'Cancelled', 'Active', 44, '2017-12-15', '14:09:00', '2017-12-15 14:09:00', '2017-12-15 15:09:00', '96CAV7EP8', 1, 0, 0, 'Hours', 'Host', 11, '2017-12-11 14:10:35', 'No', 0, 44, 'No', 0, '', '', 'ekq5dgsv', 'Yes', 'No', '2017-12-11 08:40:16', '0000-00-00 00:00:00', 62, 0),
(194, 151, 209, 62, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '4 Hours', 'Cancelled', 'Active', 44, '2017-12-15', '14:12:00', '2017-12-15 14:12:00', '2017-12-15 18:12:00', 'XV8KWT501', 4, 0, 0, 'Hours', 'User', 44, '2017-12-11 14:13:01', 'No', 0, 176, 'No', 0, '', '', '7qvhtcdh', 'Yes', 'No', '2017-12-11 08:42:43', '0000-00-00 00:00:00', 62, 0);

-- --------------------------------------------------------

--
-- Table structure for table `booking_refund`
--

CREATE TABLE `booking_refund` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `bank_details_id` int(11) NOT NULL,
  `payment_history_id` int(11) NOT NULL DEFAULT '0',
  `refund_amount` int(11) NOT NULL,
  `refund_amount_status` enum('Pending','Funded') NOT NULL,
  `upload_bank_receipt` varchar(255) NOT NULL,
  `upload_bank_receipt_status` enum('Pending','Accepted','Rejected') NOT NULL DEFAULT 'Pending',
  `booking_refund_for` enum('Booking_cancel','Booking_failed') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `booking_refund`
--

INSERT INTO `booking_refund` (`id`, `booking_id`, `users_id`, `bank_details_id`, `payment_history_id`, `refund_amount`, `refund_amount_status`, `upload_bank_receipt`, `upload_bank_receipt_status`, `booking_refund_for`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(25, 190, 62, 0, 0, 66, 'Funded', '', 'Pending', 'Booking_cancel', '2017-12-11 08:23:50', '0000-00-00 00:00:00', 62, 0),
(26, 191, 62, 0, 0, 33, 'Pending', '', 'Pending', 'Booking_cancel', '2017-12-11 08:28:55', '0000-00-00 00:00:00', 62, 0),
(27, 192, 62, 0, 0, 66, 'Pending', '', 'Pending', 'Booking_cancel', '2017-12-11 08:35:36', '0000-00-00 00:00:00', 61, 0),
(28, 193, 62, 0, 0, 44, 'Pending', '', 'Pending', 'Booking_cancel', '2017-12-11 08:40:35', '0000-00-00 00:00:00', 61, 0),
(29, 194, 62, 0, 0, 132, 'Pending', '', 'Pending', 'Booking_cancel', '2017-12-11 08:43:01', '0000-00-00 00:00:00', 62, 0);

-- --------------------------------------------------------

--
-- Table structure for table `contact_us`
--

CREATE TABLE `contact_us` (
  `id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(50) NOT NULL,
  `state` varchar(255) NOT NULL,
  `message_description` text NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `contact_us`
--

INSERT INTO `contact_us` (`id`, `first_name`, `last_name`, `email`, `contact_number`, `state`, `message_description`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 'Mayuri', 'Patel', 'mayuri.patel@trootech.com', '789654123', 'gujarat', 'Test Description', '2017-07-06 03:00:00', '0000-00-00 00:00:00', 1, 0),
(2, 'khushbu', 'Gosai', 'khushbu.gosai@trootech.com', '698532174', 'gujarat', 'sassasa', '2017-07-03 00:00:00', '0000-00-00 00:00:00', 1, 0),
(13, 'hello', 'hello', 'hello@hello.com', '5252552', 'a', 'Gcrxrxre ', '2017-10-27 08:00:52', '0000-00-00 00:00:00', 0, 0),
(14, ' ucycyh h', 'by txt ', 'bhgghh@c.com', '8555555', 'bhgghh', 'Ch h icy', '2017-10-27 11:14:58', '0000-00-00 00:00:00', 0, 0),
(15, 'vvvhnbk', 'ccghn', 'cvgh@chh.com', '0855', 'cc', 'NBC', '2017-10-27 11:47:16', '0000-00-00 00:00:00', 0, 0),
(16, 'Justus', 'Mendez', 'intellihubllc@gmail.com', '2169738799', 'cc', 'Testing', '2017-11-07 20:03:55', '0000-00-00 00:00:00', 0, 0),
(17, 'H', 'B', 'hbc@mailinator.com', '55555545566', '', 'Bfjdjrjrhrjrj', '2017-11-08 09:47:04', '0000-00-00 00:00:00', 0, 0),
(18, 'H', 'B', 'hbc@mailinator.com', '6544313131', '', 'BBC', '2017-11-08 09:52:33', '0000-00-00 00:00:00', 0, 0),
(19, 'H', 'B', 'abc@gmail.com', '5646464646', '', 'Cjcjfjdjdm', '2017-11-08 12:21:24', '0000-00-00 00:00:00', 0, 0),
(20, 'j', 'm', 'JG@gmail.com', '258', '', 'High', '2017-11-08 16:47:28', '0000-00-00 00:00:00', 0, 0),
(21, 'kk', 'patel', 'Kishan.patel@trootech.com', '9723441604', '', 'Spot I need', '2017-11-14 07:47:31', '0000-00-00 00:00:00', 0, 0),
(22, 'Khushi', 'Patel', 'kishan18191@gmail.com', '9723441606', '', 'This is for you', '2017-11-17 12:55:59', '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

CREATE TABLE `country` (
  `id` int(11) NOT NULL,
  `country_name` varchar(255) NOT NULL,
  `country_code` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `country`
--

INSERT INTO `country` (`id`, `country_name`, `country_code`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 'India', 'IN', 'Active', '2017-07-21 00:00:00', '2017-10-09 09:58:54', 1, 1),
(2, 'America', 'US', 'Active', '2017-08-09 11:26:41', '2017-10-27 08:09:44', 1, 1),
(8, 'Japan', 'JP', 'Active', '2017-08-09 11:26:55', '2017-08-09 12:41:04', 1, 1),
(10, 'China', 'CN', 'Inactive', '2017-08-09 12:42:59', '2017-10-26 12:37:40', 1, 1),
(14, 'Germany', 'GR', 'Active', '2017-10-25 13:07:52', '2017-10-25 13:07:52', 1, 0),
(15, 'aa ', 'aa', 'Active', '2017-10-27 08:07:55', '2017-10-27 08:08:16', 1, 1),
(16, 'india_', 'aaaa', 'Inactive', '2017-10-27 12:21:27', '2017-10-27 12:21:27', 1, 0),
(17, '8**//*//', '**/', 'Inactive', '2017-11-13 12:03:34', '2017-11-13 12:03:34', 8, 0);

-- --------------------------------------------------------

--
-- Table structure for table `custom_availability`
--

CREATE TABLE `custom_availability` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `no_of_hour` varchar(255) NOT NULL,
  `amount_of_hour` varchar(255) NOT NULL,
  `no_of_days` varchar(255) NOT NULL,
  `amount_of_days` varchar(255) NOT NULL,
  `month` varchar(255) NOT NULL,
  `amount_of_month` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `device_master`
--

CREATE TABLE `device_master` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `device_token` text NOT NULL,
  `gcm_key` text NOT NULL,
  `is_login` enum('Yes','No') NOT NULL,
  `gcm_arn` text NOT NULL,
  `user_type` enum('android','ios') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `device_master`
--

INSERT INTO `device_master` (`id`, `users_id`, `device_token`, `gcm_key`, `is_login`, `gcm_arn`, `user_type`) VALUES
(66, 61, 'BCD8D5F3-3B10-45EC-B862-4EE9ED11242D', '5188F7E05E7DFA3A63910138E0D60B7CAA759D05DB33CB31F2AAF5757FABBE1E', 'Yes', 'arn:aws:sns:us-east-1:959774175020:endpoint/APNS_SANDBOX/parkit-ios-dev/fe074032-3ec4-342b-98e2-95d9020af1a9', 'ios'),
(67, 62, '1E011CA2-4D0A-49EB-A885-1352E2A5FAE3', '8AB5785040450CE36B653D6A0D443AF6D44DF00EDADBA90B4000138B3872D010', 'Yes', 'arn:aws:sns:us-east-1:959774175020:endpoint/APNS_SANDBOX/parkit-ios-dev/83756211-1c27-303d-baa0-32710c051b03', 'ios');

-- --------------------------------------------------------

--
-- Table structure for table `email_template`
--

CREATE TABLE `email_template` (
  `id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `email_template`
--

INSERT INTO `email_template` (`id`, `subject`, `description`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 'SignUP', '<p>Thanks for signing up on SPOTON............</p>\r\n', 'Active', '2017-06-20 09:16:39', '2017-11-13 11:28:25', 1, 8),
(2, 'Change Password Template', '<p>Change Password Template Description&nbsp; Change Password Template Description Change Password Template Description Change Password Template Description Change Password Template Description</p>\r\n', 'Active', '2017-06-20 09:29:36', '2017-11-14 05:03:32', 1, 8),
(3, 'Send Promocode To Users Mail', '<p>Hello {{USERNAME}}. Welcome To Spoton.</p>\n\n<p>Promocode : {{PROMOCODE}}</p>\n\n<p>Thanks You, Spoton Team!</p>\n', 'Active', '0000-00-00 00:00:00', '2017-07-03 07:10:16', 0, 1),
(4, 'Contact Us Reply to Users ', 'Hello {{Name}}. Welcome To Spoton.\n\nDescription: {{Description}}\n\nThanks You, Spoton Team!', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0),
(5, 'Parking Spot Verification code', '<p>Hello {{USERNAME}}.</p>\r\n\r\n<p>Verification Code: {{VERIFICATIONCODE}}</p>\r\n\r\n<p>Thanks You, Spot on Team!</p>\r\n', 'Active', '2017-07-13 00:00:00', '2017-11-16 05:34:21', 1, 8),
(6, 'Contact Us', '<p>Hello</p>\r\n', 'Active', '2017-07-20 00:00:00', '2017-10-26 12:47:53', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `general_settings`
--

CREATE TABLE `general_settings` (
  `id` int(11) NOT NULL,
  `cancellation_fee` int(11) NOT NULL,
  `commission_amount` int(11) NOT NULL,
  `penalty_amount` varchar(100) NOT NULL,
  `discount_amount` varchar(100) NOT NULL,
  `distance_of_miles` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `general_settings`
--

INSERT INTO `general_settings` (`id`, `cancellation_fee`, `commission_amount`, `penalty_amount`, `discount_amount`, `distance_of_miles`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 25, 25, '10', '25', '100', '2017-09-11 12:34:08', '2017-11-17 18:23:53', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_master`
--

CREATE TABLE `menu_master` (
  `menu_id` int(11) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `action_id` int(11) NOT NULL,
  `sort_order` int(11) NOT NULL,
  `sub_sort_order` int(11) NOT NULL,
  `icon_cis` varchar(200) NOT NULL,
  `show_in_menu` enum('Yes','No') NOT NULL DEFAULT 'Yes',
  `show_in_user_rights` enum('Yes','No') NOT NULL DEFAULT 'Yes'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `menu_master`
--

INSERT INTO `menu_master` (`menu_id`, `parent_id`, `name`, `action_id`, `sort_order`, `sub_sort_order`, `icon_cis`, `show_in_menu`, `show_in_user_rights`) VALUES
(1, 0, 'Dashboard', 1, 1, 0, 'fa fa-home', 'Yes', 'Yes'),
(2, 0, 'Manage AdminUser', 2, 2, 0, 'fa fa-users', 'Yes', 'Yes'),
(3, 0, 'Manage Static Pages', 5, 3, 0, 'fa fa-list', 'Yes', 'Yes'),
(4, 0, 'Manage Email Template', 8, 4, 0, 'fa fa-envelope', 'Yes', 'Yes'),
(5, 0, 'App Settings', 11, 5, 0, 'fa fa-cog', 'No', 'Yes'),
(6, 0, 'Manage Users', 12, 6, 0, 'fa fa-user', 'Yes', 'Yes'),
(7, 0, 'Manage Parking Spot', 17, 7, 0, 'fa fa-product-hunt', 'Yes', 'Yes'),
(8, 0, 'Manage Pricing', 22, 8, 0, 'fa fa-rub', 'Yes', 'Yes'),
(9, 0, 'Surcharge Amount', 27, 9, 0, 'fa fa-list-alt', 'Yes', 'Yes'),
(10, 0, 'General Settings', 28, 10, 0, 'fa fa-cogs', 'Yes', 'Yes'),
(11, 0, 'Manage Review Questionnaire', 29, 11, 0, 'fa fa-question-circle', 'Yes', 'Yes'),
(12, 0, 'Manage Promo Codes', 34, 12, 0, 'fa fa-th-list', 'Yes', 'Yes'),
(13, 0, 'Manage Contact Us', 39, 13, 0, 'fa fa-phone', 'Yes', 'Yes'),
(14, 0, 'Manage Admin Notification', 42, 14, 0, 'fa fa-bell', 'No', 'Yes'),
(15, 0, 'Manage Admin Country', 45, 15, 0, 'fa fa-flag', 'Yes', 'Yes'),
(16, 0, 'Manage Admin State', 46, 16, 0, 'fa fa-flag-o', 'Yes', 'Yes'),
(17, 0, 'Manage Admin Booking', 47, 17, 0, 'fa fa-book', 'Yes', 'Yes'),
(18, 0, 'Manage User Notification', 48, 14, 0, 'fa fa-bell-o', 'Yes', 'Yes'),
(19, 0, 'Manage Monthly Reports', 49, 18, 0, 'fa fa-flag', 'Yes', 'Yes'),
(20, 0, 'Manage Refund', 50, 19, 0, 'fa fa-money', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `push_notification_id` int(20) DEFAULT NULL,
  `notification_mode` enum('ON','OFF') NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `users_id`, `push_notification_id`, `notification_mode`, `title`, `description`, `created_date`, `updated_date`, `created_by`) VALUES
(525, 40, 1, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(526, 40, 2, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(527, 40, 3, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(528, 40, 4, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(529, 40, 5, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(530, 40, 6, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(531, 40, 7, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(532, 40, 8, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(533, 40, 9, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(534, 40, 10, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(535, 40, 11, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(536, 40, 12, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(537, 40, 13, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(538, 40, 15, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(539, 40, 16, 'ON', '', '', '2017-11-01 14:21:17', '2017-11-01 14:21:17', 0),
(540, 41, 1, 'ON', '', '', '2017-11-01 14:43:51', '2017-12-07 08:35:29', 0),
(541, 41, 2, 'ON', '', '', '2017-11-01 14:43:51', '2017-12-07 08:35:30', 0),
(542, 41, 3, 'ON', '', '', '2017-11-01 14:43:51', '2017-12-07 08:35:32', 0),
(543, 41, 4, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(544, 41, 5, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(545, 41, 6, 'ON', '', '', '2017-11-01 14:43:51', '2017-12-07 08:35:38', 0),
(546, 41, 7, 'ON', '', '', '2017-11-01 14:43:51', '2017-12-07 08:35:40', 0),
(547, 41, 8, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(548, 41, 9, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(549, 41, 10, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(550, 41, 11, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(551, 41, 12, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(552, 41, 13, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(553, 41, 15, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(554, 41, 16, 'ON', '', '', '2017-11-01 14:43:51', '2017-11-01 14:43:51', 0),
(555, 42, 1, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-06 12:38:18', 0),
(556, 42, 2, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(557, 42, 3, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(558, 42, 4, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(559, 42, 5, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(560, 42, 6, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(561, 42, 7, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(562, 42, 8, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(563, 42, 9, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(564, 42, 10, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(565, 42, 11, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(566, 42, 12, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(567, 42, 13, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(568, 42, 15, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(569, 42, 16, 'ON', '', '', '2017-11-01 14:47:41', '2017-11-01 14:47:41', 0),
(570, 43, 1, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(571, 43, 2, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(572, 43, 3, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(573, 43, 4, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(574, 43, 5, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(575, 43, 6, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(576, 43, 7, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(577, 43, 8, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(578, 43, 9, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(579, 43, 10, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(580, 43, 11, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(581, 43, 12, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(582, 43, 13, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(583, 43, 15, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(584, 43, 16, 'ON', '', '', '2017-11-01 18:13:17', '2017-11-01 18:13:17', 0),
(585, 44, 1, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(586, 44, 2, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(587, 44, 3, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(588, 44, 4, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(589, 44, 5, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(590, 44, 6, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(591, 44, 7, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(592, 44, 8, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(593, 44, 9, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(594, 44, 10, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(595, 44, 11, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(596, 44, 12, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(597, 44, 13, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(598, 44, 15, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(599, 44, 16, 'ON', '', '', '2017-11-01 18:24:11', '2017-11-01 18:24:11', 0),
(600, 45, 1, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(601, 45, 2, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(602, 45, 3, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(603, 45, 4, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(604, 45, 5, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(605, 45, 6, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(606, 45, 7, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(607, 45, 8, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(608, 45, 9, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(609, 45, 10, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(610, 45, 11, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(611, 45, 12, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(612, 45, 13, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(613, 45, 15, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(614, 45, 16, 'ON', '', '', '2017-11-01 23:45:51', '2017-11-01 23:45:51', 0),
(615, 46, 1, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(616, 46, 2, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(617, 46, 3, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(618, 46, 4, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(619, 46, 5, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(620, 46, 6, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(621, 46, 7, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(622, 46, 8, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(623, 46, 9, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(624, 46, 10, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(625, 46, 11, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(626, 46, 12, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(627, 46, 13, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(628, 46, 15, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(629, 46, 16, 'ON', '', '', '2017-11-03 11:22:37', '2017-11-03 11:22:37', 0),
(630, 47, 1, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(631, 47, 2, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(632, 47, 3, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(633, 47, 4, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(634, 47, 5, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(635, 47, 6, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(636, 47, 7, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(637, 47, 8, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(638, 47, 9, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(639, 47, 10, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(640, 47, 11, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(641, 47, 12, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(642, 47, 13, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(643, 47, 15, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(644, 47, 16, 'ON', '', '', '2017-11-03 14:47:10', '2017-11-03 14:47:10', 0),
(645, 48, 1, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(646, 48, 2, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(647, 48, 3, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(648, 48, 4, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(649, 48, 5, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(650, 48, 6, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(651, 48, 7, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(652, 48, 8, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(653, 48, 9, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(654, 48, 10, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(655, 48, 11, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(656, 48, 12, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(657, 48, 13, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(658, 48, 15, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(659, 48, 16, 'ON', '', '', '2017-11-03 17:44:36', '2017-11-03 17:44:36', 0),
(660, 49, 1, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(661, 49, 2, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(662, 49, 3, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(663, 49, 4, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(664, 49, 5, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(665, 49, 6, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(666, 49, 7, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(667, 49, 8, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(668, 49, 9, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(669, 49, 10, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(670, 49, 11, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(671, 49, 12, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(672, 49, 13, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(673, 49, 15, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(674, 49, 16, 'ON', '', '', '2017-11-07 19:31:12', '2017-11-07 19:31:12', 0),
(675, 50, 1, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(676, 50, 2, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(677, 50, 3, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(678, 50, 4, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(679, 50, 5, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(680, 50, 6, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(681, 50, 7, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(682, 50, 8, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(683, 50, 9, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(684, 50, 10, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(685, 50, 11, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(686, 50, 12, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(687, 50, 13, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(688, 50, 15, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(689, 50, 16, 'ON', '', '', '2017-11-07 19:36:24', '2017-11-07 19:36:24', 0),
(690, 51, 1, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(691, 51, 2, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(692, 51, 3, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(693, 51, 4, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(694, 51, 5, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(695, 51, 6, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(696, 51, 7, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(697, 51, 8, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(698, 51, 9, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(699, 51, 10, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(700, 51, 11, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(701, 51, 12, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(702, 51, 13, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(703, 51, 15, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(704, 51, 16, 'ON', '', '', '2017-11-14 06:24:24', '2017-11-14 06:24:24', 0),
(705, 52, 1, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(706, 52, 2, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(707, 52, 3, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(708, 52, 4, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(709, 52, 5, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(710, 52, 6, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(711, 52, 7, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(712, 52, 8, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(713, 52, 9, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(714, 52, 10, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(715, 52, 11, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(716, 52, 12, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(717, 52, 13, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(718, 52, 15, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(719, 52, 16, 'ON', '', '', '2017-11-14 06:29:15', '2017-11-14 06:29:15', 0),
(720, 53, 1, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(721, 53, 2, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(722, 53, 3, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(723, 53, 4, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-18 10:54:38', 0),
(724, 53, 5, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(725, 53, 6, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(726, 53, 7, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(727, 53, 8, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(728, 53, 9, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(729, 53, 10, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(730, 53, 11, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(731, 53, 12, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(732, 53, 13, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(733, 53, 15, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(734, 53, 16, 'ON', '', '', '2017-11-14 07:03:47', '2017-11-14 07:03:47', 0),
(735, 54, 1, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(736, 54, 2, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(737, 54, 3, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(738, 54, 4, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(739, 54, 5, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(740, 54, 6, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(741, 54, 7, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(742, 54, 8, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(743, 54, 9, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(744, 54, 10, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(745, 54, 11, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(746, 54, 12, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(747, 54, 13, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(748, 54, 15, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(749, 54, 16, 'ON', '', '', '2017-11-14 09:20:37', '2017-11-14 09:20:37', 0),
(750, 55, 1, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(751, 55, 2, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(752, 55, 3, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(753, 55, 4, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(754, 55, 5, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(755, 55, 6, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(756, 55, 7, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(757, 55, 8, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(758, 55, 9, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(759, 55, 10, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(760, 55, 11, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(761, 55, 12, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(762, 55, 13, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(763, 55, 15, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(764, 55, 16, 'ON', '', '', '2017-11-15 03:26:53', '2017-11-15 03:26:53', 0),
(765, 56, 1, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(766, 56, 2, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(767, 56, 3, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(768, 56, 4, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(769, 56, 5, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(770, 56, 6, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(771, 56, 7, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(772, 56, 8, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(773, 56, 9, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(774, 56, 10, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(775, 56, 11, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(776, 56, 12, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(777, 56, 13, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(778, 56, 15, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(779, 56, 16, 'ON', '', '', '2017-11-15 03:37:24', '2017-11-15 03:37:24', 0),
(780, 57, 1, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(781, 57, 2, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(782, 57, 3, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(783, 57, 4, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(784, 57, 5, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(785, 57, 6, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(786, 57, 7, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(787, 57, 8, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(788, 57, 9, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(789, 57, 10, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(790, 57, 11, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(791, 57, 12, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(792, 57, 13, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(793, 57, 15, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(794, 57, 16, 'ON', '', '', '2017-11-15 11:55:47', '2017-11-15 11:55:47', 0),
(795, 58, 1, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(796, 58, 2, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(797, 58, 3, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(798, 58, 4, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(799, 58, 5, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(800, 58, 6, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(801, 58, 7, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(802, 58, 8, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(803, 58, 9, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(804, 58, 10, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(805, 58, 11, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(806, 58, 12, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(807, 58, 13, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(808, 58, 15, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(809, 58, 16, 'ON', '', '', '2017-11-15 14:18:10', '2017-11-15 14:18:10', 0),
(810, 59, 1, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(811, 59, 2, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(812, 59, 3, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(813, 59, 4, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(814, 59, 5, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(815, 59, 6, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(816, 59, 7, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(817, 59, 8, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(818, 59, 9, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(819, 59, 10, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(820, 59, 11, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(821, 59, 12, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(822, 59, 13, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(823, 59, 15, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(824, 59, 16, 'ON', '', '', '2017-11-15 21:11:42', '2017-11-15 21:11:42', 0),
(825, 60, 1, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(826, 60, 2, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(827, 60, 3, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(828, 60, 4, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(829, 60, 5, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(830, 60, 6, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(831, 60, 7, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(832, 60, 8, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(833, 60, 9, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(834, 60, 10, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(835, 60, 11, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(836, 60, 12, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(837, 60, 13, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(838, 60, 15, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(839, 60, 16, 'ON', '', '', '2017-11-29 15:01:29', '2017-11-29 15:01:29', 0),
(840, 61, 1, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(841, 61, 2, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(842, 61, 3, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(843, 61, 4, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(844, 61, 5, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(845, 61, 6, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(846, 61, 7, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(847, 61, 8, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(848, 61, 9, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(849, 61, 10, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(850, 61, 11, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(851, 61, 12, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(852, 61, 13, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(853, 61, 15, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(854, 61, 16, 'ON', '', '', '2017-12-11 06:54:31', '2017-12-11 06:54:31', 0),
(855, 62, 1, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(856, 62, 2, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(857, 62, 3, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(858, 62, 4, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(859, 62, 5, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(860, 62, 6, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(861, 62, 7, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(862, 62, 8, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(863, 62, 9, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(864, 62, 10, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(865, 62, 11, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(866, 62, 12, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(867, 62, 13, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(868, 62, 15, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0),
(869, 62, 16, 'ON', '', '', '2017-12-11 07:16:29', '2017-12-11 07:16:29', 0);

-- --------------------------------------------------------

--
-- Table structure for table `page_content`
--

CREATE TABLE `page_content` (
  `id` int(11) NOT NULL,
  `page_name` varchar(255) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_description` text NOT NULL,
  `meta_title` varchar(255) NOT NULL,
  `meta_keyword` varchar(255) NOT NULL,
  `meta_description` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `page_content`
--

INSERT INTO `page_content` (`id`, `page_name`, `page_title`, `page_description`, `meta_title`, `meta_keyword`, `meta_description`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 'About Us', 'ABOUT US', '<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'About Us Meta title', 'about_us', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s', 'Active', '2017-06-17 09:41:17', '2017-10-26 12:47:23', 1, 1),
(2, 'How It Works', 'HOW IT WORKS', '<p>&quot;How It Works&quot; - Qu is a platform that allows people (Hosts) with extra parking spaces at their house or apartment, the opportunity to rent it out for a small fee. Other people (Renters) can rent those extra parking spaces quickly and conveniently from anywhere in the world. Qu opens up unique parking opportunities, as well as gets rid of the need for more parking garages.</p>\r\n', 'how it works title', 'how_it_works', ' - Qu is a platform that allows people (Hosts) with extra parking spaces at their house or apartment, the opportunity to rent it out for a small fee.', 'Active', '2017-07-19 00:00:00', '2017-11-15 10:13:17', 1, 1);
INSERT INTO `page_content` (`id`, `page_name`, `page_title`, `page_description`, `meta_title`, `meta_keyword`, `meta_description`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(3, 'Terms and Conditions', 'TERMS & CONDITIONS', '<p><strong>ority.</strong></p>\r\n\r\n<ul>\r\n	<li>\r\n	<ul>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>Miscellaneous Occupancy Tax Provisions</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>Whether you are a Guest or Host, you agree that any claim or cause of action relating to Qu&rsquo;s facilitation of Opt-in for Host Remittance or Collection and Remittance of Occupancy Taxes shall not extend to any supplier or vendor that may be used by Qu in connection with facilitation of Opt-in Remittance or Collection and Remittance of Occupancy Taxes, if any. Guests and Hosts agree that we may seek additional amounts from You in the event that the Taxes collected and/or remitted are insufficient to fully discharge your obligations to the Tax Authority, and agree that your sole remedy for Occupancy Taxes collected is a refund of Occupancy Taxes collected by Qu from the applicable Tax Authority in accordance with applicable procedures set by that Tax Authority.</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>In any jurisdiction in which we have not provided notice of, or are not facilitating (or are no longer facilitating) the collection or remittance of Occupancy Taxes by Collection and Remittance, Opt-in for Host Remittance or any other means or method, in your jurisdiction, Hosts and Guests remain solely responsible and liable for the collection and/or remittance of any and all Occupancy Taxes that may apply to Accommodations.</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>Hosts and Guests acknowledge and agree that in some jurisdictions, Qu may decide not to facilitate collection or remittance of Occupancy Taxes or may not be able to facilitate the collection and/or remittance of Occupancy Taxes, and nothing contained in these Terms of Service is a representation or guarantee that Qu will facilitate collection and/or remittance of Occupancy Tax anywhere at all, including in any specific jurisdiction, or that Qu will continue to facilitate any collection or remittance of Occupancy Tax in any specific jurisdiction in which it may have been offered. Qu reserves the right, in its sole determination, to cease any facilitation of any collection and remittance of Occupancy Tax (regardless of method used or to be used in the future) for any reason or no reason at all, provided that it will give Hosts reasonable notice in any jurisdiction in which Qu determines to cease any such facilitation.</strong></p>\r\n		</li>\r\n	</ul>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Currency Conversion</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Qu&rsquo;s online platform facilitates Bookings between Guests and Hosts who may pay in a currency different from their destination currency, which may require currency conversions to accommodate these differing currency preferences. Although the Qu platform allows users to view the price of Listings in a number of currencies, the currencies available for users to make and receive payments may be limited, and may not include the default currency in any given geographic location.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Details regarding currency conversion, including any associated fees, are detailed in the <a href="https://www.airbnb.com/terms/payments_terms">Payments Terms</a>.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Damage to Accommodations and Security Deposits</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>As a Guest, you are responsible for leaving the Accommodation (including any personal or other property located at an Accommodation) in the condition it was in when you arrived. You acknowledge and agree that, as a Guest, you are responsible for your own acts and omissions and are also responsible for the acts and omissions of any individuals whom you invite to, or otherwise provide access to, the Accommodation. In the event that a Host claims otherwise and provides evidence of damage (&ldquo;Damage Claim&ldquo;), including but not limited to photographs, you agree to pay the cost of replacing the damaged items with equivalent items.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Hosts may choose to include security deposits in their Listings (&ldquo;Security Deposits&ldquo;). Each Listing will describe whether a Security Deposit is required for the applicable Accommodation. Qu will use commercially reasonable efforts to address Hosts&rsquo; requests and claims related to Security Deposits, but Qu is not responsible for administering or accepting any Damage Claims by Hosts related to Security Deposits, and disclaims any and all liability in this regard.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>If a Host has a Damage Claim for a confirmed Booking, the Host can seek payment from the Guest through the Resolution Center. The Host may escalate the Damage Claim to Qu if the Host and Guest are unable to resolve a Damage Claim through the Resolution Center, or immediately in certain circumstances. If a Host escalates a Damage Claim to Qu, you as a Guest will be notified of the Damage Claim and given an opportunity to respond. If you as a Guest agree to pay the Host in connection with a Damage Claim, or if Qu determines, in its sole discretion, that you are responsible for damaging an Accommodation or any personal or other property located at an Accommodation, Qu (via Qu Payments) will collect any such costs from you and/or against the Security Deposit in accordance with the <a href="https://www.airbnb.com/terms/payments_terms">Payments Terms</a>. Qu also reserves the right to otherwise collect payment from you and pursue any avenues available to Qu in this regard in situations in which you have been determined, in Qu&rsquo;s sole discretion, to have damaged any Accommodation or any personal or other property located at an Accommodation.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Both Guests and Hosts agree to cooperate with and assist Qu in good faith, and to provide Qu with such information and take such actions as may be reasonably requested by Qu, in connection with any Damage Claims or other complaints or claims made by Members relating to Accommodations or any personal or other property located at an Accommodation (including, without limitation, payment requests made under the Qu Host Guarantee) or with respect to any investigation undertaken by Qu or a representative of Qu regarding use or abuse of the Site, Application or the Services. If you are a Guest, upon Qu&rsquo;s reasonable request, and to the extent you are reasonably able to do so, you agree to participate in mediation or similar resolution process with a Host, at no cost to you, which process will be conducted by Qu or a third party selected by Qu or its insurer, with respect to losses for which the Host is requesting payment from Qu under the Qu Host Guarantee.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>If you are a Guest, you understand and agree that Qu may make a claim under your homeowner&rsquo;s, renter&rsquo;s or other insurance policy related to any damage or loss that you may have caused or been responsible for or to an Accommodation or any personal or other property located at an Accommodation (including without limitation amounts paid by Qu under the Qu Host Guarantee). You agree to cooperate with and assist Qu in good faith, and to provide Qu with such information as may be reasonably requested by Qu, in order to make a claim under your homeowner&rsquo;s, renter&rsquo;s or other insurance policy, including, but not limited to, executing documents and taking such further acts as Qu may reasonably request to assist Qu in accomplishing the foregoing.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Security Deposits, if required by a Host, may be applied to any fees due from a Guest overstaying at a Listing without the Host&rsquo;s consent.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Overstaying without the Host&rsquo;s Consent</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Guests agree that a confirmed Booking is merely a license granted by the Host to the Guest to enter and use the Listing for the limited duration of the confirmed Booking and in accordance with the Guest&rsquo;s agreement with the Host. Guests further agree to leave the Accommodation no later than the checkout time that the Host specifies in the Listing or such other time as mutually agreed upon between the Host and Guest. If a Guest stays past the agreed upon checkout time without the Host&rsquo;s consent, they no longer have a license to stay in the Listing and the Host is entitled to make the Guest leave. In addition, Guests agree that the Host can charge the Guest, for each 1 hour period that the Guest stays over the agreed period without the Host&rsquo;s consent, an additional nightly fee of two times the average nightly Accommodation Fee originally paid by the Guest to cover the inconvenience suffered by the Host, plus all applicable Service Fees, Taxes, and any legal expenses incurred by the Host to make the Guest leave (collectively, &ldquo;Additional Sums&ldquo;). Qu Payments will collect Additional Sums from Guests pursuant to the <a href="https://www.airbnb.com/terms/payments_terms">Payments Terms</a>.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>User Conduct</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You understand and agree that you are solely responsible for compliance with any and all laws, rules, regulations, and Tax obligations that may apply to your use of the Site, Application, Services and Collective Content. In connection with your use of the Site, Application, Services and Collective Content, you may not and you agree that you will not:</strong></p>\r\n\r\n	<ul>\r\n		<li>\r\n		<p><strong>violate any local, state, provincial, national, or other law or regulation, or any order of a court, including, without limitation, zoning restrictions and Tax regulations;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use manual or automated software, devices, scripts, robots, backdoors or other means or processes to access, &ldquo;scrape,&rdquo; &ldquo;crawl&rdquo; or &ldquo;spider&rdquo; any web pages or other services contained in the Site, Application, Services or Collective Content;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>access or use our Site, Application, Services or the Qu API to use, expose, or allow to be used or exposed, any Qu Content: (i) that is not publicly displayed by Qu in its search results pages or listing pages before a Booking is confirmed; (ii) in any way that is inconsistent with the Qu Privacy Policy or Terms of Service; or (iii) in any way that otherwise violates the privacy rights or any other rights of Qu&rsquo;s users or any other third party;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use the Site, Application, Services or Collective Content for any commercial or other purposes that are not expressly permitted by these Terms or in a manner that falsely implies Qu endorsement, partnership or otherwise misleads others as to your affiliation with Qu;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>dilute, tarnish or otherwise harm the Qu brand in any way, </strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>including through unauthorized use of Collective Content, </strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>registering and/or using Qu or derivative terms in domain names, trade names, trademarks or other source identifiers, or registering and/or using domains names, trade names, trademarks or other source identifiers that closely imitate or are confusingly similar to Qu domains, trademarks, taglines, promotional campaigns or Collective Content</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>copy, store or otherwise access or use any information contained on the Site, Application, Services or Collective Content for purposes not expressly permitted by these Terms;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>infringe the rights of Qu or the rights of any other person or entity, including without limitation, their intellectual property, privacy, publicity or contractual right</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>interfere with or damage our Site, Application or Services, including, without limitation, through the use of viruses, cancel bots, Trojan horses, harmful code, flood pings, denial-of-service attacks, backdoors, packet or IP spoofing, forged routing or electronic mail address information or similar methods or technology;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use our Site, Application or Services to transmit, distribute, post or submit any information concerning any other person or entity, including without limitation, photographs of others without their permission, personal contact information or credit, debit, calling card or account numbers;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use our Site, Application, Services or Collective Content in connection with the distribution of unsolicited commercial email (&ldquo;spam&rdquo;) or advertisements unrelated to lodging in a private residence;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>&ldquo;stalk&rdquo; or harass any other user of our Site, Application, Services or Collective Content, or collect or store any personally identifiable information about any other user other than for purposes of transacting as an Qu Guest or Host;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>offer, as a Host, any Accommodation that you do not yourself own or have permission to Book as a residential or other property (without limiting the foregoing, you will not list Accommodations as a Host if you are serving in the capacity of an agent for a third party);</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>offer, as a Host, any Accommodation that may not be Booked pursuant to the terms and conditions of an agreement with a third party;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>register for more than one Qu Account or register for an Qu Account on behalf of an individual other than yourself;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>unless Qu explicitly permits otherwise, request or book a stay at any Accommodation if you will not actually be staying at the Accommodation yourself;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>contact another Member for any purpose other than asking a question related to a Booking, Accommodation, Listing, or the Member&rsquo;s use of the Site, Application and Services;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>recruit or otherwise solicit any Host or other Member to join third-party services or websites that are competitive to Qu, without Qu&rsquo;s prior written approval;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>recruit or otherwise solicit any Member to join third-party services, applications or websites, without our prior written approval;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>impersonate any person or entity, or falsify or otherwise misrepresent yourself or your affiliation with any person or entity;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use automated scripts to collect information from or otherwise interact with the Site, Application, Services or Collective Content;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use the Site, Application, Services or Collective Content to find a Host or Guest and then complete a Booking of an Accommodation independent of the Site, Application or Services, in order to circumvent the obligation to pay any Service Fees related to Qu&rsquo;s provision of the Services or for any other reasons;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>as a Host, submit any Listing with false or misleading information, including price information, or submit any Listing with a price that you do not intend to honor;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>violate these Terms or Qu&rsquo;s then-current <a href="https://www.airbnb.com/help/policies">Policies and Community Guidelines</a> or <a href="https://www.airbnb.com/standards">Standards</a>;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>engage in disruptive, circumventive, abusive or harassing behavior in any area or aspect of our Platform, Application, or Services;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>post, upload, publish, submit or transmit any Content that: (i) infringes, misappropriates or violates a third party&rsquo;s patent, copyright, trademark, trade secret, moral rights or other intellectual property rights, or rights of publicity or privacy; (ii) violates, or encourages any conduct that would violate, any applicable law or regulation or would give rise to civil liability; (iii) is fraudulent, false, misleading (directly or by omission or failure to update information) or deceptive; (iv) is defamatory, obscene, pornographic, vulgar or offensive; (v) promotes discrimination, bigotry, racism, hatred, harassment or harm against any individual or group; (vi) is violent or threatening or promotes violence or actions that are threatening to any other person; or (vii) promotes illegal or harmful activities or substances;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>systematically retrieve data or other content from our Site, Application or Services to create or compile, directly or indirectly, in single or multiple downloads, a collection, compilation, database, directory or the like, whether by manual methods, through the use of bots, crawlers, or spiders, or otherwise;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>use, display, mirror or frame the Site, Application, Services or Collective Content, or any individual element within the Site, Application, Services or Collective Content, Qu&rsquo;s name, any Qu trademark, logo or other proprietary information, or the layout and design of any page or form contained on a page in the Site, Application or Services, without Qu&rsquo;s express written consent;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>access, tamper with, or use non-public areas of the Site, Application or Services, Qu&rsquo;s computer systems, or the technical delivery systems of Qu&rsquo;s providers;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>attempt to probe, scan, or test the vulnerability of any Qu system or network or breach any security or authentication measures;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>avoid, bypass, remove, deactivate, impair, descramble, or otherwise circumvent any technological measure implemented by Qu or any of Qu&rsquo;s providers or any other third party (including another user) to protect the Site, Services, Application or Collective Content;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>forge any TCP/IP packet header or any part of the header information in any email or newsgroup posting, or in any way use the Site, Services, Application or Collective Content to send altered, deceptive or false source-identifying information;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>attempt to decipher, decompile, disassemble or reverse engineer any of the software used to provide the Site, Services, Application or Collective Content;</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>advocate, encourage, or assist any third party in doing any of the foregoing; or</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>accept or make a payment for Accommodation Fees outside Qu Payments. If you do so, you acknowledge and agree that you: (i) would be in breach of these Terms; (ii) accept all risks and responsibility for such payment, and (iii) hold Qu harmless from any liability for such payment.</strong></p>\r\n		</li>\r\n	</ul>\r\n	</li>\r\n	<li>\r\n	<p><strong>Qu has the right to investigate and prosecute violations of any of the above to the fullest extent of the law. In addition, and as set in these Terms, Qu may take a range of actions against you, including but not limited to removing or disabling access to any or all of your Member Content or deactivating or canceling your Listing(s) or Qu Account, for a violation of this Section or these Terms.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Qu may access, preserve and disclose any of your information if we are required to do so by law, or if we believe in good faith that it is reasonably necessary to (i) respond to claims asserted against Qu or to comply with legal process (for example, subpoenas or warrants), (ii) enforce or administer our agreements with users, such as these Terms and the <a href="https://www.airbnb.com/terms/host_guarantee">Qu Host Guarantee</a>, (iii) for fraud prevention, risk assessment, investigation, customer support, product development and de-bugging purposes, or (iv) protect the rights, property or safety of Qu, its users, or members of the public. You acknowledge that Qu has no obligation to monitor your access to or use of the Site, Application, Services or Collective Content or to review, remove, disable access to or edit any Member Content, but has the right to do so for the purpose of operating and improving the Site, Application and Services (including without limitation for fraud prevention, risk assessment, investigation and customer support purposes), to ensure your compliance with these Terms, to comply with applicable law or the order or requirement of a court, administrative agency or other governmental body, to respond to content that it determines is otherwise objectionable or as set forth in these Terms. Qu reserves the right, at any time and without prior notice, to remove or disable access to any Collective Content that Qu, at its sole discretion, considers to be objectionable for any reason, in violation of these Terms or otherwise harmful to the Site, Application or Services.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Reporting Misconduct</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>If you stay with or host anyone who you feel is acting or has acted inappropriately, including but not limited to anyone who (i) engages in offensive, violent or sexually inappropriate behavior, (ii) you suspect of stealing from you, or (iii) engages in any other disturbing conduct, you should immediately report such person to the appropriate authorities and then to Qu by contacting us with your police station and report number; provided that your report will not obligate us to take any action beyond that required by law (if any) or cause us to incur any liability to you.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Privacy</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You agree that Qu&rsquo;s <a href="https://www.airbnb.com/terms/privacy_policy">Privacy Policy</a> (as may be updated from time to time) governs Qu&rsquo;s collection and use of your personal information.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Intellectual Property Ownership and Rights Notices</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The Site, Application, Services, and Collective Content are protected by copyright, trademark, and other laws of the United States and foreign countries. You acknowledge and agree that the Site, Application, Services and Collective Content, including all associated intellectual property rights, are the exclusive property of Qu and its licensors. You will not remove, alter or obscure any copyright, trademark, service mark or other proprietary rights notices incorporated in or accompanying the Site, Application, Services, or Collective Content. All trademarks, service marks, logos, trade names, and any other proprietary designations of Qu used on or in connection with the Site, Application, Services, and Qu Content are trademarks or registered trademarks of Qu in the US and abroad. Trademarks, service marks, logos, trade names and any other proprietary designations of third parties used on or in connection with the Site, Application, Services, and Qu Content are used for identification purposes only and may be the property of their respective owners. As a Host, Guest, or Member, you understand and agree that you are bound by the additional Terms, Guidelines and Policies that apply to your use of the Site, Application, Services and Collective Content, including Qu&rsquo;s <a href="https://www.airbnb.com/terms/trademark_and_branding">Trademark &amp; Branding Guidelines</a> (as may be updated from time to time).</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Additional Terms</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Our Site, Application and Services have different products, features and offerings, so sometimes additional terms or product requirements may apply to your use of those products, features or offerings. For example, additional terms apply if you refer new users to Qu (&ldquo;Referral Program&ldquo;) or participate in our <a href="https://www.airbnb.com/help/article/1128/home-safety-terms-and-conditions">Home Safety program</a>. If additional terms are available for the relevant product or Services you use, those additional terms become part of these Terms.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Application License</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Subject to your compliance with these Terms, Qu grants you a limited non-exclusive, non-transferable license to download and install a copy of the Application on each mobile device or computer that you own or control and run such copy of the Application solely for your own personal use. Furthermore, with respect to any Apple App Store Sourced Application (defined below), you will only use the App Store Sourced Application (i) on an Apple-branded product that runs the iOS (Apple&rsquo;s proprietary operating system) and (ii) as permitted by the &ldquo;Usage Rules&rdquo; set forth in the Apple App Store Terms of Service. Qu reserves all rights in the Application not expressly granted to you by these Terms.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Qu Content and Member Content License</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Subject to your compliance with these Terms and Qu&rsquo;s <a href="https://www.airbnb.com/terms/trademark_and_branding">Trademark &amp; Branding Guidelines</a>, Qu grants you a limited, non-exclusive, non-transferable license, to (i) access and view any Qu Content solely for your personal and non-commercial purposes and (ii) access and view any Member Content to which you are permitted access, solely for your personal and non-commercial purposes. You have no right to sublicense the license rights granted in this section.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You will not use, copy, adapt, modify, prepare derivative works based upon, distribute, license, sell, transfer, publicly display, publicly perform, transmit, broadcast or otherwise exploit the Site, Application, Services, or Collective Content, except as expressly permitted in these Terms. No licenses or rights are granted to you by implication or otherwise under any intellectual property rights owned or controlled by Qu or its licensors, except for the licenses and rights expressly granted in these Terms.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Member Content</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>We may, in our sole discretion, permit you to post, upload, publish, submit or transmit Member Content. By making available any Member Content on or through the Site, Application, Services, or through Qu promotional campaigns, you hereby grant to Qu a worldwide, irrevocable, perpetual (or for the term of the protection), non-exclusive, transferable, royalty-free license, with the right to sublicense, to use, view, copy, adapt, translate, modify, distribute, license, sell, transfer, publicly display, publicly perform, transmit, stream, broadcast, access, view, and otherwise exploit such Member Content on, through, by means of or to promote or market the Site, Application and Services. Qu does not claim any ownership rights in any such Member Content and nothing in these Terms will be deemed to restrict any rights that you may have to use and exploit any such Member Content.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You acknowledge and agree that you are solely responsible for all Member Content that you make available through the Site, Application, Services or through Qu promotional campaigns. Accordingly, you represent and warrant that: (i) you either are the sole and exclusive owner of all Member Content that you make available through the Site, Application, Services or through Qu promotional campaigns or you have all rights, licenses, consents and releases that are necessary to grant to Qu the rights in such Member Content, as contemplated under these Terms; and (ii) neither the Member Content nor your posting, uploading, publication, submission or transmittal of the Member Content or Qu&rsquo;s use of the Member Content (or any portion thereof) on, through or by means of the Site, Application, the Services or Qu promotional campaigns will infringe, misappropriate or violate a third party&rsquo;s patent, copyright, trademark, trade secret, moral rights or other proprietary or intellectual property rights, or rights of publicity or privacy, or result in the violation of any applicable law or regulation.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Links</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The Site, Application and Services may contain links to third-party websites or resources. You acknowledge and agree that Qu is not responsible or liable for: (i) the availability or accuracy of such websites or resources; or (ii) the content, products, or services on or available from such websites or resources. Links to such websites or resources do not imply any endorsement by Qu of such websites or resources or the content, products, or services available from such websites or resources. You acknowledge sole responsibility for and assume all risk arising from your use of any such websites or resources or the Content, products or services on or available from such websites or resources.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Some portions of the Qu platform implement Google Maps/Earth mapping services, including Google Maps API(s). Your use of Google Maps/Earth is subject to <a href="http://www.google.com/intl/en_us/help/terms_maps.html">Google&rsquo;s terms of use</a>.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Feedback</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>We welcome and encourage you to provide feedback, comments and suggestions for improvements to the Site, Application and Services (&ldquo;Feedback&ldquo;). You may submit Feedback by emailing us, through the &ldquo;<a href="https://www.airbnb.com/help/contact_us">Contact</a>&rdquo; section of the Site and Application, or by other means of communication. You acknowledge and agree that all Feedback you give us will be the sole and exclusive property of Qu and you hereby irrevocably assign to Qu and agree to irrevocably assign to Qu all of your right, title, and interest in and to all Feedback, including without limitation all worldwide patent, copyright, trade secret, moral and other proprietary or intellectual property rights therein, and waive any moral rights you may have in such Feedback. At Qu&rsquo;s request and expense, you will execute documents and take such further acts as Qu may reasonably request to assist Qu to acquire, perfect, and maintain its intellectual property rights and other legal protections for the Feedback.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Copyright Policy</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Qu respects copyright law and expects its users to do the same. It is Qu&rsquo;s policy to terminate in appropriate circumstances the Qu Accounts of Members or other account holders who repeatedly infringe or are believed to be repeatedly infringing the rights of copyright holders. Please see Qu&rsquo;s <a href="https://www.airbnb.com/terms/copyright_policy">Copyright Policy</a> for further information.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Term and Termination, Suspension and Other Measures</strong></p>\r\n\r\n	<ul>\r\n		<li>\r\n		<p><strong>Term</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>These Terms shall be effective for a 30-day term, at the end of which it will automatically and continuously renew for subsequent 30-day terms until such time when you or Qu terminate these Terms as described below.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>Termination for convenience</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>You may terminate these Terms at any time via the &ldquo;Cancel Account&rdquo; feature on the Site or by sending us an email. If you cancel your Qu Account as a Host, any confirmed Bookings will be automatically cancelled and your Guests will receive a full refund. If you cancel your Qu Account as a Guest, any confirmed Booking will be automatically cancelled and any refund will depend upon the terms of the applicable cancellation policy.</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>Without limiting our rights specified below, Qu may terminate these Terms for convenience at any time by giving you 30 days&rsquo; notice via email to your registered email address.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>Termination for breach, suspension and other measures</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>Qu may immediately, without notice terminate these Terms if (i) you have materially breached these Terms or our Policies, including but not limited to any breach of your warranties outlined in these Terms or breach of the &ldquo;User Conduct&rdquo; provisions in these Terms, (ii) you have provided inaccurate, fraudulent, outdated or incomplete information during the Qu Account registration, or Listing process or thereafter, (iii) you have violated applicable laws, regulations or third party rights, or (iv) Qu believes in good faith that such action is reasonably necessary to protect the safety or property of other Members, Qu or third parties, for fraud prevention, risk assessment, security or investigation purposes.</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>In addition Qu may deactivate or delay Listings, reviews, or other Member Content, cancel any pending or confirmed Bookings, limit your use of or access to your Qu Account and the Site, Application or Services, temporarily or permanently revoke any special status associated with your Qu Account, or temporarily or permanently suspend your Qu Account if (i) you have breached these Terms or our Policies, including material and non-material breaches and receiving poor ratings from Hosts or Guests, or (ii) Qu believes in good faith that such action is reasonably necessary to protect the safety or property of Members, Qu or third parties, for fraud prevention, risk assessment, security or investigation purposes.</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>If we take any of the measures described in this Section 24.C, we may (i) communicate to your Guests or Hosts that a pending or confirmed Booking has been cancelled, (ii) refund your Guests in full for any and all confirmed Bookings, irrespective of preexisting cancellation policies, (iii) support your Guests, on an exceptional basis, in finding potential alternative Accommodations, and (iv) you will not be entitled to any compensation for confirmed Bookings that were cancelled.</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>In case of non-material breaches and where appropriate, you will be given notice of any measure by Qu and an opportunity to resolve the issue to Qu&rsquo;s reasonable satisfaction.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>Consequences</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>If you or we terminate this Agreement, we do not have an obligation to delete or return to you any of your Member Content, including but not limited to any reviews or Feedback. When this Agreement has been terminated, you are not entitled to a restoration of your Qu Account or any of your Member Content. If your access to or use of the Site, Application and Services has been limited or your Qu Account has been suspended or this Agreement has been terminated by us, you may not register a new Qu Account or attempt to access and use the Site, Application and Services through other Qu Accounts.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>Survival</strong></p>\r\n		</li>\r\n		<li>\r\n		<p><strong>If you or we terminate this Agreement, the clauses of these Terms that reasonably should survive termination of the Agreement will remain in effect.</strong></p>\r\n		</li>\r\n	</ul>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Disclaimers</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>IF YOU CHOOSE TO USE THE SITE, APPLICATION, SERVICES OR COLLECTIVE CONTENT OR PARTICIPATE IN THE REFERRAL PROGRAM, YOU DO SO AT YOUR SOLE RISK. YOU ACKNOWLEDGE AND AGREE THAT Qu DOES NOT HAVE AN OBLIGATION TO CONDUCT BACKGROUND OR REGISTERED SEX OFFENDER CHECKS ON ANY MEMBER, INCLUDING, BUT NOT LIMITED TO, GUESTS AND HOSTS. BUT MAY CONDUCT SUCH BACKGROUND OR REGISTERED SEX OFFENDER CHECKS, IN OUR SOLE DISCRETION, TO THE EXTENT PERMITTED BY APPLICABLE LAWS AND IF WE HAVE SUFFICIENT INFORMATION TO IDENTIFY A MEMBER. IF WE CHOOSE TO CONDUCT SUCH CHECKS, TO THE EXTENT PERMITTED BY APPLICABLE LAW, WE DISCLAIM WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED, THAT SUCH CHECKS WILL IDENTIFY PRIOR MISCONDUCT BY A USER OR GUARANTEE THAT A USER WILL NOT ENGAGE IN MISCONDUCT IN THE FUTURE.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>THE SITE, APPLICATION, SERVICES, COLLECTIVE CONTENT AND REFERRAL PROGRAM ARE PROVIDED &ldquo;AS IS&rdquo;, WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESS OR IMPLIED. WITHOUT LIMITING THE FOREGOING, Qu EXPLICITLY DISCLAIMS ANY WARRANTIES OF MERCHANTABILITY, SATISFACTORY QUALITY, FITNESS FOR A PARTICULAR PURPOSE, QUIET ENJOYMENT OR NON-INFRINGEMENT, AND ANY WARRANTIES ARISING OUT OF COURSE OF DEALING OR USAGE OF TRADE. Qu MAKES NO WARRANTY THAT THE SITE, APPLICATION, SERVICES, COLLECTIVE CONTENT, INCLUDING, BUT NOT LIMITED TO, THE LISTINGS OR ANY ACCOMMODATIONS, OR THE REFERRAL PROGRAM WILL MEET YOUR REQUIREMENTS OR BE AVAILABLE ON AN UNINTERRUPTED, SECURE, OR ERROR-FREE BASIS. Qu MAKES NO WARRANTY REGARDING THE QUALITY OF ANY LISTINGS, ACCOMMODATIONS, HOSTS, GUESTS, YOUR ACCRUAL OF Qu TRAVEL CREDITS, THE SERVICES OR COLLECTIVE CONTENT OR THE ACCURACY, TIMELINESS, TRUTHFULNESS, COMPLETENESS OR RELIABILITY OF ANY COLLECTIVE CONTENT OBTAINED THROUGH THE SITE, APPLICATION, SERVICES OR REFERRAL PROGRAM.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>NO ADVICE OR INFORMATION, WHETHER ORAL OR WRITTEN, OBTAINED FROM Qu OR THROUGH THE SITE, APPLICATION, SERVICES OR COLLECTIVE CONTENT, WILL CREATE ANY WARRANTY NOT EXPRESSLY MADE HEREIN.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>YOU ARE SOLELY RESPONSIBLE FOR ALL OF YOUR COMMUNICATIONS AND INTERACTIONS WITH OTHER USERS OF THE SITE, APPLICATION OR SERVICES AND WITH OTHER PERSONS WITH WHOM YOU COMMUNICATE OR INTERACT AS A RESULT OF YOUR USE OF THE SITE, APPLICATION OR SERVICES, INCLUDING, BUT NOT LIMITED TO, ANY HOSTS OR GUESTS. YOU UNDERSTAND THAT Qu DOES NOT MAKE ANY ATTEMPT TO VERIFY THE STATEMENTS OF USERS OF THE SITE, APPLICATION OR SERVICES OR TO REVIEW OR VISIT ANY ACCOMMODATIONS. Qu MAKES NO REPRESENTATIONS OR WARRANTIES AS TO THE CONDUCT OF USERS OF THE SITE, APPLICATION OR SERVICES OR THEIR COMPATIBILITY WITH ANY CURRENT OR FUTURE USERS OF THE SITE, APPLICATION OR SERVICES. YOU AGREE TO TAKE REASONABLE PRECAUTIONS IN ALL COMMUNICATIONS AND INTERACTIONS WITH OTHER USERS OF THE SITE, APPLICATION OR SERVICES AND WITH OTHER PERSONS WITH WHOM YOU COMMUNICATE OR INTERACT AS A RESULT OF YOUR USE OF THE SITE, APPLICATION OR SERVICES, INCLUDING, BUT NOT LIMITED TO, GUESTS AND HOSTS, PARTICULARLY IF YOU DECIDE TO MEET OFFLINE OR IN PERSON REGARDLESS OF WHETHER SUCH MEETINGS ARE ORGANIZED BY Qu. Qu EXPLICITLY DISCLAIMS ALL LIABILITY FOR ANY ACT OR OMISSION OF ANY GUEST OR OTHER THIRD PARTY.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Limitation of Liability</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>YOU ACKNOWLEDGE AND AGREE THAT, TO THE MAXIMUM EXTENT PERMITTED BY LAW, THE ENTIRE RISK ARISING OUT OF YOUR ACCESS TO AND USE OF THE SITE, APPLICATION, SERVICES AND COLLECTIVE CONTENT, YOUR LISTING OR BOOKING OF ANY ACCOMMODATIONS VIA THE SITE, APPLICATION AND SERVICES, YOUR PARTICIPATION IN THE REFERRAL PROGRAM, AND ANY CONTACT YOU HAVE WITH OTHER USERS OF Qu WHETHER IN PERSON OR ONLINE REMAINS WITH YOU. NEITHER Qu NOR ANY OTHER PARTY INVOLVED IN CREATING, PRODUCING, OR DELIVERING THE SITE, APPLICATION, SERVICES, COLLECTIVE CONTENT OR THE REFERRAL PROGRAM WILL BE LIABLE FOR ANY INCIDENTAL, SPECIAL, EXEMPLARY OR CONSEQUENTIAL DAMAGES, INCLUDING LOST PROFITS, LOSS OF DATA OR LOSS OF GOODWILL, SERVICE INTERRUPTION, COMPUTER DAMAGE OR SYSTEM FAILURE OR THE COST OF SUBSTITUTE PRODUCTS OR SERVICES, OR FOR ANY DAMAGES FOR PERSONAL OR BODILY INJURY OR EMOTIONAL DISTRESS ARISING OUT OF OR IN CONNECTION WITH THESE TERMS, FROM THE USE OF OR INABILITY TO USE THE SITE, APPLICATION, SERVICES OR COLLECTIVE CONTENT, FROM ANY COMMUNICATIONS, INTERACTIONS OR MEETINGS WITH OTHER USERS OF THE SITE, APPLICATION, OR SERVICES OR OTHER PERSONS WITH WHOM YOU COMMUNICATE OR INTERACT AS A RESULT OF YOUR USE OF THE SITE, APPLICATION, SERVICES, OR YOUR PARTICIPATION IN THE REFERRAL PROGRAM OR FROM YOUR LISTING OR BOOKING OF ANY ACCOMMODATION VIA THE SITE, APPLICATION AND SERVICES, WHETHER BASED ON WARRANTY, CONTRACT, TORT (INCLUDING NEGLIGENCE), PRODUCT LIABILITY OR ANY OTHER LEGAL THEORY, AND WHETHER OR NOT Qu HAS BEEN INFORMED OF THE POSSIBILITY OF SUCH DAMAGE, EVEN IF A LIMITED REMEDY SET FORTH HEREIN IS FOUND TO HAVE FAILED OF ITS ESSENTIAL PURPOSE.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>EXCEPT FOR OUR OBLIGATIONS TO PAY AMOUNTS TO APPLICABLE HOSTS PURSUANT TO THESE TERMS OR AN APPROVED PAYMENT REQUEST UNDER THE Qu HOST GUARANTEE, IN NO EVENT WILL Qu&rsquo;S AGGREGATE LIABILITY ARISING OUT OF OR IN CONNECTION WITH THESE TERMS AND YOUR USE OF THE SITE, APPLICATION AND SERVICES INCLUDING, BUT NOT LIMITED TO, FROM YOUR LISTING OR BOOKING OF ANY ACCOMMODATION VIA THE SITE, APPLICATION AND SERVICES, OR FROM THE USE OF OR INABILITY TO USE THE SITE, APPLICATION, SERVICES, OR COLLECTIVE CONTENT OR YOUR PARTICIPATION IN THE REFERRAL PROGRAM AND IN CONNECTION WITH ANY ACCOMMODATION OR INTERACTIONS WITH ANY OTHER MEMBERS, EXCEED THE AMOUNTS YOU HAVE PAID OR OWE FOR BOOKINGS VIA THE SITE, APPLICATION AND SERVICES AS A GUEST IN THE TWELVE (12) MONTH PERIOD PRIOR TO THE EVENT GIVING RISE TO THE LIABILITY, OR IF YOU ARE A HOST, THE AMOUNTS PAID BY Qu TO YOU IN THE TWELVE (12) MONTH PERIOD PRIOR TO THE EVENT GIVING RISE TO THE LIABILITY, OR ONE HUNDRED U.S. DOLLARS (US$100), IF NO SUCH PAYMENTS HAVE BEEN MADE, AS APPLICABLE. THE LIMITATIONS OF DAMAGES SET FORTH ABOVE ARE FUNDAMENTAL ELEMENTS OF THE BASIS OF THE BARGAIN BETWEEN Qu AND YOU. SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OR LIMITATION OF LIABILITY FOR CONSEQUENTIAL OR INCIDENTAL DAMAGES, SO THE ABOVE LIMITATION MAY NOT APPLY TO YOU.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Indemnification</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You agree to release, defend, indemnify, and hold Qu and its affiliates and subsidiaries, and their officers, directors, employees and agents, harmless from and against any claims, liabilities, damages, losses, and expenses, including, without limitation, reasonable legal and accounting fees, arising out of or in any way connected with (a) your access to or use of the Site, Application, Services, or Collective Content or your violation of these Terms; (b) your Member Content; (c) your (i) interaction with any Member, (ii) Booking of an Accommodation, or (iii) creation of a Listing; (d) the use, condition or Booking of an Accommodation by you, including but not limited to any injuries, losses, or damages (compensatory, direct, incidental, consequential or otherwise) of any kind arising in connection with or as a result of a Booking or use of an Accommodation; and (e) your participation in the Referral Program or your accrual of any Qu Travel Credits.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Export Control and Restricted Countries</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You may not use, export, re-export, import, or transfer the Application except as authorized by United States law, the laws of the jurisdiction in which you obtained the Application, and any other applicable laws. In particular, but without limitation, the Application may not be exported or re-exported: (a) into any United States embargoed countries; or (b) to anyone on the U.S. Treasury Department&rsquo;s list of Specially Designated Nationals or the U.S. Department of Commerce&rsquo;s Denied Persons List or Entity List. By using the Site, Application and Services, you represent and warrant that (i) neither you nor your listed Accommodation is located in a country that is subject to a U.S. Government embargo, or that has been designated by the U.S. Government as a &ldquo;terrorist supporting&rdquo; country and (ii) you are not listed on any U.S. Government list of prohibited or restricted parties. You also will not use the Site, Application and Services for any purpose prohibited by U.S. law, including the development, design, manufacture or production of missiles, or nuclear, chemical or biological weapons. Qu does not permit Listings associated with certain countries due to U.S. embargo restrictions. In addition to complying with the above, you must also comply with any relevant export control laws in your local jurisdiction.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Accessing and Downloading the Application from iTunes</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The following applies to any Application accessed through or downloaded from the Apple App Store (&ldquo;App Store Sourced Application&ldquo;):</strong></p>\r\n\r\n	<ul>\r\n		<li>\r\n		<p><strong>You acknowledge and agree that (i) these Terms are concluded between you and Qu only, and not Apple, and (ii) Qu, not Apple, is solely responsible for the App Store Sourced Application and content thereof. Your use of the App Store Sourced Application must comply with the App Store Terms of Services.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>You acknowledge that Apple has no obligation whatsoever to furnish any maintenance and support services with respect to the App Store Sourced Application.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>In the event of any failure of the App Store Sourced Application to conform to any applicable warranty, you may notify Apple, and Apple will refund the purchase price for the App Store Sourced Application to you and to the maximum extent permitted by applicable law, Apple will have no other warranty obligation whatsoever with respect to the App Store Sourced Application. As between Qu and Apple, any other claims, losses, liabilities, damages, costs or expenses attributable to any failure to conform to any warranty will be the sole responsibility of Qu.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>You and Qu acknowledge that, as between Qu and Apple, Apple is not responsible for addressing any claims you have or any claims of any third party relating to the App Store Sourced Application or your possession and use of the App Store Sourced Application, including, but not limited to: (i) product liability claims; (ii) any claim that the App Store Sourced Application fails to conform to any applicable legal or regulatory requirement; and (iii) claims arising under consumer protection or similar legislation.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>You and Qu acknowledge that, in the event of any third-party claim that the App Store Sourced Application or your possession and use of that App Store Sourced Application infringes that third party&rsquo;s intellectual property rights, as between Qu and Apple, Qu, not Apple, will be solely responsible for the investigation, defense, settlement and discharge of any such intellectual property infringement claim to the extent required by these Terms.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>You and Qu acknowledge and agree that Apple, and Apple&rsquo;s subsidiaries, are third-party beneficiaries of these Terms as related to your license of the App Store Sourced Application, and that, upon your acceptance of the terms and conditions of these Terms, Apple will have the right (and will be deemed to have accepted the right) to enforce these Terms as related to your license of the App Store Sourced Application against you as a third-party beneficiary thereof.</strong></p>\r\n		</li>\r\n		<li>&nbsp;</li>\r\n		<li>\r\n		<p><strong>Without limiting any other terms of these Terms, you must comply with all applicable third-party terms of agreement when using the App Store Sourced Application.</strong></p>\r\n		</li>\r\n	</ul>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Entire Agreement</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Except as they may be supplemented by a document referenced and incorporated herein or by additional Qu policies, guidelines, standards, or terms for a specific product, feature, service or offering, these Terms constitute the entire and exclusive understanding and agreement between Qu and you regarding the Site, Application, Services, Collective Content (excluding Payment Services), and any Bookings or Listings of Accommodations made via the Site, Application and Services (excluding Payment Services), and these Terms supersede and replace any and all prior oral or written understandings or agreements between Qu and you regarding Bookings or listings of Accommodations, the Site, Application, Services, and Collective Content (excluding Payment Services).</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Assignment</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>You may not assign or transfer these Terms, by operation of law or otherwise, without Qu&rsquo;s prior written consent. Any attempt by you to assign or transfer these Terms, without such consent, will be null and of no effect. Qu may assign or transfer these Terms, at its sole discretion, without restriction. Subject to the foregoing, these Terms will bind and inure to the benefit of the parties, their successors and permitted assigns.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Notices</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Any notices or other communications permitted or required hereunder, including those regarding modifications to these Terms, will be in writing and given by Qu (i) via a Communication (in each case to the address or phone number that you provide) or (ii) by posting to the Site or via the Application. For notices made via a Communication, the date of receipt will be deemed the date on which such notice is transmitted.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Controlling Law and Jurisdiction</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>These Terms and your use of the Services will be interpreted in accordance with the laws of the State of California and the United States of America, without regard to its conflict-of-law provisions. You and we agree to submit to the personal jurisdiction of a state court located in San Francisco County, San Francisco, California or a United States District Court, Northern District of California located in San Francisco, California for any actions for which the parties retain the right to seek injunctive or other equitable relief in a court of competent jurisdiction to prevent the actual or threatened infringement, misappropriation or violation of a party&rsquo;s copyrights, trademarks, trade secrets, patents, or other intellectual property rights, as set forth in the Dispute Resolution provision below.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Dispute Resolution</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>If you reside in the United States, you and Qu agree that any dispute, claim or controversy arising out of or relating to these Terms or the breach, termination, enforcement, interpretation or validity thereof, or to the use of the Services or use of the Site, Application or Collective Content (collectively, &ldquo;Disputes&ldquo;) will be settled by binding arbitration, except that each party retains the right to seek injunctive or other equitable relief in a court of competent jurisdiction to prevent the actual or threatened infringement, misappropriation or violation of a party&rsquo;s copyrights, trademarks, trade secrets, patents, or other intellectual property rights. You acknowledge and agree that you and Qu are each waiving the right to a trial by jury or to participate as a plaintiff or class member in any purported class action lawsuit, class-wide arbitration, private attorney-general action, or any other representative proceeding. Further, unless both you and Qu otherwise agree in writing, the arbitrator may not consolidate more than one person&rsquo;s claims, and may not otherwise preside over any form of any class or representative proceeding. If this specific paragraph is held unenforceable, then the entirety of this &ldquo;Dispute Resolution&rdquo; section will be deemed void. Except as provided in the preceding sentence, this &ldquo;Dispute Resolution&rdquo; section will survive any termination of these Terms.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Arbitration Rules and Governing Law. This agreement to arbitrate evidences a transaction in interstate commerce, and thus the Federal Arbitration Act governs the interpretation and enforcement of this provision. The arbitration will be administered by the American Arbitration Association (&ldquo;AAA&ldquo;) in accordance with the Consumer Arbitration Rules (the &ldquo;AAA Rules&ldquo;) then in effect, except as modified by this &ldquo;Dispute Resolution&rdquo; section. (The AAA Rules are available at <a href="http://www.adr.org/arb_med">www.adr.org/arb_med</a>or by calling the AAA at 1&ndash;800&ndash;778&ndash;7879.) The Federal Arbitration Act will govern the interpretation and enforcement of this section.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Arbitration Process. A party who desires to initiate arbitration must provide the other party with a written Demand for Arbitration as specified in the AAA Rules. (The AAA provides a <a href="http://www.adr.org/cs/idcplg?IdcService=GET_FILE&amp;dDocName=ADRSTAGE2034889&amp;RevisionSelectionMethod=LatestReleased">form Demand for Arbitration</a>.) The arbitrator will be either a retired judge or an attorney licensed to practice law in the state of California and will be selected by the parties from the AAA&rsquo;s roster of consumer dispute arbitrators. If the parties are unable to agree upon an arbitrator within seven (7) days of delivery of the Demand for Arbitration, then the AAA will appoint the arbitrator in accordance with the AAA Rules.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Arbitration Location and Procedure. Unless you and Qu otherwise agree, the arbitration will be conducted in the county where you reside. If your claim does not exceed $10,000, then the arbitration will be conducted solely on the basis of documents you and Qu submit to the arbitrator, unless you request a hearing or the arbitrator determines that a hearing is necessary. If your claim exceeds $10,000, your right to a hearing will be determined by the AAA Rules. Subject to the AAA Rules, the arbitrator will have the discretion to direct a reasonable exchange of information by the parties, consistent with the expedited nature of the arbitration.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Arbitrator&rsquo;s Decision. The arbitrator will render an award within the time frame specified in the AAA Rules. The arbitrator&rsquo;s decision will include the essential findings and conclusions upon which the arbitrator based the award. Judgment on the arbitration award may be entered in any court having jurisdiction thereof. The arbitrator&rsquo;s award damages must be consistent with the terms of the &ldquo;Limitation of Liability&rdquo; section above as to the types and the amounts of damages for which a party may be held liable. The arbitrator may award declaratory or injunctive relief only in favor of the claimant and only to the extent necessary to provide relief warranted by the claimant&rsquo;s individual claim. If you prevail in arbitration you will be entitled to an award of attorneys&rsquo; fees and expenses, to the extent provided under applicable law. Qu will not seek, and hereby waives all rights it may have under applicable law to recover, attorneys&rsquo; fees and expenses if it prevails in arbitration.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Fees. Your responsibility to pay any AAA filing, administrative and arbitrator fees will be solely as set forth in the AAA Rules. However, if your claim for damages does not exceed $75,000, Qu will pay all such fees unless the arbitrator finds that either the substance of your claim or the relief sought in your Demand for Arbitration was frivolous or was brought for an improper purpose (as measured by the standards set forth in Federal Rule of Civil Procedure 11(b)).</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Changes. Notwithstanding the provisions of the &ldquo;Modification&rdquo; section above, if Qu changes this &ldquo;Dispute Resolution&rdquo; section after the date you last accepted these Terms (or accepted any subsequent changes to these Terms), you may reject any such change by sending us written notice (including by email) within 30 days of the date such change became effective, as indicated in the &ldquo;Last Updated&rdquo; date above or in the date of Qu&rsquo;s email to you notifying you of such change. By rejecting any change, you are agreeing that you will arbitrate any Dispute between you and Qu in accordance with the provisions of this &ldquo;Dispute Resolution&rdquo; section as of the date you last accepted these Terms (or accepted any subsequent changes to these Terms).</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>General</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The failure of Qu to enforce any right or provision of these Terms will not constitute a waiver of future enforcement of that right or provision. The waiver of any such right or provision will be effective only if in writing and signed by a duly authorized representative of Qu. Except as expressly set forth in these Terms, the exercise by either party of any of its remedies under these Terms will be without prejudice to its other remedies under these Terms or otherwise. If for any reason an arbitrator or a court of competent jurisdiction finds any provision of these Terms invalid or unenforceable, that provision will be enforced to the maximum extent permissible and the other provisions of these Terms will remain in full force and effect.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Third party beneficiary</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>These Terms do not and are not intended to confer any rights or remedies upon any person other than the parties.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Additional Clauses for Users Contracting with Qu Ireland</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The following paragraphs will apply if you are contracting with Qu Ireland.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The second paragraph of Section 24.D, Term and Termination, Suspension and Other Measures, shall be removed and replaced with the following: &ldquo;If you or we terminate this Agreement, we do not have an obligation to return to you any of your Member Content, including but not limited to any reviews or Feedback. When this Agreement has been terminated, you are not entitled to a restoration of your Qu Account or any of your Member Content. If your access to or use of the Site, Application and Services has been limited or your Qu Account has been suspended or this Agreement has been terminated by us, you may not register a new Qu Account or attempt to access and use the Site, Application and Services through other Qu Accounts.&rdquo;</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The Controlling Law and Jurisdiction section shall be removed and replaced with the following: &ldquo;Controlling Law and Jurisdiction These Terms will be interpreted in accordance with Irish law. You and we agree to submit to the non-exclusive jurisdiction of the Irish courts for resolving any dispute between the parties. If Qu wishes to enforce any of its rights against you, we may elect to do so in the Irish courts or in the courts of the jurisdiction in which you are resident.&rdquo;</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The Dispute Resolution section shall be removed and is not applicable.</strong></p>\r\n	</li>\r\n	<li>&nbsp;</li>\r\n	<li>\r\n	<p><strong>Additional Clauses for Users Contracting with Qu China</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The following paragraphs will apply if you are contracting with Qu China</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The Controlling Law and Jurisdiction section shall be removed and replaced with the following: &ldquo;Controlling Law and Jurisdiction These Terms will be governed by and construed in accordance with the laws of China (&ldquo;China Laws&rdquo;). Any dispute arising from or in connection with this Agreement shall be submitted to the China International Economic and Trade Arbitration Commission (CIETAC) for arbitration in Beijing which shall be conducted in accordance with CIETAC&rsquo;s arbitration rules in effect at the time of applying for arbitration, provided that this section shall not be construed to limit any rights which Qu may have to apply to any court of competent jurisdiction for an order requiring you to perform or be prohibited from performing certain acts and other provisional relief permitted under China Laws or any other laws that may apply to you. The arbitration proceedings shall be conducted in English. The arbitral award rendered is final and binding upon both parties.&rdquo;</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>The Dispute Resolution section shall be removed and is not applicable.</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>Contacting Qu</strong></p>\r\n	</li>\r\n	<li>\r\n	<p><strong>If you have any questions about these Terms or any App Store Sourced Application, please contact Qu.</strong></p>\r\n	</li>\r\n</ul>\r\n', 'Terms and Conditions', 'Terms_and_Conditions', '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s.', 'Active', '2017-07-19 00:00:00', '2017-11-15 05:14:44', 1, 1);
INSERT INTO `page_content` (`id`, `page_name`, `page_title`, `page_description`, `meta_title`, `meta_keyword`, `meta_description`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(4, 'Privacy Policy', 'PRIVACY POLICY', '<p><strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\r\n', 'Privacy Policy title', 'Privacy_Policy', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500sLorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the indu', 'Active', '2017-07-19 00:00:00', '2017-11-13 11:24:07', 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `parking_spot`
--

CREATE TABLE `parking_spot` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `auto_rent_id` int(11) NOT NULL,
  `custom_availibity_id` int(11) NOT NULL,
  `address` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `country_id` int(20) DEFAULT NULL,
  `state_id` int(20) DEFAULT NULL,
  `city_name` varchar(255) NOT NULL,
  `verification_status` enum('Yes','No') NOT NULL,
  `users_verification_status` enum('Yes','No') NOT NULL DEFAULT 'No',
  `status` enum('Active','Inactive') NOT NULL,
  `number_of_space_spot` int(11) NOT NULL,
  `description` text NOT NULL,
  `location` varchar(255) NOT NULL,
  `latitude` text NOT NULL,
  `longitude` text NOT NULL,
  `instant_rent` enum('ON','OFF') NOT NULL,
  `renting_type` enum('','Schedule Rent','Auto Rent','Instant Rent') NOT NULL,
  `sche_start_date` datetime NOT NULL,
  `sche_start_time` time NOT NULL,
  `sche_start_date_time` datetime NOT NULL,
  `sche_end_date_time` datetime NOT NULL,
  `no_of_hours` int(11) NOT NULL,
  `no_of_days` int(11) NOT NULL,
  `no_of_months` int(11) NOT NULL,
  `mon_start_time` time NOT NULL DEFAULT '00:00:00',
  `mon_end_time` time NOT NULL DEFAULT '00:00:00',
  `tue_start_time` time NOT NULL DEFAULT '00:00:00',
  `tue_end_time` time NOT NULL DEFAULT '00:00:00',
  `wed_start_time` time NOT NULL DEFAULT '00:00:00',
  `wed_end_time` time NOT NULL DEFAULT '00:00:00',
  `thur_start_time` time NOT NULL DEFAULT '00:00:00',
  `thur_end_time` time NOT NULL DEFAULT '00:00:00',
  `fri_start_time` time NOT NULL DEFAULT '00:00:00',
  `fri_end_time` time NOT NULL DEFAULT '00:00:00',
  `sat_start_time` time NOT NULL DEFAULT '00:00:00',
  `sat_end_time` time NOT NULL DEFAULT '00:00:00',
  `sun_start_time` time NOT NULL DEFAULT '00:00:00',
  `sun_end_time` time NOT NULL DEFAULT '00:00:00',
  `verification_code` int(25) NOT NULL,
  `parking_spot_search_count` int(11) NOT NULL,
  `parking_spot_search` enum('Active','Inactive') NOT NULL DEFAULT 'Inactive',
  `is_delete` enum('Yes','No') NOT NULL DEFAULT 'No',
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parking_spot`
--

INSERT INTO `parking_spot` (`id`, `users_id`, `auto_rent_id`, `custom_availibity_id`, `address`, `postal_code`, `country_id`, `state_id`, `city_name`, `verification_status`, `users_verification_status`, `status`, `number_of_space_spot`, `description`, `location`, `latitude`, `longitude`, `instant_rent`, `renting_type`, `sche_start_date`, `sche_start_time`, `sche_start_date_time`, `sche_end_date_time`, `no_of_hours`, `no_of_days`, `no_of_months`, `mon_start_time`, `mon_end_time`, `tue_start_time`, `tue_end_time`, `wed_start_time`, `wed_end_time`, `thur_start_time`, `thur_end_time`, `fri_start_time`, `fri_end_time`, `sat_start_time`, `sat_end_time`, `sun_start_time`, `sun_end_time`, `verification_code`, `parking_spot_search_count`, `parking_spot_search`, `is_delete`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(151, 61, 0, 0, 'Tragad Road,Gota,Daskroi,Gujarat 382481,India', '382481', 0, 0, 'Daskroi', 'Yes', 'Yes', 'Active', 2, 'Adani Pratham  flats parking spot.', 'Tragad Road,Gota,Daskroi,Gujarat 382481,India', '23.1300898', '72.5508914', 'ON', 'Instant Rent', '0000-00-00 00:00:00', '00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0, 0, '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', '00:00:00', 0, 0, 'Inactive', 'No', '2017-12-11 06:57:44', '2017-12-11 11:16:03', 61, 0);

-- --------------------------------------------------------

--
-- Table structure for table `parking_spot_images`
--

CREATE TABLE `parking_spot_images` (
  `id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `uploaded_image` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `parking_spot_search_log`
--

CREATE TABLE `parking_spot_search_log` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `date` varchar(255) NOT NULL,
  `time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parking_spot_search_log`
--

INSERT INTO `parking_spot_search_log` (`id`, `users_id`, `latitude`, `longitude`, `date`, `time`) VALUES
(48, 62, '23.1300898', '72.5508914', '11 Jan 2018', '3:27 PM'),
(49, 61, '23.1300898', '72.5508914', '16 Dec 2017', '15:16');

-- --------------------------------------------------------

--
-- Table structure for table `parking_spot_space_managment`
--

CREATE TABLE `parking_spot_space_managment` (
  `id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `space_number` int(11) NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parking_spot_space_managment`
--

INSERT INTO `parking_spot_space_managment` (`id`, `parking_spot_id`, `space_number`, `created_by`) VALUES
(209, 151, 1, 61),
(210, 151, 2, 61);

-- --------------------------------------------------------

--
-- Table structure for table `payment_history`
--

CREATE TABLE `payment_history` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `transaction_details` longtext NOT NULL,
  `payment_type` enum('booking_amount','surcharge_amount') NOT NULL,
  `amount` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` enum('Complete','Pending') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_history`
--

INSERT INTO `payment_history` (`id`, `users_id`, `transaction_id`, `transaction_details`, `payment_type`, `amount`, `created_date`, `status`) VALUES
(95, 62, 'g21ssz74', 'Braintree\\Transaction[id=g21ssz74, type=sale, amount=44.00, status=submitted_for_settlement, createdAt=Monday, 11-Dec-17 07:18:01 UTC, creditCardDetails=Braintree\\Transaction\\CreditCardDetails[token=, bin=411111, last4=1111, cardType=Visa, expirationMonth=05, expirationYear=2018, customerLocation=US, cardholderName=, imageUrl=https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox, prepaid=Unknown, healthcare=Unknown, debit=Unknown, durbinRegulated=Unknown, commercial=Unknown, payroll=Unknown, issuingBank=Unknown, countryOfIssuance=Unknown, productId=Unknown, uniqueNumberIdentifier=, venmoSdk=, expirationDate=05/2018, maskedNumber=411111******1111], customerDetails=Braintree\\Transaction\\CustomerDetails[id=, firstName=, lastName=, company=, email=, website=, phone=, fax=]]', 'booking_amount', 44, '2017-12-11 07:18:02', 'Complete'),
(96, 62, '7m2j2ycm', 'Braintree\\Transaction[id=7m2j2ycm, type=sale, amount=88.00, status=submitted_for_settlement, createdAt=Monday, 11-Dec-17 08:23:13 UTC, creditCardDetails=Braintree\\Transaction\\CreditCardDetails[token=, bin=411111, last4=1111, cardType=Visa, expirationMonth=05, expirationYear=2019, customerLocation=US, cardholderName=, imageUrl=https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox, prepaid=Unknown, healthcare=Unknown, debit=Unknown, durbinRegulated=Unknown, commercial=Unknown, payroll=Unknown, issuingBank=Unknown, countryOfIssuance=Unknown, productId=Unknown, uniqueNumberIdentifier=, venmoSdk=, expirationDate=05/2019, maskedNumber=411111******1111], customerDetails=Braintree\\Transaction\\CustomerDetails[id=, firstName=, lastName=, company=, email=, website=, phone=, fax=]]', 'booking_amount', 88, '2017-12-11 08:23:13', 'Complete'),
(97, 62, 'm9xba7sz', 'Braintree\\Transaction[id=m9xba7sz, type=sale, amount=44.00, status=submitted_for_settlement, createdAt=Monday, 11-Dec-17 08:28:41 UTC, creditCardDetails=Braintree\\Transaction\\CreditCardDetails[token=, bin=411111, last4=1111, cardType=Visa, expirationMonth=04, expirationYear=2019, customerLocation=US, cardholderName=, imageUrl=https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox, prepaid=Unknown, healthcare=Unknown, debit=Unknown, durbinRegulated=Unknown, commercial=Unknown, payroll=Unknown, issuingBank=Unknown, countryOfIssuance=Unknown, productId=Unknown, uniqueNumberIdentifier=, venmoSdk=, expirationDate=04/2019, maskedNumber=411111******1111], customerDetails=Braintree\\Transaction\\CustomerDetails[id=, firstName=, lastName=, company=, email=, website=, phone=, fax=]]', 'booking_amount', 44, '2017-12-11 08:28:42', 'Complete'),
(98, 62, 'gmhg0g9h', 'Braintree\\Transaction[id=gmhg0g9h, type=sale, amount=88.00, status=submitted_for_settlement, createdAt=Monday, 11-Dec-17 08:34:43 UTC, creditCardDetails=Braintree\\Transaction\\CreditCardDetails[token=, bin=411111, last4=1111, cardType=Visa, expirationMonth=01, expirationYear=2018, customerLocation=US, cardholderName=, imageUrl=https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox, prepaid=Unknown, healthcare=Unknown, debit=Unknown, durbinRegulated=Unknown, commercial=Unknown, payroll=Unknown, issuingBank=Unknown, countryOfIssuance=Unknown, productId=Unknown, uniqueNumberIdentifier=, venmoSdk=, expirationDate=01/2018, maskedNumber=411111******1111], customerDetails=Braintree\\Transaction\\CustomerDetails[id=, firstName=, lastName=, company=, email=, website=, phone=, fax=]]', 'booking_amount', 88, '2017-12-11 08:34:43', 'Complete'),
(99, 62, 'ekq5dgsv', 'Braintree\\Transaction[id=ekq5dgsv, type=sale, amount=44.00, status=submitted_for_settlement, createdAt=Monday, 11-Dec-17 08:40:15 UTC, creditCardDetails=Braintree\\Transaction\\CreditCardDetails[token=, bin=411111, last4=1111, cardType=Visa, expirationMonth=01, expirationYear=2018, customerLocation=US, cardholderName=, imageUrl=https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox, prepaid=Unknown, healthcare=Unknown, debit=Unknown, durbinRegulated=Unknown, commercial=Unknown, payroll=Unknown, issuingBank=Unknown, countryOfIssuance=Unknown, productId=Unknown, uniqueNumberIdentifier=, venmoSdk=, expirationDate=01/2018, maskedNumber=411111******1111], customerDetails=Braintree\\Transaction\\CustomerDetails[id=, firstName=, lastName=, company=, email=, website=, phone=, fax=]]', 'booking_amount', 44, '2017-12-11 08:40:15', 'Complete'),
(100, 62, '7qvhtcdh', 'Braintree\\Transaction[id=7qvhtcdh, type=sale, amount=176.00, status=submitted_for_settlement, createdAt=Monday, 11-Dec-17 08:42:42 UTC, creditCardDetails=Braintree\\Transaction\\CreditCardDetails[token=, bin=411111, last4=1111, cardType=Visa, expirationMonth=06, expirationYear=2019, customerLocation=US, cardholderName=, imageUrl=https://assets.braintreegateway.com/payment_method_logo/visa.png?environment=sandbox, prepaid=Unknown, healthcare=Unknown, debit=Unknown, durbinRegulated=Unknown, commercial=Unknown, payroll=Unknown, issuingBank=Unknown, countryOfIssuance=Unknown, productId=Unknown, uniqueNumberIdentifier=, venmoSdk=, expirationDate=06/2019, maskedNumber=411111******1111], customerDetails=Braintree\\Transaction\\CustomerDetails[id=, firstName=, lastName=, company=, email=, website=, phone=, fax=]]', 'booking_amount', 176, '2017-12-11 08:42:42', 'Complete');

-- --------------------------------------------------------

--
-- Table structure for table `pricing`
--

CREATE TABLE `pricing` (
  `id` int(11) NOT NULL,
  `no_of_hours` int(11) NOT NULL,
  `hourly_price` int(11) NOT NULL,
  `no_of_days` int(11) NOT NULL,
  `daily_price` int(11) NOT NULL,
  `no_of_month` int(11) NOT NULL,
  `monthly_price` int(11) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `pricing`
--

INSERT INTO `pricing` (`id`, `no_of_hours`, `hourly_price`, `no_of_days`, `daily_price`, `no_of_month`, `monthly_price`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(3, 1, 10, 1, 10, 1, 300, 'Inactive', '2017-10-25 11:26:27', '0000-00-00 00:00:00', 1, 0),
(4, 2, 44, 2, 100, 2, 1000, 'Active', '2017-11-13 11:50:57', '2017-11-13 11:52:14', 8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `promocode`
--

CREATE TABLE `promocode` (
  `id` int(11) NOT NULL,
  `promo_code` varchar(255) NOT NULL,
  `promo_name` varchar(255) NOT NULL,
  `discount` int(11) NOT NULL,
  `promo_start_date` date NOT NULL DEFAULT '0000-00-00',
  `promo_end_date` date NOT NULL DEFAULT '0000-00-00',
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `promocode`
--

INSERT INTO `promocode` (`id`, `promo_code`, `promo_name`, `discount`, `promo_start_date`, `promo_end_date`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, '3USKIP', 'Promocode 1', 15, '2017-07-06', '2017-12-31', 'Active', '2017-07-01 11:46:31', '2017-08-18 10:20:06', 1, 1),
(2, 'PX10IM', 'Promocode 2', 20, '2017-07-05', '2017-12-31', 'Active', '2017-07-01 11:55:49', '2017-08-18 10:19:56', 1, 1),
(3, 'GJYWNV', 'Promocode 3', 10, '2017-08-01', '2017-10-26', 'Active', '2017-08-18 10:21:07', '0000-00-00 00:00:00', 4, 0),
(4, 'GIXS2B', 'Promocode 4', 15, '2017-08-01', '2017-09-20', 'Active', '2017-08-18 10:21:26', '0000-00-00 00:00:00', 4, 0),
(5, 'I9TOZB', 'Promocode 5', 25, '2017-08-01', '2017-10-04', 'Active', '2017-08-18 10:21:40', '0000-00-00 00:00:00', 4, 0),
(6, 'K2IVHM', 'Promocode 6', 30, '2017-07-19', '2017-08-31', 'Active', '2017-08-18 10:21:54', '0000-00-00 00:00:00', 4, 0),
(7, 'GQ0VO4', 'Promocode 7', 35, '2017-08-01', '2017-10-09', 'Active', '2017-08-18 10:22:09', '0000-00-00 00:00:00', 4, 0),
(8, '1CH9KL', 'Promocode 8', 40, '2017-08-01', '2017-10-05', 'Active', '2017-08-18 10:22:25', '0000-00-00 00:00:00', 4, 0),
(9, 'MQUYKG', 'Promocode 9', 50, '2017-08-01', '2017-10-19', 'Active', '2017-08-18 10:22:39', '0000-00-00 00:00:00', 4, 0),
(10, 'LBQ9OD', 'Promocode 10', 55, '2017-08-01', '2017-10-12', 'Active', '2017-08-18 10:22:52', '0000-00-00 00:00:00', 4, 0),
(11, 'JG1AY2', 'Promocode 11', 56, '2017-07-12', '2017-10-13', 'Active', '2017-08-18 10:23:07', '0000-00-00 00:00:00', 4, 0),
(13, 'HBXSQ3', 'AAA', 3, '2017-10-01', '1970-01-01', 'Active', '2017-10-26 11:49:58', '2017-10-26 11:51:06', 1, 1),
(14, 'FG4A8N', 'NNN', 2, '2017-10-03', '2018-02-02', 'Inactive', '2017-10-26 11:51:55', '0000-00-00 00:00:00', 1, 0),
(15, 'OI4MHL', 'kkk', 100, '2017-11-13', '2018-01-18', 'Active', '2017-11-13 11:58:25', '2017-11-13 11:58:48', 8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `questions_answer` varchar(255) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `rating` float NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `feedback_description` text NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `booking_id`, `users_id`, `questions_answer`, `parking_spot_id`, `rating`, `status`, `feedback_description`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(23, 189, 61, '[\n  {\n    "1" : "No"\n  },\n  {\n    "2" : "Yes"\n  },\n  {\n    "3" : "Yes"\n  }\n]', 151, 4.5, 'Active', 'Thanks for your positiveness about my spot.please well come again.', '2017-12-11 11:17:32', '2017-12-11 11:21:43', 61, 61),
(24, 189, 62, '[\n  {\n    "1" : "No"\n  },\n  {\n    "2" : "No"\n  },\n  {\n    "3" : "No"\n  }\n]', 151, 4.5, 'Active', 'The spot facility and amenities are amazing,I m keen to visit it again. Thank you.', '2017-12-11 11:19:45', '2017-12-11 11:20:19', 62, 62);

-- --------------------------------------------------------

--
-- Table structure for table `review_questionnaires`
--

CREATE TABLE `review_questionnaires` (
  `id` int(11) NOT NULL,
  `questionnaires_title` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review_questionnaires`
--

INSERT INTO `review_questionnaires` (`id`, `questionnaires_title`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 'Was this spot in a safe neighborhood ?', 'Active', '2017-07-01 06:24:10', '2017-07-28 06:59:35', 1, 1),
(2, 'Was this place close to your desired location ?', 'Active', '2017-07-01 06:51:55', '2017-07-28 06:58:31', 1, 1),
(6, 'How\'s the spot , please review?', 'Active', '2017-11-13 11:56:20', '2017-11-13 11:56:47', 8, 8);

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `id` int(11) NOT NULL,
  `country_id` int(11) NOT NULL,
  `state_name` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`id`, `country_id`, `state_name`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(21, 1, 'dd', 'Active', '2017-10-26 12:01:34', '2017-10-26 12:01:34', 1, 0),
(23, 8, 'aa', 'Active', '2017-10-26 12:01:57', '2017-10-26 12:01:57', 1, 0),
(24, 14, 'cc', 'Active', '2017-10-26 12:02:08', '2017-10-27 12:04:42', 1, 1),
(25, 1, 'Gujarat', 'Active', '2017-11-13 12:05:52', '2017-11-13 12:05:52', 8, 0),
(26, 2, 'Arizona ', 'Active', '2017-11-14 06:23:06', '2017-11-14 06:23:06', 8, 0),
(27, 2, 'Alabama', 'Active', '2017-11-16 12:24:03', '2017-11-16 12:24:03', 1, 0),
(28, 2, 'Alaska', 'Active', '2017-11-16 12:24:14', '2017-11-16 12:24:14', 1, 0),
(29, 2, 'Arkansas', 'Active', '2017-11-16 12:24:37', '2017-11-16 12:24:37', 1, 0),
(30, 2, 'California', 'Active', '2017-11-16 12:24:50', '2017-11-16 12:24:50', 1, 0),
(31, 2, 'Colorado', 'Active', '2017-11-16 12:25:03', '2017-11-16 12:25:03', 1, 0),
(32, 2, 'Connecticut', 'Active', '2017-11-16 12:25:15', '2017-11-16 12:25:15', 1, 0),
(33, 2, 'Delaware', 'Active', '2017-11-16 12:25:27', '2017-11-16 12:25:27', 1, 0),
(34, 2, 'Florida', 'Active', '2017-11-16 12:25:39', '2017-11-16 12:25:39', 1, 0),
(35, 2, 'Georgia', 'Active', '2017-11-16 12:25:48', '2017-11-16 12:25:48', 1, 0),
(36, 2, 'Hawaii', 'Active', '2017-11-16 12:25:59', '2017-11-16 12:25:59', 1, 0),
(37, 2, 'Idaho', 'Active', '2017-11-16 12:26:10', '2017-11-16 12:26:10', 1, 0),
(38, 2, 'Illinois', 'Active', '2017-11-16 12:26:26', '2017-11-16 12:26:26', 1, 0),
(39, 2, 'Indiana', 'Active', '2017-11-16 12:26:37', '2017-11-16 12:26:37', 1, 0),
(40, 2, 'Iowa', 'Active', '2017-11-16 12:26:47', '2017-11-16 12:26:55', 1, 1),
(41, 2, 'Kansas', 'Active', '2017-11-16 12:27:09', '2017-11-16 12:27:09', 1, 0),
(42, 2, 'Kentucky', 'Active', '2017-11-16 12:27:24', '2017-11-16 12:27:24', 1, 0),
(43, 2, 'Louisiana', 'Active', '2017-11-16 12:27:34', '2017-11-16 12:27:34', 1, 0),
(44, 2, 'Maine', 'Active', '2017-11-16 12:27:44', '2017-11-16 12:27:44', 1, 0),
(45, 2, 'Maryland', 'Active', '2017-11-16 12:28:01', '2017-11-16 12:28:01', 1, 0),
(46, 2, 'Massachusetts', 'Active', '2017-11-16 12:28:20', '2017-11-16 12:28:20', 1, 0),
(47, 2, 'Michigan', 'Active', '2017-11-16 12:28:29', '2017-11-16 12:28:29', 1, 0),
(48, 2, 'Minnesota', 'Active', '2017-11-16 12:28:37', '2017-11-16 12:28:37', 1, 0),
(49, 2, 'Mississippi', 'Active', '2017-11-16 12:28:59', '2017-11-16 12:28:59', 1, 0),
(50, 2, 'Missouri', 'Active', '2017-11-16 12:29:08', '2017-11-16 12:29:08', 1, 0),
(51, 2, 'Montana', 'Active', '2017-11-16 12:29:17', '2017-11-16 12:29:17', 1, 0),
(52, 2, 'Nebraska', 'Active', '2017-11-16 12:29:28', '2017-11-16 12:29:28', 1, 0),
(53, 2, 'Nevada', 'Active', '2017-11-16 12:29:37', '2017-11-16 12:29:37', 1, 0),
(54, 2, 'New Hampshire', 'Active', '2017-11-16 12:29:48', '2017-11-16 12:29:48', 1, 0),
(55, 2, 'New Jersey', 'Active', '2017-11-16 12:29:57', '2017-11-16 12:29:57', 1, 0),
(56, 2, 'New Mexico', 'Active', '2017-11-16 12:30:07', '2017-11-16 12:30:07', 1, 0),
(57, 2, 'New York', 'Active', '2017-11-16 12:30:15', '2017-11-16 12:30:15', 1, 0),
(58, 2, 'North Carolina', 'Active', '2017-11-16 12:30:32', '2017-11-16 12:30:32', 1, 0),
(59, 2, 'North Dakota', 'Active', '2017-11-16 12:30:42', '2017-11-16 12:30:42', 1, 0),
(60, 2, 'Ohio', 'Active', '2017-11-16 12:30:52', '2017-11-16 12:30:52', 1, 0),
(61, 2, 'Oklahoma', 'Active', '2017-11-16 12:31:01', '2017-11-16 12:31:01', 1, 0),
(62, 2, 'Oregon', 'Active', '2017-11-16 12:31:12', '2017-11-16 12:31:12', 1, 0),
(63, 2, 'Pennsylvania', 'Active', '2017-11-16 12:31:30', '2017-11-16 12:31:30', 1, 0),
(64, 2, 'Rhode Island', 'Active', '2017-11-16 12:31:59', '2017-11-16 12:31:59', 1, 0),
(65, 2, 'South Carolina', 'Active', '2017-11-16 12:32:16', '2017-11-16 12:32:16', 1, 0),
(66, 2, 'South Dakota', 'Active', '2017-11-16 12:32:27', '2017-11-16 12:32:27', 1, 0),
(67, 2, 'Tennessee', 'Active', '2017-11-16 12:32:38', '2017-11-16 12:32:38', 1, 0),
(68, 2, 'Texas', 'Active', '2017-11-16 12:32:50', '2017-11-16 12:32:50', 1, 0),
(69, 2, 'Utah', 'Active', '2017-11-16 12:32:59', '2017-11-16 12:32:59', 1, 0),
(70, 2, 'Vermont', 'Active', '2017-11-16 12:33:11', '2017-11-16 12:33:11', 1, 0),
(71, 2, 'Virginia', 'Active', '2017-11-16 12:33:23', '2017-11-16 12:33:23', 1, 0),
(72, 2, 'Washington', 'Active', '2017-11-16 12:33:33', '2017-11-16 12:33:33', 1, 0),
(73, 2, 'West Virginia', 'Active', '2017-11-16 12:33:43', '2017-11-16 12:33:43', 1, 0),
(74, 2, 'Wisconsin', 'Active', '2017-11-16 12:33:54', '2017-11-16 12:33:54', 1, 0),
(75, 2, 'Wyoming', 'Active', '2017-11-16 12:34:05', '2017-11-16 12:34:05', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `surcharge_amount`
--

CREATE TABLE `surcharge_amount` (
  `id` int(11) NOT NULL,
  `amount_before_half_min` int(11) NOT NULL,
  `amount_after_half_min` int(11) NOT NULL,
  `amount_per_hour` int(11) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_adminuser`
--

CREATE TABLE `tbl_adminuser` (
  `id` int(11) NOT NULL,
  `first_name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `last_name` varchar(200) CHARACTER SET latin1 NOT NULL,
  `email_address` varchar(225) CHARACTER SET latin1 NOT NULL,
  `password` varchar(225) CHARACTER SET latin1 NOT NULL,
  `image` text CHARACTER SET latin1,
  `changepasswordtime` datetime DEFAULT NULL,
  `changepasswordkey` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
  `status` enum('Active','Inactive') CHARACTER SET latin1 NOT NULL,
  `createdDate` datetime DEFAULT NULL,
  `updatedDate` datetime DEFAULT NULL,
  `remember_token` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_adminuser`
--

INSERT INTO `tbl_adminuser` (`id`, `first_name`, `last_name`, `email_address`, `password`, `image`, `changepasswordtime`, `changepasswordkey`, `status`, `createdDate`, `updatedDate`, `remember_token`, `role_id`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '0192023a7bbd73250516f069df18b500', NULL, NULL, NULL, 'Active', '2017-06-13 00:00:00', '2017-11-13 11:21:36', '42af42c7f2d7a8e9692dd7901b9f473a8f95ce4a', 0);

-- --------------------------------------------------------

--
-- Table structure for table `transaction_history`
--

CREATE TABLE `transaction_history` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `transaction_for` enum('Booking','Reservation','Refund_Booking','Refund_Reservation','surcharge') NOT NULL,
  `amount` int(11) NOT NULL,
  `is_transaction` enum('Yes','No') NOT NULL DEFAULT 'No',
  `invoice` varchar(255) NOT NULL DEFAULT '',
  `created_date` datetime NOT NULL,
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction_history`
--

INSERT INTO `transaction_history` (`id`, `users_id`, `transaction_id`, `parking_spot_id`, `transaction_for`, `amount`, `is_transaction`, `invoice`, `created_date`, `created_by`) VALUES
(169, 62, 95, 151, 'Booking', 44, 'Yes', 'HL6Z90MT8-invoice.pdf', '2017-12-11 07:18:02', 62),
(170, 61, 95, 151, 'Reservation', 44, 'Yes', '', '2017-12-11 07:18:02', 62),
(171, 62, 96, 151, 'Booking', 88, 'Yes', 'DZNMTXO7E-invoice.pdf', '2017-12-11 08:23:13', 62),
(172, 61, 96, 151, 'Reservation', 88, 'Yes', '', '2017-12-11 08:23:13', 62),
(173, 62, 25, 151, 'Refund_Booking', 66, 'No', '', '2017-12-11 08:23:50', 62),
(174, 61, 25, 151, 'Refund_Reservation', 66, 'No', '', '2017-12-11 08:23:50', 62),
(175, 62, 97, 151, 'Booking', 44, 'Yes', '4LOQA98Z1-invoice.pdf', '2017-12-11 08:28:42', 62),
(176, 61, 97, 151, 'Reservation', 44, 'Yes', '', '2017-12-11 08:28:42', 62),
(177, 62, 26, 151, 'Refund_Booking', 33, 'No', '', '2017-12-11 08:28:55', 62),
(178, 61, 26, 151, 'Refund_Reservation', 33, 'No', '', '2017-12-11 08:28:55', 62),
(179, 62, 98, 151, 'Booking', 88, 'Yes', 'FV9HZL1XQ-invoice.pdf', '2017-12-11 08:34:44', 62),
(180, 61, 98, 151, 'Reservation', 88, 'Yes', '', '2017-12-11 08:34:44', 62),
(181, 62, 27, 151, 'Refund_Booking', 66, 'No', '', '2017-12-11 08:35:36', 61),
(182, 61, 27, 151, 'Refund_Reservation', 66, 'No', '', '2017-12-11 08:35:36', 61),
(183, 62, 99, 151, 'Booking', 44, 'Yes', '96CAV7EP8-invoice.pdf', '2017-12-11 08:40:15', 62),
(184, 61, 99, 151, 'Reservation', 44, 'Yes', '', '2017-12-11 08:40:15', 62),
(185, 62, 28, 151, 'Refund_Booking', 44, 'No', '', '2017-12-11 08:40:35', 61),
(186, 61, 28, 151, 'Refund_Reservation', 44, 'No', '', '2017-12-11 08:40:35', 61),
(187, 62, 100, 151, 'Booking', 176, 'Yes', 'XV8KWT501-invoice.pdf', '2017-12-11 08:42:42', 62),
(188, 61, 100, 151, 'Reservation', 176, 'Yes', '', '2017-12-11 08:42:42', 62),
(189, 62, 29, 151, 'Refund_Booking', 132, 'No', '', '2017-12-11 08:43:01', 62),
(190, 61, 29, 151, 'Refund_Reservation', 132, 'No', '', '2017-12-11 08:43:01', 62);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `remember_token` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL DEFAULT '',
  `latitude` text NOT NULL,
  `longitude` text NOT NULL,
  `country_id` int(20) NOT NULL,
  `zipcode` varchar(255) NOT NULL,
  `social_id` varchar(255) NOT NULL,
  `social_type` enum('','google plus','facebook') NOT NULL,
  `timezone` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `username`, `email`, `contact_number`, `gender`, `password`, `profile_image`, `status`, `remember_token`, `location`, `latitude`, `longitude`, `country_id`, `zipcode`, `social_id`, `social_type`, `timezone`, `created_at`, `updated_at`, `created_by`, `updated_by`) VALUES
(61, 'H B', 'Champavat', '', 'harendra@mailinator.com', '9586446446464', 'Male', '84c5a15b0aada68da355f31fac17ea08', '1512979733.jpg', 'Active', '', 'Prahlad Nagar,Ahmedabad,Gujarat 380015,India', '23.0097039756885', '72.506040679281', 1, '388842', '', '', 'Asia/Kolkata', '2017-12-11 06:54:31', '0000-00-00 00:00:00', 61, 0),
(62, 'trootech', 'developer', '', 'troodeveloper@gmail.com', '6023559257', '', '', '', 'Active', '', 'Corporate Road,Prahlad Nagar,Ahmedabad,Gujarat 380015,India', '23.010325660572', '72.5067492985204', 0, '', '105949683019957975180', 'google plus', 'Asia/Kolkata', '2017-12-11 07:16:29', '0000-00-00 00:00:00', 62, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_notification`
--

CREATE TABLE `user_notification` (
  `id` int(11) NOT NULL,
  `notification_title` varchar(255) NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_notification`
--

INSERT INTO `user_notification` (`id`, `notification_title`, `status`, `created_date`, `updated_date`, `created_by`, `updated_by`) VALUES
(1, 'Notify me when my listed parking spot/space is verified by the admin', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(2, 'Notify me when booking is made by the user (renter)', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(3, 'Notify me when there is high demand nearby', 'Active', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 0, 0),
(4, 'Notify me after fixed \'X\' number of hours/days of exit time (as per the booking details)', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(5, 'Notify me when booking is cancelled by the user (renter/host)', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(6, 'Notify me when less than 25% of my time allowance is left', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(7, 'Notify me when I go over the allotted time along with the surcharge/penalty amount to be\r\npaid', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(8, 'Notify me when the device (renter) first time arrives', 'Active', '2017-07-21 00:00:00', '2017-07-28 11:41:26', 0, 0),
(9, 'Notify me when user (renter) departs', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(10, 'When I as a host receives the 25% of the surcharge amount for inconvenience', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(11, 'When I as a renter gets the 25% of discount for inconvenience', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(12, 'Notify me (as a renter) when cancellation amount is refunded to me', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(13, 'Notify me when as a host booking amount is funded by the admin', 'Active', '2017-07-21 00:00:00', '0000-00-00 00:00:00', 0, 0),
(15, 'testing 23', 'Inactive', '2017-08-17 12:16:13', '2017-08-18 10:41:53', 1, 4),
(16, 'Booking', 'Inactive', '2017-10-25 12:50:15', '2017-11-13 12:01:47', 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `user_surcharge_amount`
--

CREATE TABLE `user_surcharge_amount` (
  `id` int(11) NOT NULL,
  `users_id` int(11) NOT NULL,
  `parking_spot_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `surcharge_amount_time` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `action_master`
--
ALTER TABLE `action_master`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `action_id` (`action_id`);

--
-- Indexes for table `admin_fund_managment`
--
ALTER TABLE `admin_fund_managment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `users_id` (`users_id`);

--
-- Indexes for table `admin_notification`
--
ALTER TABLE `admin_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin_notification_management`
--
ALTER TABLE `admin_notification_management`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_setting`
--
ALTER TABLE `app_setting`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_adminuser_id` (`tbl_adminuser_id`),
  ADD KEY `tbl_adminuser_id_2` (`tbl_adminuser_id`);

--
-- Indexes for table `auto_rent`
--
ALTER TABLE `auto_rent`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_details`
--
ALTER TABLE `bank_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `bank_receipt`
--
ALTER TABLE `bank_receipt`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parking_spot_id` (`parking_spot_id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `booking_refund`
--
ALTER TABLE `booking_refund`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`users_id`),
  ADD KEY `bank_details_id` (`bank_details_id`);

--
-- Indexes for table `contact_us`
--
ALTER TABLE `contact_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `custom_availability`
--
ALTER TABLE `custom_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `device_master`
--
ALTER TABLE `device_master`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `email_template`
--
ALTER TABLE `email_template`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `general_settings`
--
ALTER TABLE `general_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `menu_master`
--
ALTER TABLE `menu_master`
  ADD PRIMARY KEY (`menu_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`),
  ADD KEY `push_notification_id` (`push_notification_id`);

--
-- Indexes for table `page_content`
--
ALTER TABLE `page_content`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_spot`
--
ALTER TABLE `parking_spot`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`),
  ADD KEY `auto_rent_id` (`auto_rent_id`),
  ADD KEY `custom_availibity_id` (`custom_availibity_id`),
  ADD KEY `custom_availibity_id_2` (`custom_availibity_id`);

--
-- Indexes for table `parking_spot_images`
--
ALTER TABLE `parking_spot_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_spot_search_log`
--
ALTER TABLE `parking_spot_search_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parking_spot_space_managment`
--
ALTER TABLE `parking_spot_space_managment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `pricing`
--
ALTER TABLE `pricing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promocode`
--
ALTER TABLE `promocode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `user_id` (`users_id`);

--
-- Indexes for table `review_questionnaires`
--
ALTER TABLE `review_questionnaires`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`id`),
  ADD KEY `country_id` (`country_id`);

--
-- Indexes for table `surcharge_amount`
--
ALTER TABLE `surcharge_amount`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_adminuser`
--
ALTER TABLE `tbl_adminuser`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_history`
--
ALTER TABLE `transaction_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_notification`
--
ALTER TABLE `user_notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_surcharge_amount`
--
ALTER TABLE `user_surcharge_amount`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`users_id`),
  ADD KEY `parking_spot_id` (`parking_spot_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `action_master`
--
ALTER TABLE `action_master`
  MODIFY `action_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;
--
-- AUTO_INCREMENT for table `admin_fund_managment`
--
ALTER TABLE `admin_fund_managment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `admin_notification`
--
ALTER TABLE `admin_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `admin_notification_management`
--
ALTER TABLE `admin_notification_management`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
--
-- AUTO_INCREMENT for table `app_setting`
--
ALTER TABLE `app_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `auto_rent`
--
ALTER TABLE `auto_rent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bank_details`
--
ALTER TABLE `bank_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
--
-- AUTO_INCREMENT for table `bank_receipt`
--
ALTER TABLE `bank_receipt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;
--
-- AUTO_INCREMENT for table `booking_refund`
--
ALTER TABLE `booking_refund`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT for table `contact_us`
--
ALTER TABLE `contact_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `country`
--
ALTER TABLE `country`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `custom_availability`
--
ALTER TABLE `custom_availability`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `device_master`
--
ALTER TABLE `device_master`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;
--
-- AUTO_INCREMENT for table `email_template`
--
ALTER TABLE `email_template`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `general_settings`
--
ALTER TABLE `general_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `menu_master`
--
ALTER TABLE `menu_master`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=870;
--
-- AUTO_INCREMENT for table `page_content`
--
ALTER TABLE `page_content`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `parking_spot`
--
ALTER TABLE `parking_spot`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=152;
--
-- AUTO_INCREMENT for table `parking_spot_images`
--
ALTER TABLE `parking_spot_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;
--
-- AUTO_INCREMENT for table `parking_spot_search_log`
--
ALTER TABLE `parking_spot_search_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT for table `parking_spot_space_managment`
--
ALTER TABLE `parking_spot_space_managment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=211;
--
-- AUTO_INCREMENT for table `payment_history`
--
ALTER TABLE `payment_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;
--
-- AUTO_INCREMENT for table `pricing`
--
ALTER TABLE `pricing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `promocode`
--
ALTER TABLE `promocode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `review_questionnaires`
--
ALTER TABLE `review_questionnaires`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;
--
-- AUTO_INCREMENT for table `surcharge_amount`
--
ALTER TABLE `surcharge_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_adminuser`
--
ALTER TABLE `tbl_adminuser`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `transaction_history`
--
ALTER TABLE `transaction_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;
--
-- AUTO_INCREMENT for table `user_notification`
--
ALTER TABLE `user_notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `user_surcharge_amount`
--
ALTER TABLE `user_surcharge_amount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_fund_managment`
--
ALTER TABLE `admin_fund_managment`
  ADD CONSTRAINT `admin_fund_managment_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `app_setting`
--
ALTER TABLE `app_setting`
  ADD CONSTRAINT `app_setting_ibfk_1` FOREIGN KEY (`tbl_adminuser_id`) REFERENCES `tbl_adminuser` (`id`);

--
-- Constraints for table `bank_details`
--
ALTER TABLE `bank_details`
  ADD CONSTRAINT `bank_details_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `booking_refund`
--
ALTER TABLE `booking_refund`
  ADD CONSTRAINT `booking_refund_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `booking_refund_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `custom_availability`
--
ALTER TABLE `custom_availability`
  ADD CONSTRAINT `custom_availability_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `device_master`
--
ALTER TABLE `device_master`
  ADD CONSTRAINT `device_master_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payment_history`
--
ALTER TABLE `payment_history`
  ADD CONSTRAINT `payment_history_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `booking` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `state`
--
ALTER TABLE `state`
  ADD CONSTRAINT `state_ibfk_1` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_surcharge_amount`
--
ALTER TABLE `user_surcharge_amount`
  ADD CONSTRAINT `user_surcharge_amount_ibfk_1` FOREIGN KEY (`users_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_surcharge_amount_ibfk_2` FOREIGN KEY (`parking_spot_id`) REFERENCES `parking_spot` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
