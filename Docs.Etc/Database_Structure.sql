-- phpMyAdmin SQL Dump
-- http://www.phpmyadmin.net
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `admin_config`
-- 

CREATE TABLE `admin_config` (
  `config_id` varchar(30) NOT NULL,
  `menu_format` text NOT NULL,
  `menu_width` text NOT NULL,
  `use_breadcrumb` text NOT NULL,
  `email_1` text NOT NULL,
  `email_2` text NOT NULL,
  `email_3` text NOT NULL,
  `preferred_delivery` text NOT NULL,
  `delivery_earliest` text NOT NULL,
  `delivery_range` text NOT NULL,
  `count_weekends` text NOT NULL,
  `preferred_required` text NOT NULL,
  `product_id_on` text NOT NULL,
  `paypal_on` text NOT NULL,
  `custom_fields` text NOT NULL,
  `custom_1` text NOT NULL,
  `custom_2` text NOT NULL,
  PRIMARY KEY  (`config_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `admin_config`
-- 

INSERT INTO `admin_config` VALUES ('general_config', 'href_vertical', '300', 'yes', '', '', '', 'no', '1', '7', 'yes', 'no', '1', '1', '', '', '');


-- --------------------------------------------------------

-- 
-- Table structure for table `template_config`
-- 

CREATE TABLE `template_config` (
  `config_id` varchar(30) NOT NULL,
  `font_size` text NOT NULL,
  `font_1` text NOT NULL,
  `font_2` text NOT NULL,
  `font_3` text NOT NULL,
  `font_4` text NOT NULL,
  `font_5` text NOT NULL,
  `font_6` text NOT NULL,
  `font_7` text NOT NULL,
  `font_8` text NOT NULL,
  `font_9` text NOT NULL,
  `logo_image` text NOT NULL,
  `company_name` text NOT NULL,
  `company_font` text NOT NULL,
  `template_wrap` text NOT NULL,
  PRIMARY KEY  (`config_id`)
) TYPE=MyISAM;

-- 
-- Dumping data for table `template_config`
-- 

INSERT INTO `template_config` VALUES ('template', '+0', '35', '23', '20', '18', '16', '15', '14', '12', '11', 'logo.png', 'Company Name', 'LiberationSerif-Italic.ttf', '100%');

-- --------------------------------------------------------

-- 
-- Table structure for table `logs_for_admin`
-- 

CREATE TABLE `logs_for_admin` (
  `id` int(12) NOT NULL auto_increment,
  `log_data` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=100 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `logs_for_user`
-- 

CREATE TABLE `logs_for_user` (
  `id` int(12) NOT NULL auto_increment,
  `log_data` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `user_accounts`
-- 

CREATE TABLE `accounts` (
  `id` int(12) NOT NULL auto_increment,
  `email` text NOT NULL,
  `name` text NOT NULL,
  `store` text NOT NULL,
  `pass` text NOT NULL,
  `address` text NOT NULL,
  `town` text NOT NULL,
  `postal_code` text NOT NULL,
  `country` text NOT NULL,
  `discount` text NOT NULL,
  `reg_key` text NOT NULL,
  `status` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=100 ;



-- --------------------------------------------------------

-- 
-- Table structure for table `orders`
-- 

CREATE TABLE `orders` (
  `id` int(12) NOT NULL auto_increment,
  `user_id` text NOT NULL,
  `name` text NOT NULL,
  `store` text NOT NULL,
  `time_stamp` text NOT NULL,
  `order_type` text NOT NULL,
  `order_data` text NOT NULL,
  `order_subtotal` text NOT NULL,
  `applied_discount` text NOT NULL,
  `order_total` text NOT NULL,
  `shipping_status` text NOT NULL,
  `payment_status` text NOT NULL,
  `paypal_trans_id` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=100 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `category_structure`
-- 

CREATE TABLE `category_structure` (
  `id` int(12) NOT NULL auto_increment,
  `category_name` text NOT NULL,
  `parent_category_id` int(12) NOT NULL default '0',
  `timestamp` varchar(24) NOT NULL default '',
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=100 ;


-- --------------------------------------------------------

-- 
-- Table structure for table `product_list`
-- 

CREATE TABLE `product_list` (
  `id` int(12) NOT NULL auto_increment,
  `product_name` text NOT NULL,
  `product_id` text NOT NULL,
  `unit_price` varchar(10) NOT NULL default '',
  `parent_category_id` int(12) NOT NULL default '0',
  `optional_stock_id` text NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=100 ;

