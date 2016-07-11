-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 2016-07-06 03:18:54
-- 服务器版本： 10.1.10-MariaDB
-- PHP Version: 7.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teachingplatform`
--

-- --------------------------------------------------------

--
-- 表的结构 `course`
--

CREATE TABLE `course` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '课程主键自增id',
  `name` varchar(20) DEFAULT NULL COMMENT '课程名称',
  `description` varchar(30) DEFAULT NULL COMMENT '课程描述'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `course`
--

INSERT INTO `course` (`id`, `name`, `description`) VALUES
(1, '工科高等数学(1)', '数学工具之重要'),
(2, '大学语文', '读书破万卷'),
(3, '大学英语(1)', '学好英语用处大'),
(4, '工程认识', '工程学方法'),
(5, '高级语言程序设计(1)', 'C++');

-- --------------------------------------------------------

--
-- 表的结构 `course_offered`
--

CREATE TABLE `course_offered` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '开设课程的自增主键id',
  `teacher_username` char(4) DEFAULT NULL COMMENT '开课教师账号',
  `school_number` tinyint(2) DEFAULT NULL COMMENT '开课学院编号',
  `semester_id` int(11) UNSIGNED DEFAULT NULL COMMENT '开课学期编号',
  `course_id` int(11) UNSIGNED DEFAULT NULL COMMENT '课程所在编号',
  `addition_des` varchar(255) DEFAULT NULL COMMENT '课程附加描述',
  `resource_str` text COMMENT '课程资源序列串',
  `status` enum('0','1','','') NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `course_offered`
--

INSERT INTO `course_offered` (`id`, `teacher_username`, `school_number`, `semester_id`, `course_id`, `addition_des`, `resource_str`, `status`) VALUES
(42, '0101', 1, 1, 1, NULL, NULL, '1'),
(43, '0101', 1, 1, 2, NULL, NULL, '1'),
(44, '0101', 1, 1, 3, NULL, NULL, '1'),
(45, '0101', 1, 1, 4, NULL, NULL, '1'),
(46, '0101', 1, 2, 1, NULL, NULL, '1'),
(47, '0101', 1, 2, 2, NULL, NULL, '1'),
(48, '0101', 1, 2, 3, NULL, NULL, '1'),
(49, '0101', 1, 2, 4, NULL, NULL, '1'),
(50, '0102', 1, 1, 1, NULL, NULL, '1'),
(51, '0102', 1, 1, 2, NULL, NULL, '1'),
(52, '0102', 1, 1, 3, NULL, NULL, '1'),
(53, '0102', 1, 1, 4, NULL, NULL, '1'),
(54, '0102', 1, 2, 1, NULL, NULL, '1'),
(55, '0102', 1, 2, 2, NULL, NULL, '1'),
(56, '0102', 1, 2, 3, NULL, NULL, '1'),
(57, '0102', 1, 2, 4, NULL, NULL, '1'),
(58, '2101', 21, 1, 1, NULL, NULL, '1'),
(59, '2101', 21, 1, 2, NULL, NULL, '1'),
(60, '2101', 21, 1, 3, NULL, NULL, '1'),
(61, '2101', 21, 1, 4, NULL, NULL, '1'),
(62, '2101', 21, 2, 1, NULL, NULL, '1'),
(63, '2101', 21, 2, 2, NULL, NULL, '1'),
(64, '2101', 21, 2, 3, NULL, NULL, '1'),
(65, '2101', 21, 2, 4, NULL, NULL, '1'),
(66, '2102', 21, 1, 1, NULL, NULL, '1'),
(67, '2102', 21, 1, 2, NULL, NULL, '1'),
(68, '2102', 21, 1, 3, NULL, NULL, '1'),
(69, '2102', 21, 1, 4, NULL, NULL, '1'),
(70, '2102', 21, 2, 1, NULL, NULL, '1'),
(71, '2102', 21, 2, 2, NULL, NULL, '1'),
(72, '2102', 21, 2, 3, NULL, NULL, '1'),
(73, '2102', 21, 2, 4, NULL, NULL, '1');

-- --------------------------------------------------------

--
-- 表的结构 `course_student`
--

CREATE TABLE `course_student` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '学生课程表自增id',
  `course_offered_id` int(11) UNSIGNED DEFAULT NULL COMMENT '开设课程id，外键到course_offered',
  `student_username` char(8) DEFAULT NULL COMMENT '学生账号，外键到student表',
  `course_team_id` int(11) UNSIGNED DEFAULT NULL COMMENT '学生当前所在选课团队id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `course_student`
--

INSERT INTO `course_student` (`id`, `course_offered_id`, `student_username`, `course_team_id`) VALUES
(6, 42, '13010001', NULL),
(7, 42, '13010002', NULL),
(8, 42, '13210001', NULL),
(9, 42, '12210001', NULL),
(10, 43, '13010001', NULL),
(11, 43, '13010002', NULL),
(12, 43, '13210001', NULL),
(13, 43, '12210001', NULL),
(14, 44, '13010001', NULL),
(15, 44, '13010002', NULL),
(16, 44, '13210001', NULL),
(17, 44, '12210001', NULL),
(18, 45, '13010001', NULL),
(19, 45, '13010002', NULL),
(20, 45, '13210001', NULL),
(21, 45, '12210001', NULL),
(22, 46, '13010001', NULL),
(23, 46, '13010002', NULL),
(24, 46, '13210001', NULL),
(25, 46, '12210001', NULL),
(26, 47, '13010001', NULL),
(27, 47, '13010002', NULL),
(28, 47, '13210001', NULL),
(29, 47, '12210001', NULL),
(30, 48, '13010001', NULL),
(31, 48, '13010002', NULL),
(32, 48, '13210001', NULL),
(33, 48, '12210001', NULL),
(34, 49, '13010001', NULL),
(35, 49, '13010002', NULL),
(36, 49, '13210001', NULL),
(37, 49, '12210001', NULL),
(38, 50, '13010001', NULL),
(39, 50, '13010002', NULL),
(40, 50, '13210001', NULL),
(41, 50, '12210001', NULL),
(42, 51, '13010001', NULL),
(43, 51, '13010002', NULL),
(44, 51, '13210001', NULL),
(45, 51, '12210001', NULL),
(46, 52, '13010001', NULL),
(47, 52, '13010002', NULL),
(48, 52, '13210001', NULL),
(49, 52, '12210001', NULL),
(50, 53, '13010001', NULL),
(51, 53, '13010002', NULL),
(52, 53, '13210001', NULL),
(53, 53, '12210001', NULL),
(54, 54, '13010001', NULL),
(55, 54, '13010002', NULL),
(56, 54, '13210001', NULL),
(57, 54, '12210001', NULL),
(58, 55, '13010001', NULL),
(59, 55, '13010002', NULL),
(60, 55, '13210001', NULL),
(61, 55, '12210001', NULL),
(62, 56, '13010001', NULL),
(63, 56, '13010002', NULL),
(64, 56, '13210001', NULL),
(65, 56, '12210001', NULL),
(66, 57, '13010001', NULL),
(67, 57, '13010002', NULL),
(68, 57, '13210001', NULL),
(69, 57, '12210001', NULL),
(70, 58, '13010001', NULL),
(71, 58, '13010002', NULL),
(72, 58, '13210001', NULL),
(73, 58, '12210001', NULL),
(74, 59, '13010001', NULL),
(75, 59, '13010002', NULL),
(76, 59, '13210001', NULL),
(77, 59, '12210001', NULL),
(78, 60, '13010001', NULL),
(79, 60, '13010002', NULL),
(80, 60, '13210001', NULL),
(81, 60, '12210001', NULL),
(82, 61, '13010001', NULL),
(83, 61, '13010002', NULL),
(84, 61, '13210001', NULL),
(85, 61, '12210001', NULL),
(86, 62, '13010001', NULL),
(87, 62, '13010002', NULL),
(88, 62, '13210001', NULL),
(89, 62, '12210001', NULL),
(90, 63, '13010001', NULL),
(91, 63, '13010002', NULL),
(92, 63, '13210001', NULL),
(93, 63, '12210001', NULL),
(94, 64, '13010001', NULL),
(95, 64, '13010002', NULL),
(96, 64, '13210001', NULL),
(97, 64, '12210001', NULL),
(98, 65, '13010001', NULL),
(99, 65, '13010002', NULL),
(100, 65, '13210001', NULL),
(101, 65, '12210001', NULL),
(102, 66, '13010001', NULL),
(103, 66, '13010002', NULL),
(104, 66, '13210001', NULL),
(105, 66, '12210001', NULL),
(106, 67, '13010001', NULL),
(107, 67, '13010002', NULL),
(108, 67, '13210001', NULL),
(109, 67, '12210001', NULL),
(110, 68, '13010001', NULL),
(111, 68, '13010002', NULL),
(112, 68, '13210001', NULL),
(113, 68, '12210001', NULL),
(114, 69, '13010001', NULL),
(115, 69, '13010002', NULL),
(116, 69, '13210001', NULL),
(117, 69, '12210001', NULL),
(118, 70, '13010001', NULL),
(119, 70, '13010002', NULL),
(120, 70, '13210001', NULL),
(121, 70, '12210001', NULL),
(122, 71, '13010001', NULL),
(123, 71, '13010002', NULL),
(124, 71, '13210001', NULL),
(125, 71, '12210001', NULL),
(126, 72, '13010001', NULL),
(127, 72, '13010002', NULL),
(128, 72, '13210001', NULL),
(129, 72, '12210001', NULL),
(130, 73, '13010001', NULL),
(131, 73, '13010002', NULL),
(132, 73, '13210001', NULL),
(133, 73, '12210001', NULL);

-- --------------------------------------------------------

--
-- 表的结构 `course_team`
--

CREATE TABLE `course_team` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '选课团队id',
  `course_offered_id` int(11) UNSIGNED DEFAULT NULL COMMENT '开设课程id，外键关联到courses_offered',
  `team_id` int(11) UNSIGNED DEFAULT NULL COMMENT '原始团队id，关联到team表',
  `owner_username` char(8) DEFAULT NULL COMMENT '团队负责人账号-外键到stduent表',
  `course_teammate_str` text COMMENT '选课团队组员账号序列',
  `name` varchar(20) DEFAULT NULL COMMENT '选课团队组名',
  `description` varchar(30) DEFAULT NULL COMMENT '选课团队简介',
  `state` enum('4','3','2','1') DEFAULT NULL COMMENT '选课团队状态 1- 正常，审核通过 2-待审核 3-审核不通过 4-解散'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `educational_admin`
--

CREATE TABLE `educational_admin` (
  `username` char(3) NOT NULL DEFAULT '' COMMENT '教务人员账号 3位 以0开头',
  `name` varchar(12) DEFAULT NULL COMMENT '教务姓名',
  `gender` enum('0','1') DEFAULT NULL COMMENT '教务性别 1 男性 0 女性',
  `birth` date DEFAULT NULL COMMENT '用户出生年月',
  `state` enum('0','1') DEFAULT NULL COMMENT '教务人员状态 1 - 在职 0 - 离职'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `educational_admin`
--

INSERT INTO `educational_admin` (`username`, `name`, `gender`, `birth`, `state`) VALUES
('001', '1号教务人员', '1', '1970-11-06', '1'),
('002', '2号教务人员', '0', '1981-02-27', '1');

-- --------------------------------------------------------

--
-- 表的结构 `homework`
--

CREATE TABLE `homework` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '教师布置作业id',
  `name` varchar(30) DEFAULT NULL COMMENT '作业名称',
  `description` text COMMENT '作业描述内容',
  `publish_date` datetime DEFAULT NULL COMMENT '作业发布时间',
  `start_date` datetime DEFAULT NULL COMMENT '作业开始时间',
  `end_date` datetime DEFAULT NULL COMMENT '作业截止时间',
  `type` enum('3','2','1') DEFAULT NULL COMMENT '作业类型 1-个人（默认） 2-团队 3-不限制',
  `resource_str` text COMMENT '作业附属资源id序列',
  `course_offered_id` int(11) UNSIGNED DEFAULT NULL COMMENT '作业所在开设课程id'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `log`
--

CREATE TABLE `log` (
  `id` bigint(11) UNSIGNED NOT NULL COMMENT '日志id',
  `from_id` varchar(12) DEFAULT NULL COMMENT '操作者id',
  `from_table` varchar(12) DEFAULT NULL COMMENT '操作者所在表名',
  `to_id` varchar(12) DEFAULT NULL COMMENT '被操作者id',
  `to_table` varchar(12) DEFAULT NULL COMMENT '被操作者所在表名',
  `record` text COMMENT '操作记录',
  `date` datetime DEFAULT NULL COMMENT '操作发生时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `resource`
--

CREATE TABLE `resource` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '资源自增id',
  `name` text DEFAULT NULL COMMENT '资源名称',
  `description` varchar(30) DEFAULT NULL COMMENT '资源描述',
  `publish_time` datetime DEFAULT NULL COMMENT '资源发布时间',
  `place` text DEFAULT NULL COMMENT '资源存储路径',
  `owner_username` varchar(8) DEFAULT NULL COMMENT '资源发布者账号',
  `owner_course_team_id` int(11) UNSIGNED DEFAULT NULL COMMENT '资源附属选课团队id',
  `owner_course_team_str` text COMMENT '资源附属选课团队当前成员账号序列'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `school`
--

CREATE TABLE `school` (
  `number` tinyint(2) NOT NULL DEFAULT '0' COMMENT '学院编号 最短一位 最长两位 由教务人员制定',
  `name` varchar(20) DEFAULT NULL COMMENT '学院名称',
  `description` text COMMENT '学院介绍',
  `state` enum('0','1') DEFAULT NULL COMMENT '学院状态 1-在办 0 - 停办 '
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `school`
--

INSERT INTO `school` (`number`, `name`, `description`, `state`) VALUES
(1, '材料科学与工程学院', '北京航空航天大学材料科学与工程学院起源于1954年成立的航空冶金系，2001年建立材料科学与工程学院，下设材料科学系、材料物理与化学系、材料加工工程与自动化系、高分子及复合材料系。拥有材料科学与工程一级学科博士点，下设材料学、材料物理与化学、材料加工工程3个二级学科博士点和信息功能材料、微纳米技术和材料结构失效与安全工程3个自主设置学科博士点。材料科学与工程学科为国家一级重点学科。', '1'),
(21, '软件学院', '北京航空航天大学软件学院是2002年经国家教育部和国家纪委联合批准成立的全国37所国家示范性软件学院之一。北航软件学院以创办一所能够在一种新的办学机制下，规模培养全面和谐发展的、创新型的、国际化的、市场急需的工程实用性人才的国内一流学院为发展目标，以培养高层次、实用性、复合型、国际化的软件工程专业人才为宗旨，以期为振兴中国软件产业做出贡献，为创建适应行业需求和市场导向的新型办学机制探路，为软件工程高端人才培养做示范，为推进工程教育的改革提供实践经验。学院目前在校本科生634人；在校研究生超过4000人，已向软件行业输送毕业研究生5786人（截止2012年12月）。', '1');

-- --------------------------------------------------------

--
-- 表的结构 `sclass`
--

CREATE TABLE `sclass` (
  `number` char(6) NOT NULL DEFAULT '' COMMENT '班级编号 入学年份(2位)-院系编号(2位)-班级编号(2位) 一共6位，由教务制定',
  `name` varchar(20) DEFAULT NULL COMMENT '班级名称',
  `school_number` tinyint(2) DEFAULT NULL COMMENT '班级所在学院编号'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `sclass`
--

INSERT INTO `sclass` (`number`, `name`, `school_number`) VALUES
('122101', '122101班', 21),
('130101', '130101班', 1),
('132101', '132101班', 21);

-- --------------------------------------------------------

--
-- 表的结构 `semester`
--

CREATE TABLE `semester` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '学期自增主键',
  `name` varchar(20) DEFAULT NULL COMMENT '学期名称',
  `start_date` date DEFAULT NULL COMMENT '学期开始时间',
  `end_date` date DEFAULT NULL COMMENT '学期结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `semester`
--

INSERT INTO `semester` (`id`, `name`, `start_date`, `end_date`) VALUES
(1, '2016年春', '2016-02-29', '2016-07-03'),
(2, '2015年秋', '2015-09-14', '2016-01-24');

-- --------------------------------------------------------

--
-- 表的结构 `student`
--

CREATE TABLE `student` (
  `username` char(8) NOT NULL DEFAULT '' COMMENT '学生用户账号 8位 主键',
  `name` varchar(12) DEFAULT NULL COMMENT '学生姓名',
  `gender` enum('0','1') DEFAULT NULL COMMENT '学生性别 1-男生 0 - 女生',
  `birth` date DEFAULT NULL COMMENT '学生出生日期',
  `address` varchar(20) DEFAULT NULL COMMENT '学生住址',
  `telephone` varchar(11) DEFAULT NULL COMMENT '学生联系电话',
  `email` varchar(20) DEFAULT NULL COMMENT '学生电子邮箱',
  `enrollment_year` int(4) DEFAULT NULL COMMENT '学生入学年份',
  `graduation_year` int(4) DEFAULT NULL COMMENT '学生毕业年份 （默认由入学年份加4，其他情况由教务制定）',
  `class_number` char(6) DEFAULT NULL COMMENT '学生所在班级 关联到class表',
  `school_number` tinyint(2) DEFAULT NULL COMMENT '学生所在院系编号',
  `now_team_str` text COMMENT '学生目前所在团队序列',
  `old_team_str` text COMMENT '学生曾经所在团队序列'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `student`
--

INSERT INTO `student` (`username`, `name`, `gender`, `birth`, `address`, `telephone`, `email`, `enrollment_year`, `graduation_year`, `class_number`, `school_number`, `now_team_str`, `old_team_str`) VALUES
('12210001', '21系A同学', '1', '1993-04-23', '北京市海淀区学院路37号　', '13240327922', 'xxx@sina.com.cn', 2012, 2016, '122101', 21, NULL, NULL),
('13010001', '1系A同学', '1', '1994-06-05', '北京市海淀区学院路37号　', '13240327922', '13240327922@buaa.edu', 2013, 2017, '130101', 1, NULL, NULL),
('13010002', '1系B同学', '0', '1994-11-14', '北京市海淀区学院路37号　', '85823700', 'buaa@buaa.edu.cn', 2013, 2017, '130101', 1, NULL, NULL),
('13210001', '21系B同学', '1', '1994-02-12', '北京市海淀区学院路37号　', '82314047', 'qqq@qq.com', 2013, 2017, '132101', 21, NULL, NULL);

-- --------------------------------------------------------

--
-- 表的结构 `submit_homework`
--

CREATE TABLE `submit_homework` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '学生提交作业id',
  `homework_id` int(11) UNSIGNED DEFAULT NULL COMMENT '所属的老师布置作业id,外键到homework',
  `submit_time` datetime DEFAULT NULL COMMENT '作业提交时间',
  `comment` varchar(30) DEFAULT NULL COMMENT '教师对作业的评价',
  `grade` double(11,2) DEFAULT NULL COMMENT '教师对作业的评分',
  `result_time` datetime DEFAULT NULL COMMENT '教师反馈作业时间',
  `type` enum('2','1') DEFAULT NULL COMMENT '作业提交类型 1 -个人 2-团队',
  `submit_username` char(8) DEFAULT NULL COMMENT '作业提交者用户账号，关联到student表',
  `submit_course_team_id` int(11) UNSIGNED DEFAULT NULL COMMENT '提交作业的选课团队id',
  `submit_course_team_owner_username` char(8) DEFAULT NULL,
  `submit_course_team_str` text COMMENT '提交作业的选课团队组员账号序列',
  `name` varchar(30) DEFAULT NULL COMMENT '提交名称',
  `words` text COMMENT '提交的文本内容',
  `resource_str` text COMMENT '提交的资源序列',
  `state` enum('3','2','0','1') DEFAULT NULL COMMENT '提交状态 0-未提交 1-已保存 2-已提交 3-已评分'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `teacher`
--

CREATE TABLE `teacher` (
  `username` char(4) NOT NULL DEFAULT '' COMMENT '教师账号 作为主键 ',
  `name` varchar(20) DEFAULT NULL COMMENT '教师姓名',
  `gender` enum('0','1') DEFAULT NULL COMMENT '教师性别 1-男生 0-女生',
  `birth` date DEFAULT NULL COMMENT '教师出生年月',
  `address` varchar(20) DEFAULT NULL COMMENT '教师住址',
  `telephone` varchar(11) DEFAULT NULL COMMENT '教师联系电话',
  `email` varchar(20) DEFAULT NULL COMMENT '教师电子邮箱',
  `state` enum('0','1') DEFAULT NULL COMMENT '教师状态 1- 在职 0 - 离职 由教务制定',
  `school_number` tinyint(2) DEFAULT NULL COMMENT '所在院系序号 外键到school表',
  `rank` varchar(11) DEFAULT NULL COMMENT '教师职称'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `teacher`
--

INSERT INTO `teacher` (`username`, `name`, `gender`, `birth`, `address`, `telephone`, `email`, `state`, `school_number`, `rank`) VALUES
('0101', '1系1号教师', '1', '1990-07-05', '北京市东花市北里20号楼6单元501室', '13240327919', '13240327919@qq.com', '1', 1, '讲师'),
('0102', '1系2号教师', '0', '1989-02-05', '北京市东花市北里20号楼6单元502室', '13240327929', '13240327929@163.com', '1', 1, '副教授'),
('2101', '21系1号老师', '1', '1955-07-15', '北京市东花市北里20号楼6单元503室', '13240327939', '13240327939@buaa.edu', '1', 21, '教授'),
('2102', '21系2号老师', '1', '1980-02-05', '北京市东花市北里20号楼6单元504室', NULL, NULL, '1', 21, '讲师');

-- --------------------------------------------------------

--
-- 表的结构 `team`
--

CREATE TABLE `team` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '团队自增主键id',
  `name` varchar(20) DEFAULT NULL COMMENT '团队名称',
  `description` varchar(200) DEFAULT NULL COMMENT '团队描述',
  `owner` varchar(20) DEFAULT NULL COMMENT '团队负责人账号 即外键到某一学生账号',
  `now_teammate_str` text COMMENT '团队组员序列',
  `create_time` datetime DEFAULT NULL COMMENT '团队创建时间',
  `old_teammate_str` text COMMENT '离开团队组员账号',
  `state` enum('0','1') DEFAULT NULL COMMENT '团队状态 1 - 正常 0 - 解散'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 表的结构 `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL COMMENT '用户表自增主键id',
  `username` varchar(8) DEFAULT NULL COMMENT '用户账号\r\n分为四种\r\n· 管理员 admin\r\n· 教务人员 0+XX\r\n· 教师人员 1+院系编号(2位)+个人编号(2位)\r\n· 学生人员 年份(2位)+院系(2位)+个人编号(4位)\r\n',
  `password` varchar(100) DEFAULT NULL COMMENT '用户密码64位 md5加密',
  `type` enum('SA','EA','S','T') DEFAULT NULL COMMENT '用户类型 T-教师 S-学生 EA-教务 SA-管理员',
  `last_login` datetime DEFAULT NULL COMMENT '用户上次登录时间',
  `login_record` text COMMENT '用户登录记录-保留10次用户登录记录\r\n保存格式 json串\r\n{ { ip， time} }',
  `is_online` enum('0','1') DEFAULT NULL COMMENT '用户在线状态 0 - 下线 1-在线',
  `remember_token` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `type`, `last_login`, `login_record`, `is_online`, `remember_token`) VALUES
