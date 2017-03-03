-- phpMyAdmin SQL Dump
-- version 4.6.6deb1+deb.cihar.com~xenial.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 03, 2017 at 04:35 PM
-- Server version: 5.7.17-0ubuntu0.16.04.1
-- PHP Version: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ta_activity`
--
DROP DATABASE IF EXISTS `ta_activity`;
CREATE DATABASE IF NOT EXISTS `ta_activity` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_activity`;

-- --------------------------------------------------------

--
-- Table structure for table `activity_index`
--

DROP TABLE IF EXISTS `activity_index`;
CREATE TABLE `activity_index` (
  `activityid` varchar(100) NOT NULL,
  `activityname` varchar(300) NOT NULL,
  `activitydesc` text NOT NULL,
  `appid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING INDEX OF ALL ACTIVITIES';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_index`
--
ALTER TABLE `activity_index`
  ADD PRIMARY KEY (`activityid`);
--
-- Database: `ta_admin`
--
DROP DATABASE IF EXISTS `ta_admin`;
CREATE DATABASE IF NOT EXISTS `ta_admin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_admin`;

-- --------------------------------------------------------

--
-- Table structure for table `admin_roles`
--

DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `uid` varchar(30) NOT NULL COMMENT 'user id for which role is assigned',
  `roleflag` tinyint(4) NOT NULL COMMENT 'flag value 1-admin,2-moderator,etc',
  `roletitle` varchar(400) NOT NULL COMMENT 'role title',
  `permflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed,2-under review,3-blocked',
  `roletime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'time when role was assigned'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER ROLE DETAILS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_roles`
--
ALTER TABLE `admin_roles`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_roles`
--
ALTER TABLE `admin_roles`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_ads`
--
DROP DATABASE IF EXISTS `ta_ads`;
CREATE DATABASE IF NOT EXISTS `ta_ads` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_ads`;

-- --------------------------------------------------------

--
-- Table structure for table `ad_actions`
--

DROP TABLE IF EXISTS `ad_actions`;
CREATE TABLE `ad_actions` (
  `adid` varchar(50) NOT NULL COMMENT 'ADID specifying the AD in which the action is made',
  `containerid` varchar(50) NOT NULL COMMENT 'Container ID specifying the container in which the action is made',
  `actiontype` tinyint(4) NOT NULL COMMENT '1-Share,2-Favorite,3-Rate,4-highlight,5-Key on focus',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person who did this action',
  `actionip` varchar(30) NOT NULL COMMENT 'IP address of the PC from which this action was done',
  `actiontime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time when this action was made'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS OF ALL ACTIONS MADE ON AN AD';

-- --------------------------------------------------------

--
-- Table structure for table `ad_clicks`
--

DROP TABLE IF EXISTS `ad_clicks`;
CREATE TABLE `ad_clicks` (
  `adid` varchar(50) NOT NULL COMMENT 'ID of the AD',
  `containerid` varchar(50) NOT NULL COMMENT 'ID of the container which was clicked',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of person who clicked the AD',
  `clickip` varchar(30) NOT NULL COMMENT 'IP Address of the PC on which the click was made',
  `clicktime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of click'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE WHICH HAVE DETAILS OF THE CLICKS MADE ON ADS';

-- --------------------------------------------------------

--
-- Table structure for table `ad_closings`
--

DROP TABLE IF EXISTS `ad_closings`;
CREATE TABLE `ad_closings` (
  `adid` varchar(50) NOT NULL COMMENT 'ID of the AD',
  `closingprice` double NOT NULL COMMENT 'Closing Price of the AD which has to be paid',
  `paystatus` tinyint(4) NOT NULL COMMENT '1-Payed,2-Pending Payment,3-Processing Payment,4-Partly Payed',
  `paymode` tinyint(4) NOT NULL COMMENT '1-Credit Card,2-Debit Card,3-Web Transfer,4-Cash,5-DD,6-Cheque',
  `paycurrencyid` varchar(50) NOT NULL COMMENT 'Payment Currency ID',
  `paylocid` varchar(50) NOT NULL COMMENT 'ID of the location where account exists',
  `paidamt` double NOT NULL COMMENT 'amount which has been paid so far'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS OF THE AD ONCE THE CAMPAIGN IS OVER';

-- --------------------------------------------------------

--
-- Table structure for table `ad_containers`
--

DROP TABLE IF EXISTS `ad_containers`;
CREATE TABLE `ad_containers` (
  `prodid` varchar(50) NOT NULL COMMENT 'Product ID of the product in which ',
  `cid` varchar(50) NOT NULL COMMENT 'Unique ID of the container',
  `cheight` double NOT NULL COMMENT 'Container Height in pixels',
  `cwidth` double NOT NULL COMMENT 'Container Width in pixels',
  `ccost` double NOT NULL COMMENT 'Container Cost',
  `cnote` text NOT NULL COMMENT 'Note made on this container',
  `cflag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'the time this container was created',
  `ctype` tinyint(4) NOT NULL COMMENT 'the type of container (1-all accepted,2-text only,3-html,4-flash,5-image,6-image and text,7-image and html)',
  `cextflag` tinyint(4) NOT NULL COMMENT '1-container of self,2-container of ext product'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING AD CONTAINER LIST';

-- --------------------------------------------------------

--
-- Table structure for table `ad_publishers`
--

DROP TABLE IF EXISTS `ad_publishers`;
CREATE TABLE `ad_publishers` (
  `extid` varchar(50) NOT NULL,
  `uid` varchar(50) NOT NULL,
  `col_urlid` varchar(50) NOT NULL,
  `payid` varchar(50) NOT NULL,
  `flag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING EXTERNAL PUBLISHERS';

-- --------------------------------------------------------

--
-- Table structure for table `ad_views`
--

DROP TABLE IF EXISTS `ad_views`;
CREATE TABLE `ad_views` (
  `adid` varchar(50) NOT NULL COMMENT 'ID of the AD',
  `containerid` varchar(50) NOT NULL COMMENT 'ID of the container which was viewed',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person viewing this AD',
  `viewip` varchar(30) NOT NULL COMMENT 'IP Address from which the person is viewing this AD',
  `viewtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time when the user viewed the AD'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING AD VIEWS';

-- --------------------------------------------------------

--
-- Table structure for table `ad_web`
--

DROP TABLE IF EXISTS `ad_web`;
CREATE TABLE `ad_web` (
  `adid` varchar(50) NOT NULL COMMENT 'Unique ID for every advertisement',
  `col_containerid` varchar(50) NOT NULL COMMENT 'Container ID collection from Container DB',
  `mediatype` tinyint(4) NOT NULL COMMENT 'Flag value specifying Type of Media (1-Text,2-Image,3-HTML,4-Flash)',
  `adcontent` text NOT NULL COMMENT 'AD Contents (Text or HTML contents or URL of Flash file)',
  `col_timeid` varchar(50) NOT NULL COMMENT 'Collection Time ID from collection DB',
  `adflag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked',
  `adtype` tinyint(4) NOT NULL COMMENT '1-Product,2-Event,3-Page,4-Charity,5-Awareness,6-Company,7-Religious,8-Political,9-Life,10-Others',
  `adcost` double NOT NULL COMMENT 'Cost this AD has earnt Tech Ahoy',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this AD was created',
  `adstyle` text NOT NULL COMMENT 'Extra CSS to be added to this AD',
  `earntype` tinyint(4) NOT NULL COMMENT '1-Cost Per Click,2-Cost Per View,3-Cost for time,4-Cost per action',
  `socialact` tinyint(4) NOT NULL COMMENT '1-show social activity next to ad,2-dont show social activity',
  `incometarget` double NOT NULL COMMENT '-1-ignore,Others-Show this AD only to people who have this min income in Rs.',
  `views` bigint(20) NOT NULL COMMENT 'No. of views of this ad',
  `clicks` bigint(20) NOT NULL COMMENT 'No. of clicks on this AD',
  `actions` bigint(20) NOT NULL COMMENT 'No. of actions made on this AD',
  `campaignname` varchar(300) NOT NULL COMMENT 'Name of this AD Campaign',
  `campbudgetpd` double NOT NULL COMMENT 'Campaign budget per day in Rupees',
  `col_jobtargetid` varchar(50) NOT NULL COMMENT '-1-Ignore,Others-Collection ID specifying the JOB to be targeted'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING THE ADS DISPLAYED IN THE WEBS';
--
-- Database: `ta_alerts`
--
DROP DATABASE IF EXISTS `ta_alerts`;
CREATE DATABASE IF NOT EXISTS `ta_alerts` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_alerts`;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE `notifications` (
  `notifyid` varchar(100) NOT NULL COMMENT 'unique id for every notification',
  `uid` varchar(50) NOT NULL COMMENT 'user id of person to whom notification has to be sent',
  `notifytype` tinyint(4) NOT NULL COMMENT 'flag value specifying type of notification (1-post,2-message,3-friend request received,4-new addition to group,5-tag,6-shared,7-starred alerts,8-friend request accepted,9-added to thread,10-group request accepted,11-group join invite,12-comment on post,13-upvote post)',
  `notifytext` text NOT NULL COMMENT 'text or html code to be displayed as notification',
  `notifyicon` varchar(400) NOT NULL COMMENT 'icon to be displayed near notification text',
  `notifystatus` tinyint(4) NOT NULL COMMENT 'flag value specifying notification status (1-read,2-unread)',
  `notifytime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'time in which this notification was received',
  `notifylink` varchar(400) NOT NULL COMMENT 'URL of link to which the notification has to redirect when clicked',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID from JSON db',
  `elid` varchar(50) NOT NULL COMMENT 'Element ID',
  `cnt` int(11) NOT NULL DEFAULT '0' COMMENT 'Count of notification'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL NOTIFICATION ALERTS';

-- --------------------------------------------------------

--
-- Table structure for table `subalerts_products`
--

DROP TABLE IF EXISTS `subalerts_products`;
CREATE TABLE `subalerts_products` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `pid` varchar(50) NOT NULL COMMENT 'product id',
  `uid` varchar(30) NOT NULL COMMENT 'user id',
  `subtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of subscription',
  `itemid` varchar(50) NOT NULL COMMENT 'item id to which the user want to subscribe for alerts'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF SUBSCRIBED USERS TO ALERTS';

-- --------------------------------------------------------

--
-- Table structure for table `subalerts_users`
--

DROP TABLE IF EXISTS `subalerts_users`;
CREATE TABLE `subalerts_users` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `uid` varchar(30) NOT NULL COMMENT 'uid of person subscribing',
  `suid` varchar(30) NOT NULL COMMENT 'uid of person being subscribed to',
  `stime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of subscription',
  `sflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed 2-under review 3-blocked'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SUBSCRIPTION DETAILS (USERS)';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notifyid`);

--
-- Indexes for table `subalerts_products`
--
ALTER TABLE `subalerts_products`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `subalerts_users`
--
ALTER TABLE `subalerts_users`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `subalerts_products`
--
ALTER TABLE `subalerts_products`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';
--
-- AUTO_INCREMENT for table `subalerts_users`
--
ALTER TABLE `subalerts_users`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_apps`
--
DROP DATABASE IF EXISTS `ta_apps`;
CREATE DATABASE IF NOT EXISTS `ta_apps` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_apps`;

-- --------------------------------------------------------

--
-- Table structure for table `app_developers`
--

DROP TABLE IF EXISTS `app_developers`;
CREATE TABLE `app_developers` (
  `devid` varchar(50) NOT NULL COMMENT 'developer id',
  `uid` varchar(50) NOT NULL COMMENT 'user id of the developer',
  `devflag` tinyint(4) NOT NULL COMMENT 'flag value specifying developer permissions (1-allowed,2-under review,3-blocked)',
  `devtype` tinyint(4) NOT NULL COMMENT 'developer type/role (1-owner,2-chief developer,3-developer,4-tester)',
  `devtotrating` bigint(20) NOT NULL COMMENT 'total rating of the developer',
  `devnoofrating` bigint(20) NOT NULL COMMENT 'no of rating of the developer'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='DEVELOPER TABLE HAVING COLLECTION OF ALL DEVELOPER INFORMATION';

-- --------------------------------------------------------

--
-- Table structure for table `app_info`
--

DROP TABLE IF EXISTS `app_info`;
CREATE TABLE `app_info` (
  `appid` varchar(50) NOT NULL COMMENT 'application id',
  `appkey` varchar(50) NOT NULL COMMENT 'application secret developer key',
  `appname` varchar(300) NOT NULL COMMENT 'application name',
  `appdesc` text NOT NULL COMMENT 'application description',
  `devid` varchar(50) NOT NULL COMMENT 'developer id',
  `appflag` tinyint(4) NOT NULL COMMENT 'flag value specifying permissions (1-allowed,2-under review,3-blocked)',
  `apptype` tinyint(4) NOT NULL COMMENT 'flag value specifying application type (1-ta application,2-external application,3-partner application)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL TECH AHOY APPLICATION DETAILS';
--
-- Database: `ta_blacklists`
--
DROP DATABASE IF EXISTS `ta_blacklists`;
CREATE DATABASE IF NOT EXISTS `ta_blacklists` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_blacklists`;

-- --------------------------------------------------------

--
-- Table structure for table `block_info`
--

DROP TABLE IF EXISTS `block_info`;
CREATE TABLE `block_info` (
  `blockid` varchar(50) NOT NULL COMMENT 'Unique blockid for every block',
  `blockdesc` text NOT NULL COMMENT 'Description regarding the block',
  `blocktime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of block',
  `blockuid` varchar(50) NOT NULL COMMENT 'User ID of person who blocked it',
  `blocktype` tinyint(4) NOT NULL COMMENT 'Flag value specifying type 1-SPAM,2-EXPLICIT CONTENT,3-NOT RELATED,4-HURTING,5-DUPLICATE,6-INVALID,7-OTHERS',
  `itemid` varchar(50) NOT NULL COMMENT 'ID of item which is blocked',
  `itemtype` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Flag value specifying type of item which is blocked (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location,15-subbot_subscription,16-subbot_submail)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF ITEMS BEING BLOCKED WITH RESPECTIVE REASONS';

-- --------------------------------------------------------

--
-- Table structure for table `reports_info`
--

DROP TABLE IF EXISTS `reports_info`;
CREATE TABLE `reports_info` (
  `itemid` varchar(50) NOT NULL COMMENT 'The Unique ID of the item being reported',
  `reportid` varchar(50) NOT NULL COMMENT 'Unique ID for every report',
  `repuid` varchar(50) NOT NULL COMMENT 'UID of person reporting the item',
  `reason` text NOT NULL COMMENT 'Reason for report',
  `reptime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Report time',
  `refurl` varchar(300) NOT NULL COMMENT 'Reference URL (if any)',
  `itemtype` tinyint(4) NOT NULL COMMENT 'Flag value specifying item being reported (1-user,2-conversations,3-ad,4-group,5-gallery,6-media in a gallery,7-external link,8-short url,9-post,10-comment,11-alert,12-help content,13-ext app,14-location,15-subbot_subscription,16-subbot_submail)',
  `reasontype` tinyint(4) NOT NULL COMMENT 'Flag value specifying report reason (1-SPAM,2-ADULT,3-ILLEGAL,4-COPYRIGHT,5-MOCK,6-VIRUS,7-DUPLICATE,8-FAKE,9-INAPPROPRIATE,10-Others)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING REPORTS MADE BY USERS ON ITEMS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `block_info`
--
ALTER TABLE `block_info`
  ADD PRIMARY KEY (`blockid`);

--
-- Indexes for table `reports_info`
--
ALTER TABLE `reports_info`
  ADD PRIMARY KEY (`reportid`);
--
-- Database: `ta_category`
--
DROP DATABASE IF EXISTS `ta_category`;
CREATE DATABASE IF NOT EXISTS `ta_category` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_category`;

-- --------------------------------------------------------

--
-- Table structure for table `currency_mainunits`
--

DROP TABLE IF EXISTS `currency_mainunits`;
CREATE TABLE `currency_mainunits` (
  `currencyid` varchar(50) NOT NULL COMMENT 'Unique ID for currency',
  `curname` varchar(300) NOT NULL COMMENT 'Currency Name',
  `curval` double NOT NULL COMMENT 'Currency value with base as rupee (eg.if 1 dollar=57 rupees,curval-57,if 1 cur=0.5 rupees,curval-0.5)',
  `cursymbol` varchar(400) NOT NULL COMMENT 'URL of the picture showing the currency symbol to be used wherever necessary',
  `locationid` varchar(50) NOT NULL COMMENT 'ID of the location where this currency belongs'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DATABASE OF ALL CURRENCIES OF THE WORLD';

-- --------------------------------------------------------

--
-- Table structure for table `currency_subunits`
--

DROP TABLE IF EXISTS `currency_subunits`;
CREATE TABLE `currency_subunits` (
  `subcurid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the subcurrency',
  `currencyid` varchar(50) NOT NULL COMMENT 'Currency ID from currency db',
  `subcurval` double NOT NULL COMMENT 'Currency Conversion from real value',
  `subcurname` varchar(400) NOT NULL COMMENT 'Name of the subcurrency'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SUB-CURRENCIES';

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `langcode` varchar(6) NOT NULL COMMENT 'ISO code of the language',
  `language` varchar(50) NOT NULL COMMENT 'The full language name'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS OF ALL LANGUAGES';

-- --------------------------------------------------------

--
-- Table structure for table `life_routine`
--

DROP TABLE IF EXISTS `life_routine`;
CREATE TABLE `life_routine` (
  `actid` varchar(50) NOT NULL,
  `pactid` varchar(50) NOT NULL,
  `actname` varchar(200) NOT NULL,
  `actpic` varchar(400) NOT NULL,
  `actdesc` text NOT NULL,
  `acturl` varchar(400) NOT NULL,
  `actflag` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL ACTIVITIES PERFORMED';

-- --------------------------------------------------------

--
-- Table structure for table `measure_subunits`
--

DROP TABLE IF EXISTS `measure_subunits`;
CREATE TABLE `measure_subunits` (
  `subunitid` varchar(50) NOT NULL COMMENT 'Unique ID specifying SUB Unit ID',
  `subunitname` varchar(400) NOT NULL COMMENT 'Sub Unit Name',
  `convvalue` double NOT NULL COMMENT 'Conversion value ',
  `subunitsymbol` varchar(30) NOT NULL COMMENT 'Symbol of the sub unit',
  `mainunitid` varchar(50) NOT NULL COMMENT 'ID of the Main Unit'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SUB UNITS';

-- --------------------------------------------------------

--
-- Table structure for table `measure_types`
--

DROP TABLE IF EXISTS `measure_types`;
CREATE TABLE `measure_types` (
  `measureid` varchar(50) NOT NULL COMMENT 'Unique ID specifying measure',
  `measurename` varchar(400) NOT NULL COMMENT 'Name of the measure',
  `measuredef` text NOT NULL COMMENT 'Definition for the measure'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `measure_units`
--

DROP TABLE IF EXISTS `measure_units`;
CREATE TABLE `measure_units` (
  `unitid` varchar(50) NOT NULL COMMENT 'Unique ID for the unit',
  `fullunit` varchar(150) NOT NULL COMMENT 'Full unit expansion',
  `symbol` varchar(15) NOT NULL COMMENT 'Symbol for this unit',
  `dimension` varchar(20) NOT NULL COMMENT 'Dimension for this unit (Format:M--1,L-0,T-1)',
  `measureid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the measure',
  `unitdefinition` text NOT NULL COMMENT 'Definition for the '
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL UNITS WITH THEIR APPROPRIATE DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `program_cats`
--

DROP TABLE IF EXISTS `program_cats`;
CREATE TABLE `program_cats` (
  `progcatid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the CAT ID of the program',
  `catname` varchar(400) NOT NULL COMMENT 'Name of the category',
  `parcatid` varchar(50) NOT NULL COMMENT 'ID of the parent category'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='HAS CATEGORIES OF ALL PROGRAMS(eg.pic editing,word process)';

-- --------------------------------------------------------

--
-- Table structure for table `religion`
--

DROP TABLE IF EXISTS `religion`;
CREATE TABLE `religion` (
  `religid` varchar(50) NOT NULL COMMENT 'ID for the Religion',
  `label` varchar(100) NOT NULL COMMENT 'Label for the Religion',
  `religpic` varchar(150) NOT NULL COMMENT 'Picture or symbol for the religion',
  `religmeta` text NOT NULL COMMENT 'Other metadata regarding the religion'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS OF RELIGIONS';

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `skillid` varchar(50) NOT NULL COMMENT 'Skill ID of the skill being specified',
  `parskillid` varchar(50) NOT NULL COMMENT 'Skill ID of the parent skill. Empty if parent',
  `label` varchar(250) NOT NULL COMMENT 'Label for the skill',
  `skillmeta` text NOT NULL COMMENT 'Metadata for this skill',
  `skillico` varchar(200) NOT NULL COMMENT 'URL of icon for this skill'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING VARIOUS SKILLS THAT A USER CAN HAVE';

-- --------------------------------------------------------

--
-- Table structure for table `tags_post`
--

DROP TABLE IF EXISTS `tags_post`;
CREATE TABLE `tags_post` (
  `tagid` varchar(50) NOT NULL COMMENT 'Tag ID',
  `tagname` varchar(200) NOT NULL COMMENT 'Tag Name',
  `details` text NOT NULL COMMENT 'Tag Description',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of adding tag',
  `usrid` varchar(50) NOT NULL COMMENT 'User ID of the person adding the tag',
  `tagpic` varchar(300) NOT NULL COMMENT 'URL of the picture to this tag'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING TAG DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `workedu`
--

DROP TABLE IF EXISTS `workedu`;
CREATE TABLE `workedu` (
  `typeid` varchar(50) NOT NULL COMMENT 'Unique ID specifying work or education type',
  `name` varchar(400) NOT NULL COMMENT 'Name given to the respective Work/Education',
  `parid` varchar(50) NOT NULL COMMENT 'Parent ID of the Work or education,empty if parent',
  `notes` text NOT NULL COMMENT 'Notes about this work/edu type'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING NATURE OF ALL WORK AND EDUCATION WITH VARIOUS CATEGORIES AND SUBCAT';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `currency_mainunits`
--
ALTER TABLE `currency_mainunits`
  ADD PRIMARY KEY (`currencyid`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`langcode`);

--
-- Indexes for table `life_routine`
--
ALTER TABLE `life_routine`
  ADD PRIMARY KEY (`actid`);

--
-- Indexes for table `religion`
--
ALTER TABLE `religion`
  ADD PRIMARY KEY (`religid`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skillid`);

--
-- Indexes for table `tags_post`
--
ALTER TABLE `tags_post`
  ADD PRIMARY KEY (`tagid`);

--
-- Indexes for table `workedu`
--
ALTER TABLE `workedu`
  ADD PRIMARY KEY (`typeid`);
--
-- Database: `ta_collection`
--
DROP DATABASE IF EXISTS `ta_collection`;
CREATE DATABASE IF NOT EXISTS `ta_collection` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_collection`;

-- --------------------------------------------------------

--
-- Table structure for table `achievements`
--

DROP TABLE IF EXISTS `achievements`;
CREATE TABLE `achievements` (
  `col_achieveid` varchar(50) NOT NULL COMMENT 'Achievement Collection ID',
  `achievementid` varchar(50) NOT NULL COMMENT 'Achievement ID for this achievement',
  `label` varchar(200) NOT NULL COMMENT 'Label for this achievement',
  `notes` text NOT NULL COMMENT 'Description of this achievement',
  `achievetime` varchar(30) NOT NULL COMMENT 'Time of this achievement',
  `galid` varchar(50) NOT NULL COMMENT 'Gallery ID for this achievement'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ACHIEVEMENTS OF USERS';

-- --------------------------------------------------------

--
-- Table structure for table `activitylog`
--

DROP TABLE IF EXISTS `activitylog`;
CREATE TABLE `activitylog` (
  `col_activityid` varchar(50) NOT NULL COMMENT 'Unique collection ID',
  `activityid` varchar(50) NOT NULL COMMENT 'Activity ID from activity db'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `adcontainers`
--

DROP TABLE IF EXISTS `adcontainers`;
CREATE TABLE `adcontainers` (
  `col_contid` varchar(50) NOT NULL COMMENT 'Unique ID specifying container ID',
  `contid` varchar(50) NOT NULL COMMENT 'AD Container ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING AD CONTAINERS COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `age`
--

DROP TABLE IF EXISTS `age`;
CREATE TABLE `age` (
  `col_ageid` varchar(50) NOT NULL COMMENT 'Unique ID specifying Age Limits',
  `minage` int(11) NOT NULL COMMENT 'Minimum Age of the person',
  `maxage` int(11) NOT NULL COMMENT 'Maximum Age of the person'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `audience`
--

DROP TABLE IF EXISTS `audience`;
CREATE TABLE `audience` (
  `col_audienceid` varchar(50) NOT NULL COMMENT 'Audience collection ID',
  `audienceid` varchar(50) NOT NULL COMMENT 'Audience ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `contactno`
--

DROP TABLE IF EXISTS `contactno`;
CREATE TABLE `contactno` (
  `col_cnoid` varchar(50) NOT NULL COMMENT 'Collection ID for contact no',
  `cno` varchar(30) NOT NULL COMMENT 'Contact No',
  `ctype` tinyint(4) NOT NULL COMMENT '1-land line, 2- Mobile',
  `clabel` varchar(50) NOT NULL COMMENT 'Label for this number'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING COLLECTION OF CONTACT NUMBERS';

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `col_cid` varchar(50) NOT NULL COMMENT 'Collection ID of country',
  `csname` varchar(5) NOT NULL COMMENT 'Country short name'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING COLLECTION OF COUNTRIES';

-- --------------------------------------------------------

--
-- Table structure for table `email`
--

DROP TABLE IF EXISTS `email`;
CREATE TABLE `email` (
  `col_emailid` varchar(50) NOT NULL COMMENT 'Collection ID of email',
  `emailaddr` varchar(300) NOT NULL COMMENT 'Email address'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING COLLECTION OF EMAIL ADDRESSES';

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

DROP TABLE IF EXISTS `files`;
CREATE TABLE `files` (
  `col_fileid` varchar(50) NOT NULL COMMENT 'Collection ID of files',
  `fileurl` varchar(500) NOT NULL COMMENT 'URL of the file'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING FILE COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
CREATE TABLE `groups` (
  `col_gpid` varchar(50) NOT NULL COMMENT 'Group collection ID',
  `gpid` varchar(50) NOT NULL COMMENT 'Group ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='GROUP COLLECTION TABLE';

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
CREATE TABLE `languages` (
  `collangid` varchar(50) NOT NULL COMMENT 'Language collection id',
  `langid` varchar(50) NOT NULL COMMENT 'language id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LANGUAGE COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
  `col_linkid` varchar(50) NOT NULL COMMENT 'Collection ID specifying the link collection',
  `linkid` varchar(50) NOT NULL COMMENT 'Link ID from linkdb'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Table having link collections';

-- --------------------------------------------------------

--
-- Table structure for table `lists`
--

DROP TABLE IF EXISTS `lists`;
CREATE TABLE `lists` (
  `col_listid` varchar(50) NOT NULL COMMENT 'Unique ID specifying collection id',
  `listid` varchar(50) NOT NULL COMMENT 'List ID from List DB'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING COLLECTION OF ALL LISTS';

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

DROP TABLE IF EXISTS `location`;
CREATE TABLE `location` (
  `col_locationid` varchar(50) NOT NULL COMMENT 'Unique ID specifying Collection ID',
  `locationid` varchar(50) NOT NULL COMMENT 'Location ID from Location DB',
  `label` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING SET OF ALL LOCATIONS';

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE `media` (
  `col_mediaid` varchar(50) NOT NULL COMMENT 'Collection ID of media',
  `galid` varchar(50) NOT NULL COMMENT 'Gallery ID',
  `mediaid` varchar(50) NOT NULL COMMENT 'Media ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING MEDIA COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_mailcats`
--

DROP TABLE IF EXISTS `p_sb_mailcats`;
CREATE TABLE `p_sb_mailcats` (
  `col_mailcatid` varchar(50) NOT NULL COMMENT 'Collection ID of the mail categories',
  `mailcatid` varchar(50) NOT NULL COMMENT 'ID of the mail category'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING MAIL CATEGORY COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_subscriptions`
--

DROP TABLE IF EXISTS `p_sb_subscriptions`;
CREATE TABLE `p_sb_subscriptions` (
  `col_subid` varchar(50) NOT NULL COMMENT 'Collection ID of subscription',
  `subid` varchar(50) NOT NULL COMMENT 'Subscription ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING SUBSCRIPTION COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `programs`
--

DROP TABLE IF EXISTS `programs`;
CREATE TABLE `programs` (
  `col_progid` varchar(50) NOT NULL,
  `progid` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF ALL PROGRAM COLLECTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `relationship`
--

DROP TABLE IF EXISTS `relationship`;
CREATE TABLE `relationship` (
  `colrelid` varchar(50) NOT NULL COMMENT 'Unique ID specifying relationship status',
  `relflag` tinyint(4) NOT NULL COMMENT 'Flag value specifying status (1-Single,2-Married,3-In a relationship,4-Broken Up,5-In Love)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING RELATIONSHIP COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `reppoints`
--

DROP TABLE IF EXISTS `reppoints`;
CREATE TABLE `reppoints` (
  `col_reppointid` varchar(50) NOT NULL COMMENT 'Collection ID of reputation points',
  `minrep` double NOT NULL COMMENT 'Minimum Reputation',
  `maxrep` double NOT NULL COMMENT 'Maximum Reputation'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING REPUTATION POINT DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `routine`
--

DROP TABLE IF EXISTS `routine`;
CREATE TABLE `routine` (
  `col_routineid` varchar(50) NOT NULL COMMENT 'Unique ID specifying this collection',
  `routineid` varchar(50) NOT NULL COMMENT 'Routine ID from routinedb'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING COLLECTION OF ALL ROUTINES';

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
CREATE TABLE `skills` (
  `col_skillid` varchar(50) NOT NULL COMMENT 'Skill collection ID',
  `skillid` varchar(50) NOT NULL COMMENT 'Skill ID from skill db'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING SKILL COLLECTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `col_sid` varchar(50) NOT NULL COMMENT 'Collection ID of State',
  `sid` int(11) NOT NULL COMMENT 'State ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING COLLECTION OF STATES';

-- --------------------------------------------------------

--
-- Table structure for table `tagpost`
--

DROP TABLE IF EXISTS `tagpost`;
CREATE TABLE `tagpost` (
  `col_tagid` varchar(50) NOT NULL COMMENT 'TAG collection ID',
  `tagid` varchar(50) NOT NULL COMMENT 'TAG ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `time`
--

DROP TABLE IF EXISTS `time`;
CREATE TABLE `time` (
  `col_timeid` varchar(50) NOT NULL COMMENT 'Unique collection time ID',
  `timeid` varchar(50) NOT NULL COMMENT 'Unique ID for every time',
  `stime` varchar(30) NOT NULL COMMENT 'Start Time',
  `etime` varchar(30) NOT NULL COMMENT 'End Time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING TIME COLLECTION';

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `col_uid` varchar(50) NOT NULL COMMENT 'Unique collection ID',
  `uid` varchar(50) NOT NULL COMMENT 'User ID from USER DB'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER COLLECTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `workedu`
--

DROP TABLE IF EXISTS `workedu`;
CREATE TABLE `workedu` (
  `col_typeid` varchar(50) NOT NULL COMMENT 'Unique ID specifying work and education type collection',
  `typeid` varchar(50) NOT NULL COMMENT 'Unique ID specifying work and education type'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING WORK AND EDUCATION TYPE COLLECTION';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `achievements`
--
ALTER TABLE `achievements`
  ADD PRIMARY KEY (`col_achieveid`,`achievementid`);

--
-- Indexes for table `age`
--
ALTER TABLE `age`
  ADD PRIMARY KEY (`col_ageid`,`minage`,`maxage`);

--
-- Indexes for table `contactno`
--
ALTER TABLE `contactno`
  ADD PRIMARY KEY (`col_cnoid`,`cno`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`col_cid`,`csname`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`col_gpid`,`gpid`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`collangid`,`langid`);

--
-- Indexes for table `links`
--
ALTER TABLE `links`
  ADD PRIMARY KEY (`col_linkid`,`linkid`);

--
-- Indexes for table `lists`
--
ALTER TABLE `lists`
  ADD PRIMARY KEY (`col_listid`,`listid`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`col_locationid`,`locationid`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`col_mediaid`,`mediaid`);

--
-- Indexes for table `reppoints`
--
ALTER TABLE `reppoints`
  ADD PRIMARY KEY (`col_reppointid`,`minrep`,`maxrep`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`col_skillid`,`skillid`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`col_sid`,`sid`);

--
-- Indexes for table `tagpost`
--
ALTER TABLE `tagpost`
  ADD PRIMARY KEY (`col_tagid`,`tagid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`col_uid`,`uid`);

--
-- Indexes for table `workedu`
--
ALTER TABLE `workedu`
  ADD PRIMARY KEY (`col_typeid`,`typeid`);
--
-- Database: `ta_conversations`
--
DROP DATABASE IF EXISTS `ta_conversations`;
CREATE DATABASE IF NOT EXISTS `ta_conversations` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_conversations`;

-- --------------------------------------------------------

--
-- Table structure for table `message_attachment`
--

DROP TABLE IF EXISTS `message_attachment`;
CREATE TABLE `message_attachment` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `tid` varchar(50) NOT NULL COMMENT 'thread id',
  `attachid` varchar(50) NOT NULL COMMENT 'id of attachment',
  `attachurl` varchar(500) NOT NULL COMMENT 'url of the attachment',
  `uid` varchar(30) NOT NULL COMMENT 'uid of person posting attachment',
  `attachtype` varchar(10) NOT NULL COMMENT 'extension of the attachment file',
  `attachflag` tinyint(4) NOT NULL COMMENT 'flag value - 1-allowed 2-under review 3-blocked',
  `msgid` varchar(50) NOT NULL COMMENT 'message id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE CONTAINING ALL ATTACHMENT RELATED DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `message_content`
--

DROP TABLE IF EXISTS `message_content`;
CREATE TABLE `message_content` (
  `tid` varchar(50) NOT NULL COMMENT 'thread id',
  `msgid` varchar(50) NOT NULL COMMENT 'Message ID',
  `msg` text NOT NULL COMMENT 'message text',
  `fuid` varchar(30) NOT NULL COMMENT 'uid of person posting this message',
  `msgtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of message creation',
  `col_mediaid` varchar(50) DEFAULT NULL COMMENT 'media collection id of attachments (empty if no attachments)',
  `tagid` varchar(50) NOT NULL COMMENT 'id of tagged elements (default-0-no tags)',
  `msgmode` tinyint(4) NOT NULL COMMENT 'mode of this message (appid sending this message)',
  `deststate` int(11) NOT NULL COMMENT 'number of people who read this message',
  `msgflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed 2-under review 3-blocked',
  `replyto` varchar(50) NOT NULL COMMENT 'empty if main message,msgid if reply,-1 if comment',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID of the rating to be given to the thread contents',
  `embedid` varchar(50) DEFAULT NULL COMMENT 'Embed ID for embeds'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL THE MESSAGE CONTENTS';

-- --------------------------------------------------------

--
-- Table structure for table `message_embeds`
--

DROP TABLE IF EXISTS `message_embeds`;
CREATE TABLE `message_embeds` (
  `sno` bigint(20) NOT NULL COMMENT 'Serial no',
  `embedid` varchar(50) NOT NULL COMMENT 'Embed ID unique for embeds',
  `servcode` tinyint(4) NOT NULL COMMENT 'Service code (1-Youtube)',
  `code` text NOT NULL COMMENT 'Code from the service',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'TIme embed was added'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING EMBED DETAILS FOR POSTS';

-- --------------------------------------------------------

--
-- Table structure for table `message_featured`
--

DROP TABLE IF EXISTS `message_featured`;
CREATE TABLE `message_featured` (
  `tid` varchar(50) NOT NULL COMMENT 'Thread ID',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of featuring the thread',
  `flag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Featured,2-Not Featured',
  `flvl` bigint(20) NOT NULL DEFAULT '1' COMMENT 'Featured Level'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING FEATURED MESSAGES';

-- --------------------------------------------------------

--
-- Table structure for table `message_filter`
--

DROP TABLE IF EXISTS `message_filter`;
CREATE TABLE `message_filter` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `filterid` varchar(50) NOT NULL COMMENT 'filter id',
  `tid` varchar(50) NOT NULL COMMENT 'thread id',
  `uid` varchar(30) NOT NULL COMMENT 'uid of person creating the filter',
  `filterlabel` varchar(300) NOT NULL COMMENT 'label of filter',
  `filteremail` varchar(300) NOT NULL COMMENT 'email address for filter',
  `filtercontent` text NOT NULL COMMENT 'part of message to be filtered',
  `filtersubject` varchar(400) NOT NULL COMMENT 'subject of message to be filtered'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='FILTER TABLE FOR MESSAGES (LIKE LABELS)';

-- --------------------------------------------------------

--
-- Table structure for table `message_incoming`
--

DROP TABLE IF EXISTS `message_incoming`;
CREATE TABLE `message_incoming` (
  `tid` varchar(50) NOT NULL COMMENT 'Thread ID',
  `ruid` varchar(50) NOT NULL COMMENT 'Receiver UID',
  `rtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Receiving time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message_outline`
--

DROP TABLE IF EXISTS `message_outline`;
CREATE TABLE `message_outline` (
  `tid` varchar(50) NOT NULL COMMENT 'threadid',
  `subject` varchar(400) NOT NULL COMMENT 'subject of thread',
  `fid` varchar(30) NOT NULL COMMENT 'uid starting thread',
  `audienceid` varchar(50) NOT NULL COMMENT 'Audience of this thread',
  `msgtype` tinyint(4) NOT NULL COMMENT 'flag value 1-normal, 2-chat, 3-sent as email, 4-profile post, 5-group post, 6-customer support,7-item comments',
  `threadpic` varchar(400) NOT NULL COMMENT 'picture for this thread',
  `threadflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed, 2-under review, 3-blocked,4-not started',
  `appid` varchar(50) NOT NULL COMMENT 'APPID of the app creating this thread',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID of the thread',
  `lastupdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last update to the message in this thread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE OUTLINING MESSAGES (ALL TYPES)';

-- --------------------------------------------------------

--
-- Table structure for table `message_readstatus`
--

DROP TABLE IF EXISTS `message_readstatus`;
CREATE TABLE `message_readstatus` (
  `uid` varchar(30) NOT NULL COMMENT 'user id',
  `threadid` varchar(50) NOT NULL COMMENT 'thread id',
  `msgid` varchar(50) NOT NULL COMMENT 'message id',
  `readstatus` tinyint(4) NOT NULL COMMENT 'flag value,1-read,2-unread,3-ignored'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING MESSAGE READ STATUS';

-- --------------------------------------------------------

--
-- Table structure for table `message_shares`
--

DROP TABLE IF EXISTS `message_shares`;
CREATE TABLE `message_shares` (
  `tid` varchar(50) NOT NULL COMMENT 'The original thread ID',
  `ntid` varchar(50) NOT NULL COMMENT 'The New thread ID',
  `msgid` varchar(50) NOT NULL COMMENT 'The original Message ID',
  `nmsgid` varchar(50) NOT NULL COMMENT 'The new Message ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING SHARE DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `message_spam`
--

DROP TABLE IF EXISTS `message_spam`;
CREATE TABLE `message_spam` (
  `tid` varchar(50) NOT NULL COMMENT 'thread id',
  `msgid` varchar(50) NOT NULL COMMENT 'message id',
  `spamreason` text NOT NULL COMMENT 'reason for marking it spam',
  `repid` varchar(30) NOT NULL COMMENT 'uid reporting it spam'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SPAM DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `message_tags`
--

DROP TABLE IF EXISTS `message_tags`;
CREATE TABLE `message_tags` (
  `tid` varchar(50) NOT NULL COMMENT 'Thread ID',
  `col_ptagid` varchar(50) NOT NULL COMMENT 'Collection ID of post tag',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of adding tag to post'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `thread_comments`
--

DROP TABLE IF EXISTS `thread_comments`;
CREATE TABLE `thread_comments` (
  `mtid` varchar(50) NOT NULL COMMENT 'Main thread ID',
  `ctid` varchar(50) NOT NULL COMMENT 'Comment thread ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message_attachment`
--
ALTER TABLE `message_attachment`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `message_content`
--
ALTER TABLE `message_content`
  ADD PRIMARY KEY (`tid`,`msgid`);

--
-- Indexes for table `message_embeds`
--
ALTER TABLE `message_embeds`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `embedid` (`embedid`);

--
-- Indexes for table `message_featured`
--
ALTER TABLE `message_featured`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `message_filter`
--
ALTER TABLE `message_filter`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `message_incoming`
--
ALTER TABLE `message_incoming`
  ADD PRIMARY KEY (`tid`,`ruid`);

--
-- Indexes for table `message_outline`
--
ALTER TABLE `message_outline`
  ADD PRIMARY KEY (`tid`);

--
-- Indexes for table `message_readstatus`
--
ALTER TABLE `message_readstatus`
  ADD PRIMARY KEY (`uid`,`threadid`,`msgid`);

--
-- Indexes for table `message_shares`
--
ALTER TABLE `message_shares`
  ADD PRIMARY KEY (`ntid`,`nmsgid`);

--
-- Indexes for table `message_spam`
--
ALTER TABLE `message_spam`
  ADD PRIMARY KEY (`tid`,`msgid`,`repid`);

--
-- Indexes for table `message_tags`
--
ALTER TABLE `message_tags`
  ADD PRIMARY KEY (`tid`,`col_ptagid`);

--
-- Indexes for table `thread_comments`
--
ALTER TABLE `thread_comments`
  ADD PRIMARY KEY (`mtid`,`ctid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message_attachment`
--
ALTER TABLE `message_attachment`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';
--
-- AUTO_INCREMENT for table `message_embeds`
--
ALTER TABLE `message_embeds`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Serial no';
--
-- AUTO_INCREMENT for table `message_filter`
--
ALTER TABLE `message_filter`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_errors`
--
DROP DATABASE IF EXISTS `ta_errors`;
CREATE DATABASE IF NOT EXISTS `ta_errors` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_errors`;

-- --------------------------------------------------------

--
-- Table structure for table `errorcodes`
--

DROP TABLE IF EXISTS `errorcodes`;
CREATE TABLE `errorcodes` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `errname` varchar(200) NOT NULL COMMENT 'error name',
  `errdesc` text NOT NULL COMMENT 'error description',
  `errcode` varchar(200) NOT NULL COMMENT 'error code',
  `errcallback1` varchar(300) NOT NULL COMMENT 'error callback1 (button 1''s url)',
  `errcallbacktext1` varchar(200) NOT NULL COMMENT 'error callback1 text (text to be displayed in btn 1)',
  `errcallback2` varchar(300) NOT NULL COMMENT 'error callback2 (button 2''s url)',
  `errcallbacktext2` varchar(200) NOT NULL COMMENT 'error callback2 text (text to be displayed in btn 2)',
  `errtitle` varchar(500) NOT NULL COMMENT 'error title',
  `errpriority` varchar(3) NOT NULL DEFAULT '1' COMMENT 'error priority flag (1-terminate script, 2-continue running script',
  `appid` varchar(200) NOT NULL COMMENT 'application where the error belongs',
  `file` varchar(400) NOT NULL COMMENT 'file of the application where error occured'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL ERROR HANDLING & RELATED INFORMATIONS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `errorcodes`
--
ALTER TABLE `errorcodes`
  ADD PRIMARY KEY (`sno`),
  ADD UNIQUE KEY `errcode` (`errcode`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `errorcodes`
--
ALTER TABLE `errorcodes`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_extconnect`
--
DROP DATABASE IF EXISTS `ta_extconnect`;
CREATE DATABASE IF NOT EXISTS `ta_extconnect` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_extconnect`;

-- --------------------------------------------------------

--
-- Table structure for table `linkdb`
--

DROP TABLE IF EXISTS `linkdb`;
CREATE TABLE `linkdb` (
  `url` varchar(500) NOT NULL COMMENT 'URL of the link',
  `linkid` varchar(50) NOT NULL COMMENT 'unique id for every link',
  `linkvisits` bigint(20) NOT NULL DEFAULT '0' COMMENT 'no. of visits to the link',
  `linkflag` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-allowed,2-under review,3-blocked',
  `linktype` tinyint(4) NOT NULL COMMENT '1-external website,',
  `linkaddtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time during which the link was first clicked',
  `label` varchar(100) NOT NULL COMMENT 'Label for this link',
  `favico` varchar(200) NOT NULL COMMENT 'Favico URL for the link'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING ALL LINKS';

-- --------------------------------------------------------

--
-- Table structure for table `referraldb`
--

DROP TABLE IF EXISTS `referraldb`;
CREATE TABLE `referraldb` (
  `referralid` varchar(50) NOT NULL COMMENT 'unique referral id for the person',
  `referraltype` tinyint(4) NOT NULL COMMENT 'flag value specifying referral type (1-partners,2-promotions,3-others)',
  `referralweburl` varchar(400) NOT NULL COMMENT 'website url of the referral',
  `referralvisits` bigint(20) NOT NULL COMMENT 'no. of visits users made using this referral',
  `referraltime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'referral add time',
  `referralname` varchar(300) NOT NULL COMMENT 'name of the referral',
  `referraldesc` text NOT NULL COMMENT 'description regarding the referrer',
  `referraluid` varchar(50) NOT NULL COMMENT 'User ID of the referrer'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `shorturldb`
--

DROP TABLE IF EXISTS `shorturldb`;
CREATE TABLE `shorturldb` (
  `url` varchar(800) NOT NULL COMMENT 'URL of the page where link leads',
  `linkflag` tinyint(4) NOT NULL COMMENT 'Perm Flag value of link (1-allowed,2-under review,3-blocked)',
  `linkvisits` bigint(20) NOT NULL COMMENT 'No. of visits to this link',
  `linkkey` varchar(50) NOT NULL COMMENT 'Shortened key of this link',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of person shortening the link (Optional)',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time during which the link was created'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SHORTENED URLs';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `linkdb`
--
ALTER TABLE `linkdb`
  ADD PRIMARY KEY (`linkid`);

--
-- Indexes for table `shorturldb`
--
ALTER TABLE `shorturldb`
  ADD PRIMARY KEY (`linkkey`);
--
-- Database: `ta_gallery`
--
DROP DATABASE IF EXISTS `ta_gallery`;
CREATE DATABASE IF NOT EXISTS `ta_gallery` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_gallery`;

-- --------------------------------------------------------

--
-- Table structure for table `extensiondb`
--

DROP TABLE IF EXISTS `extensiondb`;
CREATE TABLE `extensiondb` (
  `extid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the extension',
  `ext` varchar(10) NOT NULL COMMENT 'The extension eg.mp3,wav,etc. (Without .)',
  `extico` varchar(400) NOT NULL COMMENT 'The icon to be shown for files having this extension',
  `col_progid` varchar(50) NOT NULL COMMENT 'ID of the program collection from collection DB which can run this type of file'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL FILE EXTENSION TYPES WITH ASSOCIATED PROGRAMS';

-- --------------------------------------------------------

--
-- Table structure for table `galdb`
--

DROP TABLE IF EXISTS `galdb`;
CREATE TABLE `galdb` (
  `galid` varchar(50) NOT NULL COMMENT 'gallery id',
  `mediaurl` varchar(300) NOT NULL COMMENT 'media url',
  `audienceid` varchar(50) NOT NULL COMMENT 'Audience id of this media',
  `mediadesc` text NOT NULL COMMENT 'media description',
  `mediaid` varchar(50) NOT NULL COMMENT 'media id',
  `tagid` varchar(50) NOT NULL COMMENT 'tag id',
  `mediatype` tinyint(4) NOT NULL COMMENT 'flag value 1-photo,2-document,3-video,4-audio',
  `mediatitle` varchar(200) NOT NULL COMMENT 'title of media uploaded',
  `mediaflag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked,4-processing',
  `mediatime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the media was uploaded',
  `mediathumb` varchar(300) NOT NULL COMMENT 'url of thumbnail to the given media',
  `jsonid` varchar(50) NOT NULL COMMENT 'Json ID having info regarding metadata for this media',
  `fext` varchar(10) NOT NULL COMMENT 'Extension of the file',
  `fname` varchar(255) NOT NULL COMMENT 'Name of the file',
  `serverid` varchar(50) NOT NULL DEFAULT '0000001' COMMENT 'Server ID of the server where this media is stored.',
  `mediauid` varchar(50) NOT NULL COMMENT 'UID of the person uploading the media',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID for the media',
  `comtid` varchar(50) NOT NULL COMMENT 'Comment thread ID for media'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LINK TO ALL TYPES OF GALLERY FILES';

-- --------------------------------------------------------

--
-- Table structure for table `galinfo`
--

DROP TABLE IF EXISTS `galinfo`;
CREATE TABLE `galinfo` (
  `galid` varchar(50) NOT NULL COMMENT 'gallery id',
  `galname` varchar(300) NOT NULL COMMENT 'name of the gallery',
  `galdesc` text NOT NULL COMMENT 'gallery description',
  `galpic` varchar(300) NOT NULL COMMENT 'cover pic of gallery',
  `audienceid` varchar(50) NOT NULL COMMENT 'The audience id of people who can view this gallery',
  `galtype` tinyint(4) NOT NULL COMMENT 'flag values 1-mixed,2-photo,3-docs,4-video,5-audio,6-FAVOURITE GALLERY,7-Custom Gallery',
  `galflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed,2-under review,3-blocked',
  `galtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of gallery creation',
  `uid` varchar(30) NOT NULL COMMENT 'uid of person creating the gallery',
  `tagid` varchar(50) NOT NULL COMMENT 'tag id from tag db',
  `parentgalid` varchar(50) NOT NULL COMMENT 'galid of the parent of this gallery (empty if no parent exists)',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID for this Item',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID from JSON DB containing metadata about the gallery',
  `comtid` varchar(50) NOT NULL COMMENT 'Comment thread ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='GALLERY INFO';

-- --------------------------------------------------------

--
-- Table structure for table `notesdb`
--

DROP TABLE IF EXISTS `notesdb`;
CREATE TABLE `notesdb` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person who created the note',
  `notetext` text NOT NULL COMMENT 'Contents of the note',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of creation of the note',
  `noteid` varchar(50) NOT NULL COMMENT 'Unique ID of the note',
  `notepriority` tinyint(4) NOT NULL COMMENT 'Priority of the note as given by the user',
  `notetype` tinyint(4) NOT NULL COMMENT 'Flag value specifying Type of note (1-random,2-meeting,3-educational,etc)',
  `noteshare` varchar(50) NOT NULL COMMENT 'Flag value 1-private,2-public,others-list id from list db to be shared with'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL NOTES CREATED BY THE USERS';

-- --------------------------------------------------------

--
-- Table structure for table `programdb`
--

DROP TABLE IF EXISTS `programdb`;
CREATE TABLE `programdb` (
  `progid` varchar(50) NOT NULL COMMENT 'Unique ID for the program',
  `progname` varchar(400) NOT NULL COMMENT 'Name of the program',
  `progpath` varchar(400) NOT NULL COMMENT 'Path specifying the program''s executable',
  `progdesc` text NOT NULL COMMENT 'Description Relating to the program',
  `progcatid` varchar(50) NOT NULL COMMENT 'Cat ID of the program where it comes in'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF ALL PROGRAMS(SOFTWARE) WITH ASSOCIATED INFO';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `extensiondb`
--
ALTER TABLE `extensiondb`
  ADD PRIMARY KEY (`ext`),
  ADD UNIQUE KEY `ext` (`ext`),
  ADD UNIQUE KEY `extid` (`extid`);

--
-- Indexes for table `galdb`
--
ALTER TABLE `galdb`
  ADD PRIMARY KEY (`galid`,`mediaid`);

--
-- Indexes for table `galinfo`
--
ALTER TABLE `galinfo`
  ADD PRIMARY KEY (`galid`);
--
-- Database: `ta_groups`
--
DROP DATABASE IF EXISTS `ta_groups`;
CREATE DATABASE IF NOT EXISTS `ta_groups` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_groups`;

-- --------------------------------------------------------

--
-- Table structure for table `gallery_attached`
--

DROP TABLE IF EXISTS `gallery_attached`;
CREATE TABLE `gallery_attached` (
  `gpid` varchar(50) NOT NULL COMMENT 'group id',
  `galid` varchar(50) NOT NULL COMMENT 'gallery id'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL GALLERIES OF A GROUP';

-- --------------------------------------------------------

--
-- Table structure for table `groups_featured`
--

DROP TABLE IF EXISTS `groups_featured`;
CREATE TABLE `groups_featured` (
  `gpid` varchar(50) NOT NULL COMMENT 'Group ID',
  `flvl` bigint(20) NOT NULL COMMENT 'Featured level',
  `ftime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of featuring the group'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING FEATURED GROUPS';

-- --------------------------------------------------------

--
-- Table structure for table `groups_info`
--

DROP TABLE IF EXISTS `groups_info`;
CREATE TABLE `groups_info` (
  `gpid` varchar(50) NOT NULL COMMENT 'group id',
  `gpkey` varchar(300) NOT NULL COMMENT 'Group Key',
  `gpdesc` text NOT NULL COMMENT 'group description',
  `gpname` varchar(300) NOT NULL COMMENT 'group name',
  `gpprivacy` varchar(50) NOT NULL COMMENT '1-public,2-secret,3-closed,others-list id',
  `gptime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'group creation time',
  `gppic` varchar(300) NOT NULL COMMENT 'group picture',
  `gpflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed,2-under review,3-blocked',
  `gprating` double NOT NULL COMMENT 'group rating',
  `gpemail` varchar(300) NOT NULL COMMENT 'email for the group',
  `gpmemtype` tinyint(4) NOT NULL DEFAULT '2' COMMENT 'Membership type (1-auto approve,2-require approval from admin,3-require approval from anyone in the group,4-disapprove all requests)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING GROUP DEFINITION';

-- --------------------------------------------------------

--
-- Table structure for table `members_attached`
--

DROP TABLE IF EXISTS `members_attached`;
CREATE TABLE `members_attached` (
  `gpid` varchar(50) NOT NULL COMMENT 'group id',
  `uid` varchar(30) NOT NULL COMMENT 'user id',
  `jointime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'user join time',
  `memrole` tinyint(4) NOT NULL COMMENT '1-user,2-admin,3-creator',
  `addby` varchar(50) NOT NULL COMMENT 'uid of person who added this person',
  `memflag` tinyint(4) NOT NULL COMMENT 'flag value (1-allowed,2-under review,3-blocked)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL MEMBERS OF A GROUP';

-- --------------------------------------------------------

--
-- Table structure for table `threads_attached`
--

DROP TABLE IF EXISTS `threads_attached`;
CREATE TABLE `threads_attached` (
  `gpid` varchar(50) NOT NULL COMMENT 'group id',
  `tid` varchar(50) NOT NULL COMMENT 'thread id',
  `attachtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of attaching the thread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL THREADS POSTED TO A GROUP';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gallery_attached`
--
ALTER TABLE `gallery_attached`
  ADD PRIMARY KEY (`gpid`,`galid`);

--
-- Indexes for table `groups_featured`
--
ALTER TABLE `groups_featured`
  ADD PRIMARY KEY (`gpid`);

--
-- Indexes for table `groups_info`
--
ALTER TABLE `groups_info`
  ADD PRIMARY KEY (`gpid`),
  ADD KEY `gpkey` (`gpkey`);

--
-- Indexes for table `members_attached`
--
ALTER TABLE `members_attached`
  ADD PRIMARY KEY (`gpid`,`uid`);

--
-- Indexes for table `threads_attached`
--
ALTER TABLE `threads_attached`
  ADD PRIMARY KEY (`gpid`,`tid`);
--
-- Database: `ta_help`
--
DROP DATABASE IF EXISTS `ta_help`;
CREATE DATABASE IF NOT EXISTS `ta_help` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_help`;

-- --------------------------------------------------------

--
-- Table structure for table `support_customer`
--

DROP TABLE IF EXISTS `support_customer`;
CREATE TABLE `support_customer` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `ticketid` varchar(50) NOT NULL COMMENT 'ticket id',
  `threadid` varchar(50) NOT NULL COMMENT 'thread id',
  `uid` varchar(30) NOT NULL COMMENT 'user id',
  `rating` float NOT NULL COMMENT 'rating',
  `solvedflag` tinyint(4) NOT NULL COMMENT 'flag value 1-solved,2-under review,3-not solved,4-ignored',
  `comments` text NOT NULL COMMENT 'comment on customer support',
  `starttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of initializing support'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS OF CUSTOMER SUPPORT';

-- --------------------------------------------------------

--
-- Table structure for table `support_help`
--

DROP TABLE IF EXISTS `support_help`;
CREATE TABLE `support_help` (
  `appid` varchar(50) NOT NULL COMMENT 'APP ID of the application for which this help item is intended',
  `threadid` varchar(50) NOT NULL COMMENT 'Thread ID for the help content',
  `asktime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the question was asked'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL HELP CONTENTS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `support_customer`
--
ALTER TABLE `support_customer`
  ADD PRIMARY KEY (`sno`);

--
-- Indexes for table `support_help`
--
ALTER TABLE `support_help`
  ADD PRIMARY KEY (`appid`,`threadid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `support_customer`
--
ALTER TABLE `support_customer`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_likes`
--
DROP DATABASE IF EXISTS `ta_likes`;
CREATE DATABASE IF NOT EXISTS `ta_likes` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_likes`;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE `ratings` (
  `rateid` varchar(50) NOT NULL COMMENT 'rate id',
  `rateuid` varchar(30) NOT NULL COMMENT 'rate user id',
  `ratetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of rate',
  `ratestat` int(11) NOT NULL COMMENT '0-rate init,1-rate up,-1 rate down',
  `ratetype` tinyint(4) NOT NULL COMMENT 'flag value 1-post,2-comment,3-gallery,4-media,5-message,6-chat,7-helpitem,8-subscription,9-submail,10-readlist,11-thread'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL RATING DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `url_bookmarks`
--

DROP TABLE IF EXISTS `url_bookmarks`;
CREATE TABLE `url_bookmarks` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `uid` varchar(30) NOT NULL COMMENT 'user id adding favourite',
  `favid` varchar(50) NOT NULL COMMENT 'favourite id',
  `favurl` varchar(400) NOT NULL COMMENT 'favourite url',
  `favname` varchar(200) NOT NULL COMMENT 'favourite name',
  `favdesc` text NOT NULL COMMENT 'favourite description',
  `favtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'favourite add time',
  `galid` varchar(50) NOT NULL COMMENT 'gallery id where this favorite belongs'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL FAVOURITE DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `user_interests`
--

DROP TABLE IF EXISTS `user_interests`;
CREATE TABLE `user_interests` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person who has the interest',
  `actid` varchar(50) NOT NULL COMMENT 'Activity ID which denotes the interest of the person',
  `acttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time during which the interest was added',
  `actnotes` text NOT NULL COMMENT 'notes about this activity'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING INTERESTS OF USERS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rateid`,`rateuid`,`ratestat`);

--
-- Indexes for table `url_bookmarks`
--
ALTER TABLE `url_bookmarks`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `url_bookmarks`
--
ALTER TABLE `url_bookmarks`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_logs`
--
DROP DATABASE IF EXISTS `ta_logs`;
CREATE DATABASE IF NOT EXISTS `ta_logs` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_logs`;

-- --------------------------------------------------------

--
-- Table structure for table `referral_activity`
--

DROP TABLE IF EXISTS `referral_activity`;
CREATE TABLE `referral_activity` (
  `instanceid` int(11) NOT NULL COMMENT 'unique id for every log',
  `description` int(11) NOT NULL COMMENT 'description of this activity',
  `referralid` int(11) NOT NULL COMMENT 'referralid from referraldb',
  `referreduid` int(11) NOT NULL COMMENT 'User ID of person who is being referred'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING REFERRAL LOG INFORMATION';

-- --------------------------------------------------------

--
-- Table structure for table `user_activity`
--

DROP TABLE IF EXISTS `user_activity`;
CREATE TABLE `user_activity` (
  `uid` varchar(50) NOT NULL COMMENT 'user id of person whose activity is recorded',
  `activityid` varchar(100) NOT NULL COMMENT 'activity id from activity index table',
  `activitytime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time during which activity was performed',
  `ipaddr` varchar(50) NOT NULL COMMENT 'ip address of pc in which activity was performed',
  `platforminfo` text NOT NULL COMMENT 'has complete info about user''s platform',
  `instanceid` varchar(100) NOT NULL COMMENT 'instance id which is unique for each logged activity',
  `activitydesc` text NOT NULL COMMENT 'extra activity information'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER ACTIVITY DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `user_profilehits`
--

DROP TABLE IF EXISTS `user_profilehits`;
CREATE TABLE `user_profilehits` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the user',
  `vuid` varchar(50) NOT NULL COMMENT 'User ID of the visitor',
  `vtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of visit'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING PROFILE HITS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_activity`
--
ALTER TABLE `user_activity`
  ADD PRIMARY KEY (`instanceid`),
  ADD KEY `uid` (`uid`);
--
-- Database: `ta_p_mplace`
--
DROP DATABASE IF EXISTS `ta_p_mplace`;
CREATE DATABASE IF NOT EXISTS `ta_p_mplace` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_p_mplace`;
--
-- Database: `ta_p_subscriberbot`
--
DROP DATABASE IF EXISTS `ta_p_subscriberbot`;
CREATE DATABASE IF NOT EXISTS `ta_p_subscriberbot` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_p_subscriberbot`;

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_cats_mail`
--

DROP TABLE IF EXISTS `p_sb_cats_mail`;
CREATE TABLE `p_sb_cats_mail` (
  `catid` varchar(50) NOT NULL COMMENT 'The Unique ID of the category',
  `pcatid` varchar(50) NOT NULL COMMENT 'The ID of the parent category ("" if this is the parent category)',
  `catname` varchar(250) NOT NULL COMMENT 'Name of the category',
  `catdesc` text NOT NULL COMMENT 'Category Description',
  `totalrating` bigint(20) NOT NULL COMMENT 'Total Rating of the category',
  `noofrates` bigint(20) NOT NULL COMMENT 'No of ratings',
  `cattype` tinyint(4) NOT NULL COMMENT '1-all ages,2-adult'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL CATEGORIES OF THE SUBSCRIPTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_submail_comments`
--

DROP TABLE IF EXISTS `p_sb_submail_comments`;
CREATE TABLE `p_sb_submail_comments` (
  `submailid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the mail for which the comments are to be retreived',
  `subid` varchar(50) NOT NULL COMMENT 'Subscription ID of the subscription to which the mail belongs',
  `threadid` varchar(50) NOT NULL COMMENT 'Comment Thread ID from master thread db'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL COMMENTS MADE ON SUBSCRIPTION MAILS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_submail_rating`
--

DROP TABLE IF EXISTS `p_sb_submail_rating`;
CREATE TABLE `p_sb_submail_rating` (
  `submailid` varchar(50) NOT NULL COMMENT 'Submail ID for the mail for which rating is to be done',
  `subid` varchar(50) NOT NULL COMMENT 'Subscription ID of the subscriptions in which this mail belongs',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID from rate db'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING RATINGS OF ALL MAILS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_submail_readlist`
--

DROP TABLE IF EXISTS `p_sb_submail_readlist`;
CREATE TABLE `p_sb_submail_readlist` (
  `readlistid` varchar(50) NOT NULL COMMENT 'Read List ID for every list',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person who has this list',
  `submailid` varchar(50) NOT NULL COMMENT 'Mail ID of the mail which has to be added to the readlist',
  `readstatus` tinyint(4) NOT NULL COMMENT 'Read status flag of this mail (1-read,2-partially read,3-unread,4-to be reviewed again)',
  `readtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this mail was marked read',
  `subid` varchar(50) NOT NULL COMMENT 'Subscription ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING READ LISTS OF USERS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_submails`
--

DROP TABLE IF EXISTS `p_sb_submails`;
CREATE TABLE `p_sb_submails` (
  `submailid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the mail',
  `subid` varchar(50) NOT NULL COMMENT 'Subscription ID under which this mail belongs',
  `subject` varchar(500) NOT NULL COMMENT 'Subject of mail',
  `body` longblob NOT NULL COMMENT 'Body of mail',
  `headers` text NOT NULL COMMENT 'Headers of mail',
  `col_subbot_mailattachid` varchar(50) NOT NULL COMMENT 'Collection ID having attachments on this mail',
  `flag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time this mail was added to the database after cron',
  `scanstatus` tinyint(4) NOT NULL COMMENT 'scan status of mail 1-scanned,2-scanning,3-not scanned',
  `time_sent` varchar(50) NOT NULL COMMENT 'time this mail was sent by the person',
  `mailtype` tinyint(4) NOT NULL COMMENT 'type of mail 1-all,2-adult',
  `mailformat` tinyint(4) NOT NULL COMMENT 'mail format 1-html,2-text only',
  `emailaddr` varchar(500) NOT NULL COMMENT 'The email address from which this mail was sent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL MAILS RECEIVED WITH FILTERS FROM SUBSCRIPTION DB';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_subscriptions_alias`
--

DROP TABLE IF EXISTS `p_sb_subscriptions_alias`;
CREATE TABLE `p_sb_subscriptions_alias` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person who has this alias',
  `subid` varchar(50) NOT NULL COMMENT 'Subscription ID of the subscription for which this alias is to be created',
  `alias` varchar(400) NOT NULL COMMENT 'The alias to be assigned'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALIAS OF SUBSCRIPTIONS THAT USERS HAVE SET';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_subscriptions_comments`
--

DROP TABLE IF EXISTS `p_sb_subscriptions_comments`;
CREATE TABLE `p_sb_subscriptions_comments` (
  `subid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the subscription ID for which the comments are to be fetched',
  `threadid` varchar(50) NOT NULL COMMENT 'The Thread ID of the comment thread from master thread db'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_subscriptions_public`
--

DROP TABLE IF EXISTS `p_sb_subscriptions_public`;
CREATE TABLE `p_sb_subscriptions_public` (
  `subid` varchar(50) NOT NULL COMMENT 'Unique ID specifying subscriptions',
  `col_emailid` varchar(50) NOT NULL COMMENT 'Collection ID specifying the email addresses from which the mail arrives for this subscription',
  `subname` varchar(50) NOT NULL COMMENT 'Subscription Name',
  `subnote` text NOT NULL COMMENT 'Note on this subscription',
  `subwebsite` varchar(400) NOT NULL COMMENT 'Website of the Subscription',
  `col_sb_mailcatid` varchar(50) NOT NULL COMMENT 'Collection ID specifying the tags/category under which this subscription belongs',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time during which this subscription was added',
  `avgfrequency` double NOT NULL COMMENT 'The average frequency of receiving the emails',
  `subflag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked',
  `subtype` tinyint(4) NOT NULL COMMENT '1-all,2-adult',
  `featuredflag` tinyint(4) NOT NULL COMMENT '1-normal,2-featured'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING ALL SUBSCRIPTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_subscriptions_rating`
--

DROP TABLE IF EXISTS `p_sb_subscriptions_rating`;
CREATE TABLE `p_sb_subscriptions_rating` (
  `subid` varchar(50) NOT NULL COMMENT 'The subscription ID of the subscription for which this rating is given',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID from master rate db'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING INFO ABOUT RATINGS ON SUBSCRIPTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_subscriptions_requests`
--

DROP TABLE IF EXISTS `p_sb_subscriptions_requests`;
CREATE TABLE `p_sb_subscriptions_requests` (
  `requestid` varchar(50) NOT NULL COMMENT 'Unique ID for every request made by users',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person making this request (if available)',
  `requestnote` text NOT NULL COMMENT 'Note made on this request',
  `requesturl` varchar(500) NOT NULL COMMENT 'URL specifying the place where the newsletter can be subscribed',
  `requesttime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this request was made',
  `emailaddr` varchar(50) NOT NULL COMMENT 'Email address where an alert has to be given if this subscription is added'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SUBSCRIPTION REQUESTS MADE BY THE USERS AND NON-USERS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_user`
--

DROP TABLE IF EXISTS `p_sb_user`;
CREATE TABLE `p_sb_user` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID who has these info for subscriberbot',
  `digestfrequency` int(11) NOT NULL COMMENT 'The frequency in which digest has to be sent in days',
  `lastdigestsent` varchar(50) NOT NULL COMMENT 'Time the digest was last sent to this user',
  `col_catid` varchar(50) NOT NULL COMMENT 'Collection ID having all Category IDs of this user',
  `col_digest_subid` varchar(50) NOT NULL COMMENT 'Collection ID of subscriptions which has to be sent as digests to the user',
  `digestmail` varchar(500) NOT NULL COMMENT 'Mail address where digests have to be sent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER SETTINGS FOR SUBSCRIBER BOT';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_user_favs`
--

DROP TABLE IF EXISTS `p_sb_user_favs`;
CREATE TABLE `p_sb_user_favs` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the user who has these favourites',
  `id` varchar(50) NOT NULL COMMENT 'ID of the item (subid,submailid,etc.)',
  `itemtype` tinyint(4) NOT NULL COMMENT 'Flag value specifying item type (1-subscription,2-submail)',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this favourite was added'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL USER SUBSCRIPTION AND SUBMAIL FAVORITES';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_user_mailcats`
--

DROP TABLE IF EXISTS `p_sb_user_mailcats`;
CREATE TABLE `p_sb_user_mailcats` (
  `cid` varchar(50) NOT NULL COMMENT 'Category ID',
  `pcid` varchar(50) NOT NULL COMMENT 'Parent Category ID',
  `cname` varchar(400) NOT NULL COMMENT 'Category Name',
  `col_subid` varchar(50) NOT NULL COMMENT 'Collection ID having subscriptions put under this category',
  `cdesc` text NOT NULL COMMENT 'Category Description',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this category was created',
  `uid_creator` varchar(50) NOT NULL COMMENT 'User ID of the person who created this category'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING ALL MAIL CATEGORIES CREATED BY USERS';

-- --------------------------------------------------------

--
-- Table structure for table `p_sb_views`
--

DROP TABLE IF EXISTS `p_sb_views`;
CREATE TABLE `p_sb_views` (
  `id` varchar(50) NOT NULL COMMENT 'Subscription ID',
  `ip` varchar(40) NOT NULL COMMENT 'IP Address in which the view was made',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person viewing this subscription',
  `viewtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this subscription was viewed',
  `itemtype` tinyint(4) NOT NULL COMMENT '1-subscriptions,2-submails,3-website'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING VIEW INFORMATION OF SUBSCRIPTIONS AND SUBMAILS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `p_sb_submail_comments`
--
ALTER TABLE `p_sb_submail_comments`
  ADD PRIMARY KEY (`submailid`);

--
-- Indexes for table `p_sb_subscriptions_comments`
--
ALTER TABLE `p_sb_subscriptions_comments`
  ADD PRIMARY KEY (`subid`);

--
-- Indexes for table `p_sb_subscriptions_rating`
--
ALTER TABLE `p_sb_subscriptions_rating`
  ADD PRIMARY KEY (`subid`);

--
-- Indexes for table `p_sb_user`
--
ALTER TABLE `p_sb_user`
  ADD PRIMARY KEY (`uid`);
--
-- Database: `ta_p_temple`
--
DROP DATABASE IF EXISTS `ta_p_temple`;
CREATE DATABASE IF NOT EXISTS `ta_p_temple` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_p_temple`;
--
-- Database: `ta_people`
--
DROP DATABASE IF EXISTS `ta_people`;
CREATE DATABASE IF NOT EXISTS `ta_people` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_people`;

-- --------------------------------------------------------

--
-- Table structure for table `audience_target`
--

DROP TABLE IF EXISTS `audience_target`;
CREATE TABLE `audience_target` (
  `audienceid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the audience',
  `col_locationid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of the locations',
  `col_routineid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of the routines',
  `col_ageid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of the age limits',
  `gender` tinyint(4) NOT NULL DEFAULT '-1' COMMENT '1-male,2-female,3-other,4-all',
  `col_reppointid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID having reputation points',
  `interestedin` tinyint(4) NOT NULL DEFAULT '-1' COMMENT '1-male,2-female,3-other,4-all',
  `col_langid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of the languages',
  `col_relstatid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of the relationship status',
  `col_weid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of work and education',
  `col_users` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID for users',
  `col_groups` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of groups',
  `spread_status` tinyint(4) NOT NULL DEFAULT '-1' COMMENT 'Flag value specifying things (1-friends,2-friend of friends, 3-relatives,4-people in groups i admin,5-no one,6-publicly web indexable)',
  `spread_uid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'User ID of the person for which the spread status is applicable',
  `col_religionid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of religions',
  `col_politicsid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of politics',
  `col_celebid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID specifying celebrity categories',
  `col_hierarchyid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection of hierarchies',
  `col_bookid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID specifying books and textual materials',
  `col_movseriesid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID specifying movies',
  `subscribers` tinyint(4) NOT NULL DEFAULT '-1' COMMENT '1-include subscribers,2-do not include subscribers,-1-ignore',
  `col_musicid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of music',
  `col_prodid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of products',
  `col_eventid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of Events',
  `col_healthid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of health status',
  `col_incomeexpid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of income and expenditure',
  `col_foodid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of food',
  `col_scheduleid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of schedule',
  `col_speciesid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of species',
  `col_skillid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of skills',
  `col_cbookid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of contact books',
  `col_listid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of lists',
  `col_sportid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of sports',
  `col_buildingid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of buildings',
  `col_inspirationid` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Collection ID of inspirations',
  `col_audienceid_or` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'ID having ORed collection',
  `col_audienceid_and` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'ANDed collection ID',
  `col_audienceid_not` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'NOTed collection ID',
  `loginreq` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-user must be logged in to view this,2-need not be logged in',
  `audlabel` varchar(300) DEFAULT NULL COMMENT 'Label the audience',
  `cuid` varchar(50) DEFAULT NULL COMMENT 'UID of the person who created this audience',
  `col_country` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'Country collection ID',
  `col_state` varchar(50) NOT NULL DEFAULT '-1' COMMENT 'State collection ID'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING AUDIENCE INFO,COLUMN VALUE -1 means ignore it';

-- --------------------------------------------------------

--
-- Table structure for table `user_bloodrequest`
--

DROP TABLE IF EXISTS `user_bloodrequest`;
CREATE TABLE `user_bloodrequest` (
  `reqid` varchar(50) NOT NULL COMMENT 'Request ID',
  `uid` varchar(50) NOT NULL COMMENT 'User ID requesting donation',
  `bloodgp` varchar(50) NOT NULL COMMENT 'Blood Group needed',
  `lat` float NOT NULL DEFAULT '0' COMMENT 'Latitude',
  `lng` float NOT NULL DEFAULT '0' COMMENT 'Longitude',
  `notes` text NOT NULL,
  `reqtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `quantity` float NOT NULL,
  `contactdet` text NOT NULL,
  `recflag` tinyint(4) NOT NULL DEFAULT '3' COMMENT 'Flag to see whether received or not (1-received,2-in process,3-not received)',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this record was created',
  `recaddr` text NOT NULL COMMENT 'Receiving address'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING REQUEST FOR BLOOD';

-- --------------------------------------------------------

--
-- Table structure for table `user_devices`
--

DROP TABLE IF EXISTS `user_devices`;
CREATE TABLE `user_devices` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID',
  `did` varchar(50) NOT NULL COMMENT 'Device ID',
  `label` varchar(200) NOT NULL COMMENT 'Label of the devices ',
  `ip` varchar(20) NOT NULL,
  `logid` varchar(50) NOT NULL,
  `adddate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resolution` varchar(100) NOT NULL,
  `lastlocid` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `devicetype` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-PC,2-Smartphone,3-Tablet,4-Laptops,5-Watches,6-Other'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING DEVICES OF A USER';

-- --------------------------------------------------------

--
-- Table structure for table `user_edu`
--

DROP TABLE IF EXISTS `user_edu`;
CREATE TABLE `user_edu` (
  `uid` varchar(50) NOT NULL COMMENT 'UID of the user',
  `eduid` varchar(50) NOT NULL COMMENT 'Unique Eduid for this education record',
  `stime` varchar(30) NOT NULL COMMENT 'Time this education was started',
  `etime` varchar(30) NOT NULL COMMENT 'Time this education ended Empty if still continuing',
  `instname` varchar(250) NOT NULL COMMENT 'Institution Name',
  `degree` varchar(150) NOT NULL COMMENT 'Degree pursued',
  `listid` varchar(50) NOT NULL COMMENT 'Listid from list db having classmates from this institution',
  `locid` varchar(50) NOT NULL COMMENT 'Locid from locdb stating location of this institution',
  `galid` varchar(50) NOT NULL COMMENT 'Galid from galdb having gallery attached to this record',
  `notes` text NOT NULL COMMENT 'Notes on this institution',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this record was created',
  `recprivacy` varchar(50) NOT NULL COMMENT 'Privacy of this record 1-Public,2-Private,others-audienceid from audience db',
  `insturl` varchar(250) NOT NULL COMMENT 'URL of the institution'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING EDUCATION DETAILS OF A USER';

-- --------------------------------------------------------

--
-- Table structure for table `user_extras`
--

DROP TABLE IF EXISTS `user_extras`;
CREATE TABLE `user_extras` (
  `uid` varchar(50) NOT NULL,
  `religid` varchar(50) NOT NULL COMMENT 'The religion ID of the user',
  `bloodgp` varchar(5) NOT NULL COMMENT 'Blood Group of the user',
  `politicalview` varchar(150) NOT NULL COMMENT 'Political view of this user',
  `wishlistid` varchar(50) NOT NULL,
  `watchlistid` varchar(50) NOT NULL,
  `readlistid` varchar(50) NOT NULL,
  `col_routineid` varchar(50) NOT NULL,
  `col_contactbookid` varchar(50) NOT NULL,
  `celebstatus` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-normal,2-celebrity,3-well known',
  `col_historyid` varchar(50) NOT NULL COMMENT 'History of this user',
  `col_lockers` varchar(50) NOT NULL COMMENT 'Lockers owned by this user',
  `resume` varchar(100) NOT NULL COMMENT 'URL containing resume of this user',
  `coverletter` varchar(100) NOT NULL COMMENT 'URL containing cover letter of this user',
  `biodata` varchar(100) NOT NULL COMMENT 'URL containing biodata of this user',
  `recommendations` varchar(100) NOT NULL COMMENT 'URL containing recommendations for this user',
  `rec_groups` varchar(50) NOT NULL COMMENT 'Group collection ID showing groups to be ignored in recommendations',
  `col_social` varchar(50) NOT NULL COMMENT 'Link collection ID from link collection db specifying social websites of the user',
  `col_skillid` varchar(50) NOT NULL COMMENT 'Skill Collection ID for this user',
  `col_achievementid` varchar(50) NOT NULL COMMENT 'Collection ID of achievements of this user',
  `aliases` text NOT NULL COMMENT 'Aliases of the user (1 per line)',
  `relstat` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1-Single,2-Married,3-In a Relationship,4-Engaged,5-It is Complicated,6-Separated,7-Divorced,8-Widowed',
  `scribbles` text NOT NULL COMMENT 'scribbles of a user',
  `cfeedaudid` varchar(50) NOT NULL COMMENT 'Audience ID for custom feed',
  `bgprofpic` varchar(350) NOT NULL COMMENT 'Profile pic background',
  `initpage` varchar(400) NOT NULL COMMENT 'Page to open when user logs in'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING EXTRA INFO ABOUT USERS';

-- --------------------------------------------------------

--
-- Table structure for table `user_health`
--

DROP TABLE IF EXISTS `user_health`;
CREATE TABLE `user_health` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person having this health',
  `col_disabilityid` varchar(50) NOT NULL COMMENT 'Disability collection',
  `col_diseaseid` varchar(50) NOT NULL COMMENT 'Disease collection',
  `col_medicineid` varchar(50) NOT NULL COMMENT 'Medicine collection',
  `healthdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time during which this health issue was recorded in database',
  `col_medicalrecordid` varchar(50) NOT NULL COMMENT 'Medical Records collection'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING HEALTH STATUS OF USERS';

-- --------------------------------------------------------

--
-- Table structure for table `user_import`
--

DROP TABLE IF EXISTS `user_import`;
CREATE TABLE `user_import` (
  `sno` bigint(20) NOT NULL COMMENT 'Serial no',
  `uid` varchar(50) NOT NULL COMMENT 'User ID of person who is importing',
  `prodid` varchar(100) NOT NULL COMMENT 'ID for the user given by the product',
  `prodflag` tinyint(4) NOT NULL COMMENT 'Flag showing products(1-Google)',
  `email` varchar(200) NOT NULL COMMENT 'Email Address',
  `usrname` varchar(300) NOT NULL COMMENT 'User Name as given by API',
  `jsonid` varchar(50) NOT NULL COMMENT 'Other data stored as json',
  `importtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of import'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER IMPORTED DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `user_info`
--

DROP TABLE IF EXISTS `user_info`;
CREATE TABLE `user_info` (
  `usrid` varchar(30) NOT NULL COMMENT 'user id',
  `unm` varchar(150) NOT NULL COMMENT 'user name',
  `upass` varchar(200) NOT NULL COMMENT 'user password',
  `uemail` varchar(300) NOT NULL COMMENT 'user email',
  `ugender` varchar(2) NOT NULL COMMENT 'gender',
  `ufname` varchar(150) NOT NULL COMMENT 'first name',
  `umname` varchar(150) NOT NULL COMMENT 'middle name',
  `ulname` varchar(150) NOT NULL COMMENT 'last name',
  `udob` varchar(20) NOT NULL COMMENT 'date of birth',
  `umobno` varchar(18) NOT NULL COMMENT 'mobile number',
  `ucountry` varchar(150) NOT NULL COMMENT 'country',
  `ustate` varchar(150) NOT NULL COMMENT 'state',
  `upincode` varchar(15) NOT NULL COMMENT 'pincode',
  `uaddress` text NOT NULL COMMENT 'address',
  `uloginstatus` tinyint(4) NOT NULL COMMENT 'login status of user 1-online 2-offline 3-busy 4-invisible',
  `uaggreementaccept` tinyint(4) NOT NULL COMMENT 'agreement acceptance 1-accepted 2-not accepted',
  `usubscribe` tinyint(4) NOT NULL COMMENT 'subscription to newletter 1-yes 2-no',
  `uuserexp` tinyint(4) NOT NULL COMMENT 'user experience flag 1-yes 2-no',
  `usignuptime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'sign up time of user',
  `uipaddr` varchar(100) NOT NULL COMMENT 'ip address of sign up',
  `usessionid` varchar(40) NOT NULL COMMENT 'session id of user',
  `profpicurl` varchar(200) NOT NULL COMMENT 'profile picture url',
  `cprofpic1` varchar(200) NOT NULL COMMENT 'compressed profile pic url level 1',
  `cprofpic2` varchar(200) NOT NULL COMMENT 'compressed profile pic url level 2',
  `uverifyid` varchar(30) NOT NULL COMMENT 'verification id generated during signup',
  `uflag` tinyint(4) NOT NULL COMMENT '1-new user, 2-verified user, 3-blocked user, 4-deleted account, 5-deactivated temporarily',
  `docroot` varchar(600) NOT NULL COMMENT 'user''s document root',
  `col_ulangid` varchar(50) NOT NULL COMMENT 'Collection having the Languages the user can speak',
  `col_userlocationid` varchar(50) NOT NULL COMMENT 'User Location ID specifying where the user is currently staying and where he stayed in past',
  `uphone` varchar(25) NOT NULL COMMENT 'User Phone number',
  `pwdretries` int(11) NOT NULL DEFAULT '0' COMMENT 'Number of password retries',
  `loginstattime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Last time the login status of user was updated'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='UNIVERSAL DATABASE FOR TECH AHOY';

-- --------------------------------------------------------

--
-- Table structure for table `user_location`
--

DROP TABLE IF EXISTS `user_location`;
CREATE TABLE `user_location` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID',
  `lat` float NOT NULL COMMENT 'Latitude',
  `lng` float NOT NULL COMMENT 'Longitude',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Created time'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER LOCATION DETAILS FROM MAPS';

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
CREATE TABLE `user_settings` (
  `uid` varchar(50) NOT NULL COMMENT 'UID of the user',
  `mainkey` varchar(200) NOT NULL COMMENT 'Main key for the setting',
  `subkey` varchar(200) NOT NULL COMMENT 'Sub key if it contains multiple val',
  `settype` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Type of setting (1-General,2-Visibility)',
  `setval` varchar(200) NOT NULL COMMENT 'Value for this setting (1-visible,2-invisible,others-audienceid)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING SETTINGS OF A USER';

-- --------------------------------------------------------

--
-- Table structure for table `user_work`
--

DROP TABLE IF EXISTS `user_work`;
CREATE TABLE `user_work` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person who has this record',
  `wid` varchar(50) NOT NULL COMMENT 'Unique ID showing the work record',
  `stime` varchar(30) NOT NULL COMMENT 'Time this role was started',
  `etime` varchar(30) NOT NULL COMMENT 'Time this role ended',
  `role` varchar(300) NOT NULL COMMENT 'Role of the person',
  `instname` varchar(400) NOT NULL COMMENT 'Name of workplace',
  `listid` varchar(50) NOT NULL COMMENT 'List ID from listdb denoting people who are colleagues',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time this record was created',
  `notes` text NOT NULL COMMENT 'Notes made by the user regarding this role',
  `recprivacy` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Privacy of this record (1-public,2-private,Others-List ID from List DB)',
  `typeid` varchar(50) NOT NULL COMMENT 'ID Specifying the type of work the person is doing',
  `salarymin` double NOT NULL COMMENT '-1-Ignore,Minimum Salary of the person',
  `salarymax` double NOT NULL COMMENT 'Max salary of the person',
  `locid` varchar(50) NOT NULL COMMENT 'Location ID from location DB specifying place of work',
  `galid` varchar(50) NOT NULL COMMENT 'Gallery ID from galdb specifying pics related to work',
  `insturl` varchar(250) NOT NULL COMMENT 'URL of the institution'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING WORK OF A PERSON';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `audience_target`
--
ALTER TABLE `audience_target`
  ADD PRIMARY KEY (`audienceid`);

--
-- Indexes for table `user_bloodrequest`
--
ALTER TABLE `user_bloodrequest`
  ADD PRIMARY KEY (`reqid`);

--
-- Indexes for table `user_devices`
--
ALTER TABLE `user_devices`
  ADD PRIMARY KEY (`uid`,`did`);

--
-- Indexes for table `user_edu`
--
ALTER TABLE `user_edu`
  ADD PRIMARY KEY (`uid`,`eduid`);

--
-- Indexes for table `user_extras`
--
ALTER TABLE `user_extras`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_health`
--
ALTER TABLE `user_health`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_import`
--
ALTER TABLE `user_import`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `user_info`
--
ALTER TABLE `user_info`
  ADD PRIMARY KEY (`usrid`,`unm`),
  ADD UNIQUE KEY `unm_2` (`unm`),
  ADD UNIQUE KEY `usrid` (`usrid`),
  ADD KEY `ufname` (`ufname`,`umname`,`ulname`),
  ADD KEY `unm` (`unm`);

--
-- Indexes for table `user_location`
--
ALTER TABLE `user_location`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`uid`,`mainkey`,`subkey`);

--
-- Indexes for table `user_work`
--
ALTER TABLE `user_work`
  ADD PRIMARY KEY (`wid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_import`
--
ALTER TABLE `user_import`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'Serial no';--
-- Database: `ta_performance`
--
DROP DATABASE IF EXISTS `ta_performance`;
CREATE DATABASE IF NOT EXISTS `ta_performance` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_performance`;

-- --------------------------------------------------------

--
-- Table structure for table `feedback_user`
--

DROP TABLE IF EXISTS `feedback_user`;
CREATE TABLE `feedback_user` (
  `sno` bigint(20) NOT NULL COMMENT 'serial no',
  `uid` varchar(30) NOT NULL COMMENT 'user id giving feedback',
  `url` varchar(350) NOT NULL COMMENT 'url in which feedback was given',
  `feedback` text NOT NULL COMMENT 'feedback text',
  `feedbackid` varchar(50) NOT NULL COMMENT 'feedback id (unique)',
  `ftime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time in which feedback was given',
  `appid` varchar(100) NOT NULL COMMENT 'app id',
  `emailaddr` varchar(300) NOT NULL COMMENT 'email address',
  `screenshot` varchar(300) NOT NULL COMMENT 'screenshot of feedback',
  `suggestedsol` text NOT NULL COMMENT 'suggested solution',
  `readstatus` tinyint(4) NOT NULL COMMENT '1-unread,2-read,3-under review',
  `moodtype` tinyint(4) NOT NULL COMMENT '1-feedback,2-feature request,3-Bug Report,4-contact,5-acct del reason'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING FEEDBACK CONTENTS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `feedback_user`
--
ALTER TABLE `feedback_user`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `feedback_user`
--
ALTER TABLE `feedback_user`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'serial no';--
-- Database: `ta_places`
--
DROP DATABASE IF EXISTS `ta_places`;
CREATE DATABASE IF NOT EXISTS `ta_places` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_places`;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
CREATE TABLE `cities` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `state_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL,
  `sortname` varchar(3) NOT NULL,
  `name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `country`
--

DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id` int(11) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) UNSIGNED NOT NULL DEFAULT '1' COMMENT 'Use this country in applications',
  `code3l` varchar(3) NOT NULL COMMENT 'ISO 3166-1 alpha-3 three-letter code',
  `code2l` varchar(2) NOT NULL COMMENT 'ISO 3166-1 alpha-2 two-letter code',
  `name` varchar(64) NOT NULL COMMENT 'Name of the country in English',
  `name_official` varchar(128) DEFAULT NULL,
  `flag_32` varchar(255) DEFAULT NULL,
  `flag_128` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `zoom` tinyint(1) DEFAULT NULL COMMENT 'Optimal zoom when showing country on map'
) ENGINE=InnoDB AVG_ROW_LENGTH=434 DEFAULT CHARSET=utf8 COMMENT='Hold the list of countries. Each country is a row';

-- --------------------------------------------------------

--
-- Table structure for table `location_info`
--

DROP TABLE IF EXISTS `location_info`;
CREATE TABLE `location_info` (
  `locid` varchar(50) NOT NULL COMMENT 'unique id of the location',
  `locname` varchar(500) NOT NULL COMMENT 'name of the location',
  `parentlocid` varchar(50) NOT NULL COMMENT 'location id of the parent location (empty if no parent)',
  `loccapitalid` varchar(50) NOT NULL COMMENT 'location id of the capital (empty if no capital)',
  `locarea` double NOT NULL COMMENT 'area in square meters of the location',
  `weatherid` varchar(50) NOT NULL COMMENT 'weather id of the location',
  `populationid` varchar(50) NOT NULL COMMENT 'population id of the location',
  `elevation` varchar(15) NOT NULL COMMENT 'elevation of the location',
  `timeadj` varchar(15) NOT NULL COMMENT 'time adjustment from GMT',
  `loctype` int(4) NOT NULL COMMENT 'flag value specifying type of location (0-world,1-continent,2-country,3-state,4-district,5-small place,6-building,7-monument,8-Ocean,9-Mountain,10-Family restaurant,11-temple,12-school,13-college/university,14-mall,15-office,16-govt building,17-bus stand,18-railway station,19-airport,20-harbor,21-forest,22-zoo,23-natural reserve,24-hill station,25-park,26-parking lot,27-farm,28-plantation,29-beach,30-waterfall,31-river,32-lake,33-coffee shop,34-pizza shop)',
  `lat` float NOT NULL COMMENT 'latitude',
  `long` float NOT NULL COMMENT 'longitude',
  `infourl` varchar(400) NOT NULL COMMENT 'URL of the website ',
  `currencyid` varchar(50) NOT NULL COMMENT 'id containing currency information of the location',
  `notes` text NOT NULL COMMENT 'notes regarding this location',
  `locflag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked',
  `totalrating` bigint(20) NOT NULL COMMENT 'total rating of the location',
  `noofrates` bigint(20) NOT NULL COMMENT 'no of people who rated',
  `loccode` varchar(15) NOT NULL COMMENT 'area code of location if it exist',
  `address` text NOT NULL COMMENT 'complete address of the location if it exist',
  `galid` varchar(50) NOT NULL COMMENT 'id of the gallery containing the location''s pics',
  `imgurl` varchar(400) NOT NULL COMMENT 'url of the main image of the location',
  `reviewid` varchar(50) NOT NULL COMMENT 'id of the reviews made to this location',
  `dialcode` varchar(15) NOT NULL COMMENT 'Dial code of phone',
  `anthem` varchar(400) NOT NULL COMMENT 'URL of the mp3 file having the anthem',
  `gdp` double NOT NULL COMMENT 'GDP of the country recognized by world bank',
  `locflagimg` varchar(400) NOT NULL COMMENT 'URL of the image of the location''s flag'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='HAS INFO REGARDING ALL PLACES IN THE WORLD';

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
CREATE TABLE `states` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `country_id` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `weather_reports`
--

DROP TABLE IF EXISTS `weather_reports`;
CREATE TABLE `weather_reports` (
  `weatherid` varchar(100) NOT NULL COMMENT 'unique id for each forecast',
  `locid` varchar(50) NOT NULL COMMENT 'id of the location on which the forecast is made',
  `weathertime` varchar(30) NOT NULL COMMENT 'start time for which the forecast is made',
  `precipitation` int(11) NOT NULL COMMENT 'precipitation value in percentage',
  `humidity` int(11) NOT NULL COMMENT 'humidity value in percentage',
  `label` varchar(100) NOT NULL COMMENT 'label to forecast like partly cloudy,etc.',
  `windspeed` int(11) NOT NULL COMMENT 'speed of wind in km/hr',
  `uvindex` varchar(100) NOT NULL COMMENT 'UV index eg. 0-Low',
  `snowfall` int(11) NOT NULL COMMENT 'snowfall in cm',
  `sunset` varchar(30) NOT NULL COMMENT 'time of sunset',
  `moonrise` varchar(30) NOT NULL COMMENT 'time of moonrise',
  `temperature` int(11) NOT NULL COMMENT 'temperature in celcius',
  `iconflag` tinyint(4) NOT NULL COMMENT 'flag value specifying icon to be shown for label',
  `recordedtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time when this forecast was recorded in the database'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING WEATHER FORECAST DETAILS FOR VARIOUS LOCATIONS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cities`
--
ALTER TABLE `cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `country`
--
ALTER TABLE `country`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_countries_name` (`name`),
  ADD UNIQUE KEY `idx_countries_code3l` (`code3l`),
  ADD UNIQUE KEY `idx_countries_code2l` (`code2l`),
  ADD KEY `code2l` (`code2l`);

--
-- Indexes for table `location_info`
--
ALTER TABLE `location_info`
  ADD PRIMARY KEY (`locid`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weather_reports`
--
ALTER TABLE `weather_reports`
  ADD PRIMARY KEY (`weatherid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cities`
--
ALTER TABLE `cities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `countries`
--
ALTER TABLE `countries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;--
-- Database: `ta_price`
--
DROP DATABASE IF EXISTS `ta_price`;
CREATE DATABASE IF NOT EXISTS `ta_price` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_price`;

-- --------------------------------------------------------

--
-- Table structure for table `jewelprice`
--

DROP TABLE IF EXISTS `jewelprice`;
CREATE TABLE `jewelprice` (
  `prodid` varchar(50) NOT NULL,
  `priceid` varchar(50) NOT NULL,
  `price` double NOT NULL,
  `prectime` varchar(30) NOT NULL,
  `curtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `weight` double NOT NULL,
  `unitid` varchar(50) NOT NULL,
  `purity` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF FLUCTUATING JEWEL PRICES';
--
-- Database: `ta_privsecurity`
--
DROP DATABASE IF EXISTS `ta_privsecurity`;
CREATE DATABASE IF NOT EXISTS `ta_privsecurity` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_privsecurity`;

-- --------------------------------------------------------

--
-- Table structure for table `incoming_requests`
--

DROP TABLE IF EXISTS `incoming_requests`;
CREATE TABLE `incoming_requests` (
  `sno` bigint(20) NOT NULL,
  `requestkey` varchar(30) NOT NULL,
  `method` tinyint(4) NOT NULL COMMENT '1-get 2-post 3-cookies 4-others',
  `appid` varchar(30) NOT NULL,
  `email` tinyint(4) NOT NULL,
  `uname` tinyint(4) NOT NULL,
  `url` tinyint(4) NOT NULL COMMENT 'URL'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `incoming_requests`
--
ALTER TABLE `incoming_requests`
  ADD PRIMARY KEY (`sno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `incoming_requests`
--
ALTER TABLE `incoming_requests`
  MODIFY `sno` bigint(20) NOT NULL AUTO_INCREMENT;--
-- Database: `ta_products`
--
DROP DATABASE IF EXISTS `ta_products`;
CREATE DATABASE IF NOT EXISTS `ta_products` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_products`;

-- --------------------------------------------------------

--
-- Table structure for table `outline_products`
--

DROP TABLE IF EXISTS `outline_products`;
CREATE TABLE `outline_products` (
  `prodid` varchar(50) NOT NULL COMMENT 'Unique ID of the product',
  `total` bigint(20) NOT NULL COMMENT 'Total no of products received',
  `sold` bigint(20) NOT NULL COMMENT 'No of products sold',
  `unsold` bigint(20) NOT NULL COMMENT 'No of products unsold',
  `returned` bigint(20) NOT NULL COMMENT 'No of products returned',
  `claims` bigint(20) NOT NULL COMMENT 'No. of claims to this product',
  `preorders` bigint(20) NOT NULL COMMENT 'No. of preorders to the product',
  `col_priceid` varchar(50) NOT NULL COMMENT 'Collection ID specifying the price with various dealers',
  `wishlistno` bigint(20) NOT NULL COMMENT 'No. of wishlists which have this product in them',
  `pop_locationid` varchar(50) NOT NULL COMMENT 'Location ID of the place where this product has high demand',
  `reports` bigint(20) NOT NULL COMMENT 'no of reports this product has received',
  `flag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked',
  `galid` varchar(50) NOT NULL COMMENT 'Gallery ID for the product',
  `col_dealerid` varchar(50) NOT NULL COMMENT 'Collection ID of the dealers which sell this product',
  `rateid` varchar(50) NOT NULL COMMENT 'Rate ID for this product',
  `threadid` varchar(50) NOT NULL COMMENT 'Thread ID for the product comments',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time this product was added'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `products_books`
--

DROP TABLE IF EXISTS `products_books`;
CREATE TABLE `products_books` (
  `prodid` varchar(50) NOT NULL COMMENT 'Product ID from the product database',
  `bookid` varchar(50) NOT NULL COMMENT 'Unique ID of the book',
  `bookname` varchar(500) NOT NULL COMMENT 'Name of the book',
  `col_authorid` varchar(50) NOT NULL COMMENT 'Collection ID specifying authors who wrote this book',
  `bookdesc` text NOT NULL COMMENT 'Description of this book',
  `bookcat` varchar(50) NOT NULL COMMENT 'CATID of the book',
  `col_ageid` varchar(50) NOT NULL COMMENT 'Collection ID of the Age Limits',
  `col_publication` varchar(50) NOT NULL COMMENT 'Collection ID of the publication',
  `edition` varchar(30) NOT NULL COMMENT 'Edition of this book',
  `editionyear` varchar(10) NOT NULL COMMENT 'year this edition was released',
  `printedat` varchar(400) NOT NULL COMMENT 'Place where this book was printed',
  `totpages` bigint(20) NOT NULL COMMENT 'No of pages in this book',
  `typesetat` varchar(400) NOT NULL COMMENT 'Place where this typeset was made'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
--
-- Database: `ta_social`
--
DROP DATABASE IF EXISTS `ta_social`;
CREATE DATABASE IF NOT EXISTS `ta_social` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_social`;

-- --------------------------------------------------------

--
-- Table structure for table `event_members`
--

DROP TABLE IF EXISTS `event_members`;
CREATE TABLE `event_members` (
  `eid` varchar(50) NOT NULL COMMENT 'Event ID',
  `ruid` varchar(50) NOT NULL COMMENT 'Receiver UID',
  `suid` varchar(50) NOT NULL COMMENT 'Sender UID',
  `status` tinyint(4) NOT NULL COMMENT '1-attending,2-pending,3-may attend,4-will not attend',
  `stime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of invite',
  `memrole` tinyint(4) NOT NULL COMMENT '1-creator,2-host,3-member',
  `memflag` tinyint(4) NOT NULL COMMENT '1-allowed,2-under review,3-blocked'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='MEMBERS ATTACHED FOR EVENTS';

-- --------------------------------------------------------

--
-- Table structure for table `eventdb`
--

DROP TABLE IF EXISTS `eventdb`;
CREATE TABLE `eventdb` (
  `eventid` varchar(50) NOT NULL COMMENT 'Event ID',
  `uid` varchar(50) NOT NULL COMMENT 'User ID',
  `ename` varchar(300) NOT NULL COMMENT 'Event name',
  `edesc` text NOT NULL COMMENT 'Event description',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Creation time',
  `audienceid` varchar(50) NOT NULL COMMENT 'Audience id',
  `weburl` varchar(300) NOT NULL COMMENT 'URL of related website',
  `etime` varchar(20) NOT NULL COMMENT 'Event time',
  `eplace` text NOT NULL COMMENT 'Event location address',
  `ecountry` varchar(50) NOT NULL COMMENT 'Country id',
  `estate` varchar(50) NOT NULL COMMENT 'state id',
  `egalid` varchar(50) NOT NULL COMMENT 'galid related to event',
  `etagid` varchar(50) NOT NULL COMMENT 'Tag ID',
  `eflag` tinyint(4) NOT NULL COMMENT '1-allowed, 2- under review, 3-blocked',
  `eduration` bigint(20) NOT NULL COMMENT 'Duration of the event in seconds',
  `estatus` tinyint(4) NOT NULL COMMENT 'Status of event (1-open,2-over,3-failed,4-not started)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING EVENT DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `followdb`
--

DROP TABLE IF EXISTS `followdb`;
CREATE TABLE `followdb` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID',
  `fuid` varchar(50) NOT NULL COMMENT 'User ID of the person to be followed',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time when the user started following'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING USER FOLLOWING DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `frienddb`
--

DROP TABLE IF EXISTS `frienddb`;
CREATE TABLE `frienddb` (
  `fuid` varchar(30) NOT NULL COMMENT 'uid of person adding as a friend',
  `tuid` varchar(30) NOT NULL COMMENT 'uid of person being added as friend',
  `reldesc` varchar(300) NOT NULL COMMENT 'Relationship Label',
  `flevel` tinyint(4) NOT NULL COMMENT 'friendship level',
  `fmsg` text NOT NULL COMMENT 'Friend request message',
  `ftime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of friend add',
  `fflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed 2-under review 3-blocked',
  `nickname` varchar(250) NOT NULL COMMENT 'a nickname given by the other user',
  `tid` varchar(50) NOT NULL COMMENT 'Thread ID for chat'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL FRIEND DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `intouchdb`
--

DROP TABLE IF EXISTS `intouchdb`;
CREATE TABLE `intouchdb` (
  `intouchid` varchar(50) NOT NULL COMMENT 'Intouch ID unique for every row',
  `uid` varchar(50) NOT NULL COMMENT 'User ID',
  `extflag` varchar(50) NOT NULL COMMENT 'External Flag (1-Facebook)',
  `exttype` tinyint(4) NOT NULL COMMENT '1-Page Plugin',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID from JSON DB',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time of adding the element'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING IN TOUCH DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `jsondb`
--

DROP TABLE IF EXISTS `jsondb`;
CREATE TABLE `jsondb` (
  `jsonid` varchar(50) NOT NULL COMMENT 'ID of json data',
  `jsondata` text NOT NULL COMMENT 'The JSON data stored as string'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING JSON DATA RELATED TO VARIOUS ITEMS';

-- --------------------------------------------------------

--
-- Table structure for table `listinfo`
--

DROP TABLE IF EXISTS `listinfo`;
CREATE TABLE `listinfo` (
  `listid` varchar(50) NOT NULL COMMENT 'list id',
  `listname` varchar(300) NOT NULL COMMENT 'name of list',
  `listdesc` text NOT NULL COMMENT 'list description',
  `listpic` varchar(300) NOT NULL COMMENT 'picture for list',
  `listtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of list creation',
  `listflag` tinyint(4) NOT NULL COMMENT 'flag value 1-allowed 2-under review 3-blocked 4-autocreated',
  `listrating` float NOT NULL COMMENT 'rating of the list',
  `listprivacy` varchar(50) NOT NULL COMMENT 'Audience ID for list',
  `listtype` tinyint(4) NOT NULL COMMENT 'flag value 1-friend list,2-message list,3-share list',
  `listuid` varchar(30) NOT NULL COMMENT 'UID OF PERSON CREATING LIST',
  `parlistid` varchar(50) NOT NULL COMMENT 'List ID of parent list Empty if this is the parent'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING INFORMATION RELATED TO A LIST';

-- --------------------------------------------------------

--
-- Table structure for table `listsdb`
--

DROP TABLE IF EXISTS `listsdb`;
CREATE TABLE `listsdb` (
  `uid` varchar(30) NOT NULL COMMENT 'uid of person creating the list',
  `itemid` varchar(50) NOT NULL COMMENT 'id of item being added to list',
  `listid` varchar(50) NOT NULL COMMENT 'list id',
  `listtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of list creation',
  `itemurl` varchar(300) NOT NULL COMMENT 'URL of the item in the list',
  `itemtype` tinyint(4) NOT NULL COMMENT 'Type of item added to list (1-Thread,2-Media,3-User,4-Unknown,5-Group)',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID from JSON db',
  `itemdesc` text NOT NULL COMMENT 'Item description',
  `itemlbl` varchar(200) NOT NULL COMMENT 'Item label',
  `itempic` varchar(300) NOT NULL COMMENT 'Item picture URL'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL LISTS';

-- --------------------------------------------------------

--
-- Table structure for table `socketdb`
--

DROP TABLE IF EXISTS `socketdb`;
CREATE TABLE `socketdb` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID from JSON DB',
  `crtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the socket was created'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING SOCKET CONTENTS';

-- --------------------------------------------------------

--
-- Table structure for table `tagdb`
--

DROP TABLE IF EXISTS `tagdb`;
CREATE TABLE `tagdb` (
  `tagid` varchar(50) NOT NULL COMMENT 'tag id',
  `fuid` varchar(30) NOT NULL COMMENT 'uid of person tagging',
  `tuid` varchar(30) NOT NULL COMMENT 'uid of person being tagged',
  `tagname` varchar(400) NOT NULL COMMENT 'name given for the tag',
  `tagtype` tinyint(4) NOT NULL COMMENT 'flag value 1-post,2-comment,3-chat,4-gallery,5-media,6-message'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL THE TAG DETAILS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `event_members`
--
ALTER TABLE `event_members`
  ADD PRIMARY KEY (`eid`,`ruid`);

--
-- Indexes for table `eventdb`
--
ALTER TABLE `eventdb`
  ADD PRIMARY KEY (`eventid`);

--
-- Indexes for table `followdb`
--
ALTER TABLE `followdb`
  ADD PRIMARY KEY (`uid`,`fuid`);

--
-- Indexes for table `frienddb`
--
ALTER TABLE `frienddb`
  ADD PRIMARY KEY (`fuid`,`tuid`);

--
-- Indexes for table `intouchdb`
--
ALTER TABLE `intouchdb`
  ADD PRIMARY KEY (`intouchid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `jsondb`
--
ALTER TABLE `jsondb`
  ADD PRIMARY KEY (`jsonid`);

--
-- Indexes for table `listinfo`
--
ALTER TABLE `listinfo`
  ADD PRIMARY KEY (`listid`);

--
-- Indexes for table `listsdb`
--
ALTER TABLE `listsdb`
  ADD PRIMARY KEY (`uid`,`itemid`,`listid`),
  ADD KEY `uid` (`uid`);

--
-- Indexes for table `socketdb`
--
ALTER TABLE `socketdb`
  ADD PRIMARY KEY (`uid`);

--
-- Indexes for table `tagdb`
--
ALTER TABLE `tagdb`
  ADD PRIMARY KEY (`tagid`,`fuid`,`tuid`);
--
-- Database: `ta_species`
--
DROP DATABASE IF EXISTS `ta_species`;
CREATE DATABASE IF NOT EXISTS `ta_species` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_species`;

-- --------------------------------------------------------

--
-- Table structure for table `classdb`
--

DROP TABLE IF EXISTS `classdb`;
CREATE TABLE `classdb` (
  `phylumid` varchar(50) NOT NULL,
  `classid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING CLASS AND RELATED DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `domaindb`
--

DROP TABLE IF EXISTS `domaindb`;
CREATE TABLE `domaindb` (
  `domainid` varchar(50) NOT NULL COMMENT 'Unique ID specifying the domain',
  `name` varchar(400) NOT NULL COMMENT 'Name of the Domain',
  `desc` text NOT NULL COMMENT 'Description relating to domain',
  `infourl` varchar(400) NOT NULL COMMENT 'URL relating to domain'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING INFO RELATED TO DOMAINS';

-- --------------------------------------------------------

--
-- Table structure for table `familydb`
--

DROP TABLE IF EXISTS `familydb`;
CREATE TABLE `familydb` (
  `orderid` varchar(50) NOT NULL,
  `familyid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING FAMILY INFO AND RELATED INFO';

-- --------------------------------------------------------

--
-- Table structure for table `genusdb`
--

DROP TABLE IF EXISTS `genusdb`;
CREATE TABLE `genusdb` (
  `familyid` varchar(50) NOT NULL,
  `genusid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL GENUS AND RELATED INFO';

-- --------------------------------------------------------

--
-- Table structure for table `kingdomdb`
--

DROP TABLE IF EXISTS `kingdomdb`;
CREATE TABLE `kingdomdb` (
  `kingdomid` varchar(50) NOT NULL,
  `domainid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF ALL KINGDOMS WITH THEIR RELATED INFO';

-- --------------------------------------------------------

--
-- Table structure for table `orderdb`
--

DROP TABLE IF EXISTS `orderdb`;
CREATE TABLE `orderdb` (
  `classid` varchar(50) NOT NULL,
  `orderid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING ORDER';

-- --------------------------------------------------------

--
-- Table structure for table `phylumdb`
--

DROP TABLE IF EXISTS `phylumdb`;
CREATE TABLE `phylumdb` (
  `kingdomid` varchar(50) NOT NULL,
  `phylumid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING PHYLUM DETAILS WITH RELATED INFO';

-- --------------------------------------------------------

--
-- Table structure for table `speciesdb`
--

DROP TABLE IF EXISTS `speciesdb`;
CREATE TABLE `speciesdb` (
  `genusid` varchar(50) NOT NULL,
  `speciesid` varchar(50) NOT NULL,
  `name` varchar(400) NOT NULL,
  `desc` text NOT NULL,
  `infourl` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL SPECIES AND RELATED INFO';
--
-- Database: `ta_system`
--
DROP DATABASE IF EXISTS `ta_system`;
CREATE DATABASE IF NOT EXISTS `ta_system` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_system`;

-- --------------------------------------------------------

--
-- Table structure for table `crondb`
--

DROP TABLE IF EXISTS `crondb`;
CREATE TABLE `crondb` (
  `cronid` varchar(50) NOT NULL COMMENT 'Cron ID',
  `cmd` text NOT NULL COMMENT 'Command for cron job',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID having schedule and all info',
  `lastrun` varchar(60) NOT NULL COMMENT 'Time the cron was last run'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING CRON JOB DETAILS';

-- --------------------------------------------------------

--
-- Table structure for table `serverdb`
--

DROP TABLE IF EXISTS `serverdb`;
CREATE TABLE `serverdb` (
  `serverid` varchar(50) NOT NULL COMMENT 'Server ID',
  `par_serverid` varchar(50) NOT NULL COMMENT 'Server ID of the parent server',
  `serverip` varchar(20) NOT NULL COMMENT 'IP address of server',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the server was added',
  `serverstatus` tinyint(4) NOT NULL DEFAULT '1' COMMENT 'Status of server (1-Online,2-Offline,3-Starting,4-Stopping)',
  `jsonid` varchar(50) NOT NULL COMMENT 'JSON ID from JSON DB having meta data for the server'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING THE SERVERS';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `serverdb`
--
ALTER TABLE `serverdb`
  ADD PRIMARY KEY (`serverid`);
--
-- Database: `ta_transactions`
--
DROP DATABASE IF EXISTS `ta_transactions`;
CREATE DATABASE IF NOT EXISTS `ta_transactions` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_transactions`;

-- --------------------------------------------------------

--
-- Table structure for table `transactions_money`
--

DROP TABLE IF EXISTS `transactions_money`;
CREATE TABLE `transactions_money` (
  `uid` varchar(50) NOT NULL COMMENT 'User ID of the person making the payment',
  `payamount_total` double NOT NULL COMMENT 'Total amount paid by the user',
  `actualamount` double NOT NULL COMMENT 'Actual amount paid (Without taxes)',
  `taxamount` double NOT NULL COMMENT 'Tax Amount',
  `transactiontime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Time the transaction occured',
  `transactionip` varchar(40) NOT NULL COMMENT 'IP Address of the PC in which the transaction occured',
  `address` text NOT NULL COMMENT 'Address of the User',
  `ruid` varchar(50) NOT NULL COMMENT 'UID of the person who receives the money (000000 if TA receives it) Leave it empty if receiver not in TA',
  `facctno` varchar(100) NOT NULL COMMENT 'Account no from which payment is made',
  `transactiondesc` text NOT NULL COMMENT 'Complete Description Regarding the transaction',
  `transactionbank` varchar(400) NOT NULL COMMENT 'Bank using which the transaction is done',
  `transactionid` varchar(50) NOT NULL COMMENT 'Unique ID of the transaction',
  `transactiontype` tinyint(4) NOT NULL COMMENT 'Flag value specifying type of transaction (1-Credit Card,2-Debit Card,3-Internet Banking,4-Cash,5-DD,6-Cheque,7-Barter,8-Points)',
  `locid` varchar(50) NOT NULL COMMENT 'Location ID specifying the location regarding the payment',
  `servicetype` tinyint(4) NOT NULL COMMENT 'Flag value specifying type of service (1-AD Payment,2-Product Buy,3-Service Buy,4-Storage Up-gradation,5-Commission,6-Fines,7-Deposit)',
  `paystatus` tinyint(4) NOT NULL COMMENT 'Payment Status (1-Paid,2-Processing,3-Pending,4-Pay failed,5-Refunded)',
  `tacctno` varchar(100) NOT NULL COMMENT 'Account No to which payment is made'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS REGARDING ALL THE MONEY TRANSACTIONS';

-- --------------------------------------------------------

--
-- Table structure for table `user_reppoints`
--

DROP TABLE IF EXISTS `user_reppoints`;
CREATE TABLE `user_reppoints` (
  `sno` int(11) NOT NULL,
  `uid` varchar(30) NOT NULL COMMENT 'user id',
  `points` decimal(10,2) NOT NULL COMMENT 'user points (may be positive or negative)',
  `reason` text NOT NULL COMMENT 'reason for adding points',
  `appid` varchar(50) NOT NULL COMMENT 'appid adding points',
  `addtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time points was added'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING DETAILS ABOUT REPUTATION POINTS ADDED';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user_reppoints`
--
ALTER TABLE `user_reppoints`
  ADD PRIMARY KEY (`sno`),
  ADD KEY `uid` (`uid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user_reppoints`
--
ALTER TABLE `user_reppoints`
  MODIFY `sno` int(11) NOT NULL AUTO_INCREMENT;--
-- Database: `ta_ui`
--
DROP DATABASE IF EXISTS `ta_ui`;
CREATE DATABASE IF NOT EXISTS `ta_ui` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ta_ui`;

-- --------------------------------------------------------

--
-- Table structure for table `icondb`
--

DROP TABLE IF EXISTS `icondb`;
CREATE TABLE `icondb` (
  `iconid` varchar(50) NOT NULL COMMENT 'a unique id for the icon',
  `iconurl` varchar(600) NOT NULL COMMENT 'url of the icon',
  `iconname` varchar(300) NOT NULL COMMENT 'name of the icon',
  `height` int(11) NOT NULL COMMENT 'height of the icon',
  `width` int(11) NOT NULL COMMENT 'width of the icon',
  `iconkey` varchar(300) NOT NULL COMMENT 'user given key for the icon',
  `icontype` tinyint(4) NOT NULL COMMENT 'flag value specifying icon type (1-smileys)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING LIST OF ALL ICONS';

-- --------------------------------------------------------

--
-- Table structure for table `themedb`
--

DROP TABLE IF EXISTS `themedb`;
CREATE TABLE `themedb` (
  `themeid` varchar(50) NOT NULL COMMENT 'unique id of the theme',
  `themename` varchar(200) NOT NULL COMMENT 'name of the theme',
  `themecss` varchar(500) NOT NULL COMMENT 'url of the css file of the theme',
  `themedesc` text NOT NULL COMMENT 'description of the theme',
  `themeuid` varchar(50) NOT NULL COMMENT 'user id of the person creating the theme',
  `themeflag` tinyint(4) NOT NULL COMMENT 'permission flag 1-allowed,2-under review,3-blocked',
  `totalrates` bigint(20) NOT NULL COMMENT 'total rating of this theme',
  `noofrates` bigint(20) NOT NULL COMMENT 'total no of people who rated this theme',
  `prodflag` tinyint(4) NOT NULL COMMENT 'product flag (1-universal,2-tahoy,3-mplace,4-temple)',
  `themecreatetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'time of creation of the theme',
  `galid` varchar(50) NOT NULL COMMENT 'id of the gallery which has the theme images for preview',
  `themeprivacy` tinyint(4) NOT NULL COMMENT 'flag value specifying theme privacy (1-public,2-private,others-list id from list db)'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='TABLE HAVING ALL THEME INFORMATION';

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