(1, 'admin', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'SA', NULL, NULL, NULL, 'wGjetCW3DfCXbVofP091HOUCPA1zlPJLGeVheGymaODIthJaUEsp8w20vQZZ'),
(2, '001', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'EA', NULL, NULL, NULL, ''),
(3, '002', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'EA', NULL, NULL, NULL, ''),
(4, '2101', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'T', NULL, NULL, NULL, ''),
(5, '2102', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'T', NULL, NULL, NULL, ''),
(6, '0101', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'T', NULL, NULL, NULL, ''),
(7, '0102', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'T', NULL, NULL, NULL, ''),
(8, '13010001', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'S', NULL, NULL, NULL, ''),
(9, '13010002', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'S', NULL, NULL, NULL, ''),
(10, '12210001', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'S', NULL, NULL, NULL, ''),
(11, '13210001', '$2y$10$Zuxg2al05FH1kMjgusi1wObbAMsNs6GBS/rgJ/Ei7jc7SU4AymDCW', 'S', NULL, NULL, NULL, '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course_offered`
--
ALTER TABLE `course_offered`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_courses_offered_teacher_username` (`teacher_username`),
  ADD KEY `fk_courses_offered_school_number` (`school_number`),
  ADD KEY `fk_courses_offered_semester_id` (`semester_id`) USING BTREE,
  ADD KEY `fk_courses_offered_course_id` (`course_id`) USING BTREE;

--
-- Indexes for table `course_student`
--
ALTER TABLE `course_student`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_student_course_offered_id` (`course_offered_id`),
  ADD KEY `fk_course_student_student_username` (`student_username`),
  ADD KEY `fk_course_student_course_team_id` (`course_team_id`);

--
-- Indexes for table `course_team`
--
ALTER TABLE `course_team`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_course_team_course_offered_id` (`course_offered_id`),
  ADD KEY `fk_course_team_team_id` (`team_id`),
  ADD KEY `fk_course_team_owner_username` (`owner_username`);

--
-- Indexes for table `educational_admin`
--
ALTER TABLE `educational_admin`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_homework_course_offered_id` (`course_offered_id`);

--
-- Indexes for table `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `resource`
--
ALTER TABLE `resource`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_resource_owner_course_team_id` (`owner_course_team_id`);

--
-- Indexes for table `school`
--
ALTER TABLE `school`
  ADD PRIMARY KEY (`number`);

--
-- Indexes for table `sclass`
--
ALTER TABLE `sclass`
  ADD PRIMARY KEY (`number`),
  ADD KEY `fk_class_school_number` (`school_number`),
  ADD KEY `number` (`number`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`username`),
  ADD KEY `fk_student_school_number` (`school_number`),
  ADD KEY `fk_student_class_number` (`class_number`);

--
-- Indexes for table `submit_homework`
--
ALTER TABLE `submit_homework`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_submit_homework_homework_id` (`homework_id`),
  ADD KEY `fk_submit_homework_submit_username` (`submit_username`),
  ADD KEY `fk_submit_homework_course_team_owner_username` (`submit_course_team_owner_username`),
  ADD KEY `fk_submit_homework_course_team_id` (`submit_course_team_id`);

--
-- Indexes for table `teacher`
--
ALTER TABLE `teacher`
  ADD PRIMARY KEY (`username`),
  ADD KEY `fk_teacher_school_number` (`school_number`);

--
-- Indexes for table `team`
--
ALTER TABLE `team`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '课程主键自增id', AUTO_INCREMENT=6;
--
-- 使用表AUTO_INCREMENT `course_offered`
--
ALTER TABLE `course_offered`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '开设课程的自增主键id', AUTO_INCREMENT=74;
--
-- 使用表AUTO_INCREMENT `course_student`
--
ALTER TABLE `course_student`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '学生课程表自增id', AUTO_INCREMENT=134;
--
-- 使用表AUTO_INCREMENT `course_team`
--
ALTER TABLE `course_team`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '选课团队id';
--
-- 使用表AUTO_INCREMENT `homework`
--
ALTER TABLE `homework`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '教师布置作业id';
--
-- 使用表AUTO_INCREMENT `log`
--
ALTER TABLE `log`
  MODIFY `id` bigint(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '日志id';
--
-- 使用表AUTO_INCREMENT `resource`
--
ALTER TABLE `resource`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '资源自增id';
--
-- 使用表AUTO_INCREMENT `semester`
--
ALTER TABLE `semester`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '学期自增主键', AUTO_INCREMENT=3;
--
-- 使用表AUTO_INCREMENT `submit_homework`
--
ALTER TABLE `submit_homework`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '学生提交作业id';
--
-- 使用表AUTO_INCREMENT `team`
--
ALTER TABLE `team`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '团队自增主键id';
--
-- 使用表AUTO_INCREMENT `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户表自增主键id', AUTO_INCREMENT=12;
--
-- 限制导出的表
--

--
-- 限制表 `course_offered`
--
ALTER TABLE `course_offered`
  ADD CONSTRAINT `fk_courses_offered_course_id` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_courses_offered_school_number` FOREIGN KEY (`school_number`) REFERENCES `school` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_courses_offered_semester_id` FOREIGN KEY (`semester_id`) REFERENCES `semester` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_courses_offered_teacher_username` FOREIGN KEY (`teacher_username`) REFERENCES `teacher` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `course_student`
--
ALTER TABLE `course_student`
  ADD CONSTRAINT `fk_course_student_course_offered_id` FOREIGN KEY (`course_offered_id`) REFERENCES `course_offered` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_student_course_team_id` FOREIGN KEY (`course_team_id`) REFERENCES `course_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_student_student_username` FOREIGN KEY (`student_username`) REFERENCES `student` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `course_team`
--
ALTER TABLE `course_team`
  ADD CONSTRAINT `fk_course_team_course_offered_id` FOREIGN KEY (`course_offered_id`) REFERENCES `course_offered` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_team_owner_username` FOREIGN KEY (`owner_username`) REFERENCES `student` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_course_team_team_id` FOREIGN KEY (`team_id`) REFERENCES `team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `fk_homework_course_offered_id` FOREIGN KEY (`course_offered_id`) REFERENCES `course_offered` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `resource`
--
ALTER TABLE `resource`
  ADD CONSTRAINT `fk_resource_owner_course_team_id` FOREIGN KEY (`owner_course_team_id`) REFERENCES `course_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `sclass`
--
ALTER TABLE `sclass`
  ADD CONSTRAINT `fk_class_school_number` FOREIGN KEY (`school_number`) REFERENCES `school` (`number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `fk_student_class_number` FOREIGN KEY (`class_number`) REFERENCES `sclass` (`number`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_school_number` FOREIGN KEY (`school_number`) REFERENCES `school` (`number`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `submit_homework`
--
ALTER TABLE `submit_homework`
  ADD CONSTRAINT `fk_submit_homework_course_team_id` FOREIGN KEY (`submit_course_team_id`) REFERENCES `course_team` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_submit_homework_course_team_owner_username` FOREIGN KEY (`submit_course_team_owner_username`) REFERENCES `student` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_submit_homework_homework_id` FOREIGN KEY (`homework_id`) REFERENCES `homework` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_submit_homework_submit_username` FOREIGN KEY (`submit_username`) REFERENCES `student` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 限制表 `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `fk_teacher_school_number` FOREIGN KEY (`school_number`) REFERENCES `school` (`number`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
