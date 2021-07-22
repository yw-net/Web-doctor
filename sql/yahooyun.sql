-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2021 at 03:31 PM
-- Server version: 5.7.21
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yahooyun`
--

-- --------------------------------------------------------

--
-- Table structure for table `complementary_treatment_baxiang`
--

CREATE TABLE `complementary_treatment_baxiang` (
  `id` int(11) NOT NULL,
  `baxiang_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `baxiang_yaowu` text COMMENT '药物名称',
  `baxiang_start_time` text COMMENT '开始时间',
  `baxiang_end_time` text COMMENT '结束时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `complementary_treatment_fangliao`
--

CREATE TABLE `complementary_treatment_fangliao` (
  `id` int(11) NOT NULL,
  `fangliao_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `fangliao_time` text COMMENT '放疗时间',
  `fangliao_feibu1` text COMMENT '肺部GY',
  `fangliao_feibu2` text COMMENT '肺部f',
  `fangliao_naobu1` text COMMENT '脑部GY',
  `fangliao_naobu2` text COMMENT '脑部f',
  `fangliao_guge1` text COMMENT '骨骼GY',
  `fangliao_guge2` text COMMENT '骨骼f',
  `fangliao_other1` text COMMENT '其他GY',
  `fangliao_other2` text COMMENT '其他f'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `complementary_treatment_gamadao`
--

CREATE TABLE `complementary_treatment_gamadao` (
  `id` int(11) NOT NULL,
  `gamadao_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `gamadao_time` text COMMENT '伽玛刀时间',
  `gamadao_feibu1` text COMMENT '肺部GY',
  `gamadao_feibu2` text COMMENT '肺部f',
  `gamadao_naobu1` text COMMENT '脑部GY',
  `gamadao_naobu2` text COMMENT '脑部f',
  `gamadao_guge1` text COMMENT '骨骼GY',
  `gamadao_guge2` text COMMENT '骨骼f',
  `gamadao_ganbu1` text COMMENT '肝部GY',
  `gamadao_ganbu2` text COMMENT '肝部f',
  `gamadao_other1` text COMMENT '其他GY',
  `gamadao_other2` text COMMENT '其他f'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `complementary_treatment_hualiao`
--

CREATE TABLE `complementary_treatment_hualiao` (
  `id` int(11) NOT NULL,
  `hualiao_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `hualiao_start_time` text COMMENT '开始时间',
  `hualiao_end_time` text COMMENT '结束时间',
  `hualiao_zhouqi` text COMMENT '化疗周期',
  `hualiao_fangan` text COMMENT '化疗方案',
  `hualiao_xuehongdanbai` text COMMENT '血红蛋白',
  `hualiao_lixibao` text COMMENT '粒细胞',
  `hualiao_baixibao` text COMMENT '白细胞',
  `hualiao_xuexiaoban` text COMMENT '血小板',
  `hualiao_chuxue` text COMMENT '出血',
  `hualiao_danhongsu` text COMMENT '胆红素',
  `hualiao_zhuananmei` text COMMENT '转氨酶',
  `hualiao_jianxinlinsuan` text COMMENT '碱性磷酸酶',
  `hualiao_kouqiang` text COMMENT '口腔',
  `hualiaog_exin` text COMMENT '恶心呕吐',
  `hualiao_fuxie` text COMMENT '腹泻',
  `hualiao_bun` text COMMENT 'BUN',
  `hualiao_jigan` text COMMENT '肌酐',
  `hualiao_danbailiao` text COMMENT '蛋白尿',
  `hualiao_xueniao` text COMMENT '血尿',
  `hualiao_fei` text COMMENT '肺',
  `hualiao_fare` text COMMENT '发热',
  `hualiao_guomin` text COMMENT '过敏',
  `hualiao_pifu` text COMMENT '皮肤',
  `hualiao_toufa` text COMMENT '头发',
  `hualiao_ganran` text COMMENT '感染',
  `hualiao_shenzhi` text COMMENT '神志',
  `hualiao_zhouweishenjing` text COMMENT '周围神经',
  `hualiao_bianmi` text COMMENT '便秘',
  `hualiao_tengtong` text COMMENT '疼痛',
  `hualiao_xinlv` text COMMENT '心率',
  `hualiao_xingongneng` text COMMENT '心功能',
  `hualiao_xinbaoyan` text COMMENT '心包炎',
  `hualiao_liubiao_check_time` text COMMENT '检查时间',
  `hualiao_cea` text COMMENT '化疗_cea',
  `hualiao_cyfra211` text COMMENT '化疗_cyfra211',
  `hualiao_ca199` text COMMENT '化疗_ca199',
  `hualiao_cea1` text COMMENT '化疗_cea1',
  `hualiao_nse` text COMMENT '化疗_nse',
  `hualiao_ca125` text COMMENT '化疗_ca125'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_fufahouzhiliao_baxiang`
--

CREATE TABLE `follow_fufahouzhiliao_baxiang` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text COMMENT '创建时间',
  `edit_time` text COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_fufahouzhiliao_fangliao`
--

CREATE TABLE `follow_fufahouzhiliao_fangliao` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text COMMENT '创建时间',
  `edit_time` text COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_fufahouzhiliao_gamadao`
--

CREATE TABLE `follow_fufahouzhiliao_gamadao` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text COMMENT '创建时间',
  `edit_time` text COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_fufahouzhiliao_hualiao`
--

CREATE TABLE `follow_fufahouzhiliao_hualiao` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text COMMENT '创建时间',
  `edit_time` text COMMENT '修改时间'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_fufazhuanyi`
--

CREATE TABLE `follow_fufazhuanyi` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `fufazhuanyi_check` text COMMENT '复发转移',
  `fufazhuanyi_time` text COMMENT '时间',
  `fufazhuanyi_checkbox` json DEFAULT NULL COMMENT '复发转移_多选框',
  `fufazhuanyi_other` text COMMENT '其他'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `follow_lianxiren`
--

CREATE TABLE `follow_lianxiren` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `lianxiren_id` int(11) NOT NULL COMMENT '联系人ID',
  `lianxiren_name` text COMMENT '联系人姓名',
  `lianxiren_guanxi` text COMMENT '与患者关系',
  `lianxiren_mobile` text COMMENT '移动电话',
  `lianxiren_zaidian` text COMMENT '宅电',
  `im` text COMMENT '即时通讯',
  `email` text COMMENT 'Email'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history_family`
--

CREATE TABLE `history_family` (
  `id` int(11) NOT NULL,
  `patients_id` int(30) NOT NULL COMMENT '患者ID',
  `jiazu` tinyint(1) DEFAULT '0' COMMENT '家族肿瘤史_单选框',
  `head_neck` tinyint(1) DEFAULT '0' COMMENT '头颈部_单选框',
  `esophageal` tinyint(1) DEFAULT '0' COMMENT '食管_单选框',
  `lung` tinyint(1) DEFAULT '0' COMMENT '肺_单选框',
  `mediastinal` tinyint(1) DEFAULT '0' COMMENT '纵隔_单选框',
  `stomach` tinyint(1) DEFAULT '0' COMMENT '胃_单选框',
  `hepatic` tinyint(1) DEFAULT '0' COMMENT '肝脏_单选框',
  `pancreatic` tinyint(1) DEFAULT '0' COMMENT '胰腺_单选框',
  `small_intestina` tinyint(1) DEFAULT '0' COMMENT '小肠_单选框',
  `colonic` tinyint(1) DEFAULT '0' COMMENT '结肠_单选框',
  `transrectal` tinyint(1) DEFAULT '0' COMMENT '直肠_单选框',
  `renal` tinyint(1) DEFAULT '0' COMMENT '肾_单选框',
  `vesical` tinyint(1) DEFAULT '0' COMMENT '膀胱_单选框',
  `prostatic` tinyint(1) DEFAULT '0' COMMENT '前列腺_单选框',
  `reproductive_system` tinyint(1) DEFAULT '0' COMMENT '生殖系统_单选框',
  `lymphatic_system` tinyint(1) DEFAULT '0' COMMENT '淋巴系统_单选框',
  `galactophore` tinyint(1) DEFAULT '0' COMMENT '乳腺_单选框',
  `uterine` tinyint(1) DEFAULT '0' COMMENT '子宫_单选框',
  `melanoma` tinyint(1) DEFAULT '0' COMMENT '黑色素瘤_单选框',
  `sarcoma` tinyint(1) DEFAULT '0' COMMENT '肉瘤_单选框',
  `others` tinyint(1) DEFAULT '0' COMMENT '其他_单选框',
  `iothercheck` varchar(255) DEFAULT NULL COMMENT '其他_输入框',
  `created_time` text,
  `edit_time` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history_menstrual`
--

CREATE TABLE `history_menstrual` (
  `id` int(11) NOT NULL,
  `patients_id` int(30) NOT NULL COMMENT '患者ID',
  `firstage` int(11) DEFAULT NULL COMMENT '初潮年龄',
  `endage` int(11) DEFAULT NULL COMMENT '绝经年龄',
  `regular` int(11) DEFAULT '0' COMMENT '是否规律(1:是0:否)'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history_self`
--

CREATE TABLE `history_self` (
  `id` int(11) NOT NULL,
  `patients_id` int(30) NOT NULL COMMENT '患者ID',
  `smoking` tinyint(1) DEFAULT '0' COMMENT '吸烟_单选框',
  `drinking` tinyint(1) DEFAULT '0' COMMENT '饮酒_单选框',
  `surgical` tinyint(1) DEFAULT '0' COMMENT '手术史_单选框',
  `tumor` tinyint(1) DEFAULT '0' COMMENT '肿瘤史_单选框',
  `carcinogenic_factors` tinyint(1) DEFAULT '0' COMMENT '致癌因素_单选框',
  `ismoking` varchar(255) DEFAULT NULL COMMENT '吸烟_输入框',
  `idrinking` varchar(255) DEFAULT NULL COMMENT '饮酒_输入框',
  `isurgical` varchar(255) DEFAULT NULL COMMENT '手术史_输入框',
  `itumor` varchar(255) DEFAULT NULL COMMENT '肿瘤史_输入框',
  `icarcinogenic_factors` varchar(255) DEFAULT NULL COMMENT '致癌因素_输入框',
  `created_time` text,
  `edit_time` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `history_surgical`
--

CREATE TABLE `history_surgical` (
  `id` int(11) NOT NULL,
  `patients_id` int(30) NOT NULL,
  `circular` tinyint(1) DEFAULT '0' COMMENT '循环系统_单选框',
  `respiratory` tinyint(1) DEFAULT '0' COMMENT '呼吸系统_单选框',
  `digestive` tinyint(1) DEFAULT '0' COMMENT '消化系统_单选框',
  `urologic` tinyint(1) DEFAULT '0' COMMENT '泌尿系统_单选框',
  `endocrinological` tinyint(1) DEFAULT '0' COMMENT '内分泌系统_单选框',
  `immune` tinyint(1) DEFAULT '0' COMMENT '免疫系统_单选框',
  `blood` tinyint(1) DEFAULT '0' COMMENT '血液系统_单选框',
  `others` tinyint(1) DEFAULT '0' COMMENT '其他_单选框',
  `icircular` varchar(255) DEFAULT NULL COMMENT '循环_输入框',
  `irespiratory` varchar(255) DEFAULT NULL COMMENT '呼吸_输入框',
  `idigestive` varchar(255) DEFAULT NULL COMMENT '消化_输入框',
  `iurologic` varchar(255) DEFAULT NULL COMMENT '泌尿_输入框',
  `iendocrinological` varchar(255) DEFAULT NULL COMMENT '内分泌_输入框',
  `iimmune` varchar(255) DEFAULT NULL COMMENT '免疫_输入框',
  `iblood` varchar(255) DEFAULT NULL COMMENT '血液_输入框',
  `iothers` varchar(255) DEFAULT NULL COMMENT '其他_输入框',
  `created_time` text,
  `edit_time` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_baxiang`
--

CREATE TABLE `induction_therapy_baxiang` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `baxiang_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `check_time` text COMMENT '检查时间',
  `baxiang_yaowu` text COMMENT '靶向药物',
  `baxiang_more` text COMMENT '详情'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_fangliao`
--

CREATE TABLE `induction_therapy_fangliao` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `fangliao_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `check_time` text COMMENT '放疗时间',
  `fangliao_plan` text COMMENT '放疗方式',
  `fangliao_more` text COMMENT '详情'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_houct`
--

CREATE TABLE `induction_therapy_houct` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `houct_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `resist` text COMMENT 'resist',
  `n1` text COMMENT '诱导后N变化1',
  `n2` text COMMENT '诱导后N变化2',
  `n3` text COMMENT '诱导后N变化3',
  `n4` text COMMENT '诱导后N变化4',
  `n5` text COMMENT '诱导后N变化5',
  `n6` text COMMENT '诱导后N变化6',
  `n7` text COMMENT '诱导后N变化7',
  `n8` text COMMENT '诱导后N变化8',
  `n9` text COMMENT '诱导后N变化9',
  `n10` text COMMENT '诱导后N变化10',
  `n11` text COMMENT '诱导后N变化11'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_houfenqi`
--

CREATE TABLE `induction_therapy_houfenqi` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `houfenqi_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `check_time` text COMMENT '检查时间',
  `stage` text COMMENT 'Stage变化',
  `houfenqi_t` text COMMENT 't',
  `houfenqi_m` text COMMENT 'm',
  `houfenqi_n` text COMMENT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_houpet`
--

CREATE TABLE `induction_therapy_houpet` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `houpet_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `pet_num` text COMMENT 'PET号',
  `pet_more` text COMMENT '详情'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_hualiao`
--

CREATE TABLE `induction_therapy_hualiao` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `hualiao_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `hualiao_cycle` text COMMENT '化疗周期',
  `hualiao_plan` text COMMENT '化疗方案',
  `hualiao_more` text COMMENT '化疗详情'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `induction_therapy_qianfenqi`
--

CREATE TABLE `induction_therapy_qianfenqi` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `t` text COMMENT 't',
  `m` text COMMENT 'm',
  `n` text COMMENT 'n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patients_id` int(11) NOT NULL COMMENT '患者ID',
  `patients_name` varchar(32) DEFAULT NULL COMMENT '患者姓名',
  `second_hospital_in` tinyint(1) DEFAULT '0' COMMENT '二次入院(0:否1:是)',
  `previous_hospital` varchar(255) DEFAULT NULL COMMENT '上次入院医院',
  `birthday` text COMMENT '生日',
  `national` varchar(20) DEFAULT '汉族' COMMENT '民族',
  `height` text COMMENT '身高',
  `weight` text COMMENT '体重',
  `hospitalized_time` text COMMENT '入院日期',
  `leaving_hospital` text COMMENT '出院日期',
  `hospital_date` text COMMENT '住院天数',
  `hospital_out_to` varchar(50) DEFAULT NULL COMMENT '离院去向',
  `adress1a` varchar(20) DEFAULT NULL COMMENT '地址(省)',
  `adress1b` varchar(20) DEFAULT NULL COMMENT '地址(市)',
  `adress1c` varchar(20) DEFAULT NULL COMMENT '地址(区)',
  `adress2` varchar(255) DEFAULT NULL COMMENT '地址(详细地址)',
  `phone` varchar(30) DEFAULT NULL COMMENT '电话',
  `hospital_id` varchar(30) DEFAULT NULL COMMENT '住院号',
  `id_photo_num` varchar(30) DEFAULT NULL COMMENT '影像号',
  `id_pathological` varchar(30) DEFAULT NULL COMMENT '病理号',
  `identity_card` varchar(30) DEFAULT NULL COMMENT '身份证号',
  `sex` tinyint(1) DEFAULT NULL COMMENT '性别(1:男0:女)',
  `age` text COMMENT '年龄',
  `married` int(11) DEFAULT NULL COMMENT '婚姻(0:未婚1:离异2:已婚)',
  `doctor` varchar(20) DEFAULT NULL COMMENT '主治医生',
  `created_time` text,
  `edit_time` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_complications_erci`
--

CREATE TABLE `postoperative_complications_erci` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `ercishoushu_check` text COMMENT '二次手术',
  `erci_time` text COMMENT '时间',
  `yuanyin_select` text COMMENT '原因',
  `yuanyin_other` text COMMENT '原因-其他'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_complications_icu`
--

CREATE TABLE `postoperative_complications_icu` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `icu_check` text COMMENT 'SICU',
  `in_time` text COMMENT '入住时间',
  `icu_yuanyin_select` text COMMENT '原因',
  `icu_yuanyin_other` text COMMENT '原因-其他',
  `ercichaguan_check` text COMMENT '二次插管',
  `huxiji` text COMMENT '呼吸机支持时间',
  `out_time` text COMMENT '离开时间',
  `zhuangui` text COMMENT '转归'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_complications_shuhou`
--

CREATE TABLE `postoperative_complications_shuhou` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `weishoushuqisiwang` text COMMENT '围手术期死亡',
  `weishoushuqisiwang_select` text COMMENT '围手术期死亡_多选框',
  `xinxueguanyiwai` text COMMENT '心血管意外',
  `xinxueguanyiwai_select` text COMMENT '心血管意外_多选框',
  `xinlvshichang` text COMMENT '心率失常',
  `xinlvshichang_select` text COMMENT '心率失常_多选框',
  `xinlvshichang_other` text COMMENT '心率失常_输入框',
  `feisuansai` text COMMENT '肺栓塞',
  `xinbaoshan` text COMMENT '心包疝',
  `xiongqiangchuxue` text COMMENT '胸腔纵隔活动性出血',
  `feibuzhang` text COMMENT '肺不张',
  `jixingfeishuizhong` text COMMENT '急性肺水肿',
  `huxisuaijie` text COMMENT '呼吸衰竭',
  `feiyeniuzhuan` text COMMENT '肺叶扭转或坏疽',
  `zhiqiguanxiongmo` text COMMENT '支气管胸膜瘘',
  `nongxiong` text COMMENT '脓胸',
  `xiongmocanqiang` text COMMENT '胸膜残腔',
  `feibuganran` text COMMENT '肺部感染',
  `qiekouganran` text COMMENT '切口感染',
  `louqi` text COMMENT '漏气5天',
  `qiguanjingxitan` text COMMENT '气管镜吸痰',
  `pibanhuaisi` text COMMENT '皮瓣坏死',
  `zhiruwuganran` text COMMENT '植入物感染',
  `rumixiong` text COMMENT '乳糜胸',
  `zhongjizhengwuli` text COMMENT '重肌症无力',
  `shuhoubinfazheng_other` text COMMENT '其他'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_complications_shuzhong`
--

CREATE TABLE `postoperative_complications_shuzhong` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `jixinghuxidaogenzu` text COMMENT '急性呼吸道梗阻',
  `diyangxuezheng` text COMMENT '低氧血症',
  `shuzhongdachuxue_check` text COMMENT '术中大出血',
  `shuzhongdachuxue_chuxueliang` text COMMENT '出血量_输入框',
  `shuzhongdachuxue_yuanyin` text COMMENT '原因_输入框',
  `gaotansuanxuezheng` text COMMENT '高碳酸血症',
  `kongqisuansai` text COMMENT '空气栓塞',
  `shuzhongxiuke` text COMMENT '术中休克',
  `xinlvshichang_check` text COMMENT '心率失常',
  `xinlvshichang_select` text COMMENT '心率失常(选择框)',
  `shuzhonggaoxueya_input1` text COMMENT '术中高血压_输入框1',
  `shuzhonggaoxueya_input2` text COMMENT '术中高血压_输入框2',
  `shuzhonggaoxueya_chixushijian` text COMMENT '术高持续时间_输入框',
  `shuzhongdixueya_input1` text COMMENT '术中低血压_输入框1',
  `shuzhongdixueya_input2` text COMMENT '术中低血压_输入框2',
  `shuzhongdixueya_chixushijian` text COMMENT '术低持续时间_输入框',
  `shuzhongsunshang_check` text COMMENT '术中损伤',
  `shuzhongsunshang_select` text COMMENT '术中损伤(选择框)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_pathology_bingli`
--

CREATE TABLE `postoperative_pathology_bingli` (
  `id` int(11) NOT NULL,
  `bingli_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `zhongwu_binglihao` text COMMENT '病理号',
  `zhongwu_stage_t` text COMMENT 'Stage-t',
  `zhongwu_stage_n` text COMMENT 'Stage-m',
  `zhongwu_stage_m` text COMMENT 'Stage-n'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_pathology_jiyin`
--

CREATE TABLE `postoperative_pathology_jiyin` (
  `id` int(11) NOT NULL,
  `jiyin_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `zhongwu_binglihao` text COMMENT '病理号',
  `jiyin_checkbox` json DEFAULT NULL COMMENT '基因检查_多选框'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_pathology_linba`
--

CREATE TABLE `postoperative_pathology_linba` (
  `id` int(11) NOT NULL,
  `linba_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text NOT NULL,
  `edit_time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_pathology_mianyi`
--

CREATE TABLE `postoperative_pathology_mianyi` (
  `id` int(11) NOT NULL,
  `mianyi_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `zhongwu_binglihao` text COMMENT '病理号',
  `mianyizuhua_checkbox` json DEFAULT NULL COMMENT '免疫组化_多选框'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `postoperative_pathology_zhongwu`
--

CREATE TABLE `postoperative_pathology_zhongwu` (
  `id` int(11) NOT NULL,
  `zhongwu_id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `zhongwu_binglihao` text COMMENT '病理号',
  `zhongwu_dati_feiye_1` text COMMENT '肺叶体积大小_输入框1',
  `zhongwu_dati_feiye_2` text COMMENT '肺叶体积大小_输入框2',
  `zhongwu_dati_feiye_3` text COMMENT '肺叶体积大小_输入框3',
  `zhongwu_dati_xiongmo` text COMMENT '胸膜皱缩',
  `zhongwu_weizhi` text COMMENT '肿物位置',
  `zhongwu_daxiao_1` text COMMENT '肿物大小_输入框1',
  `zhongwu_daxiao_2` text COMMENT '肿物大小_输入框2',
  `zhongwu_daxiao_3` text COMMENT '肿物大小_输入框3',
  `zhongwu_jiexian` text COMMENT '界限清楚',
  `zhongwu_yindu` text COMMENT '硬度',
  `zhongwu_qiemian` text COMMENT '切面',
  `zhongwu_zhidi` text COMMENT '质地',
  `zhongwu_liuti` text COMMENT '瘤体内部',
  `zhongwu_leiji` text COMMENT '累及部位',
  `zhongwu_leiji_other` text COMMENT '累及部位_输入框',
  `zhongwu_zhiqiguanjuli` text COMMENT '肿物距支气管端距离',
  `zhongwu_yufeijiejie` text COMMENT '余肺结节',
  `zhongwu_shengzhang` text COMMENT '生长方式',
  `zhongwu_canduanyangxing` text COMMENT '残端阳性',
  `zhongwu_maiguanaisuan` text COMMENT '脉管癌拴',
  `zhongwu_who` text COMMENT 'WHO分类标准',
  `zhongwu_fenhuachengdu` text COMMENT '分化程度',
  `zhongwu_fenhuachengdu_other` text COMMENT '分化程度_输入框',
  `zhongwu_xiongmoqinfan` text COMMENT '胸膜侵犯'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_chaosheng`
--

CREATE TABLE `preoperative_chaosheng` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `chaosheng_id` int(11) NOT NULL,
  `chaoShengFind` text COMMENT '超声检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `jingbulingbajiezhongda` int(1) DEFAULT '0' COMMENT '颈部淋巴结肿大_单选框',
  `huojian1` int(1) DEFAULT '0' COMMENT '活检_单选框',
  `binglihao1` text COMMENT '病理号',
  `who1` text COMMENT 'WHO分类标准',
  `ganzanwei` int(1) DEFAULT '0' COMMENT '肝占位_单选框',
  `huojian2` int(1) DEFAULT '0' COMMENT '活检_单选框',
  `binglihao2` text COMMENT '病理号',
  `who2` text COMMENT 'WHO分类标准',
  `shengshangxianjiejie` int(1) DEFAULT '0' COMMENT '肾上腺结节_单选框',
  `huojian3` int(1) DEFAULT '0' COMMENT '活检_单选框',
  `binglihao3` text COMMENT '病理号',
  `who3` text COMMENT 'WHO分类标准',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_ct`
--

CREATE TABLE `preoperative_ct` (
  `id` int(10) NOT NULL,
  `ct_id` int(10) DEFAULT NULL,
  `patients_id` int(10) NOT NULL,
  `ctFind` text COMMENT 'CT检查发现(正常:异常)',
  `buwei` json DEFAULT NULL COMMENT '部位_多选框',
  `daxiao1` text COMMENT '大小_输入框1',
  `daxiao2` text COMMENT '大小_输入框2',
  `daxiao3` text COMMENT '大小_输入框3',
  `ggo` tinyint(1) DEFAULT '0' COMMENT 'GGO_单选框',
  `gaihua` tinyint(1) DEFAULT '0' COMMENT '钙化_单选框',
  `fenyezheng` tinyint(1) DEFAULT '0' COMMENT '分叶症_单选框',
  `maocizheng` tinyint(1) DEFAULT '0' COMMENT '毛刺症_单选框',
  `xiongmozhousuo` tinyint(1) DEFAULT '0' COMMENT '胸膜皱缩_单选框',
  `jizhuangtuqi` tinyint(1) DEFAULT '0' COMMENT '棘状突起_单选框',
  `kongdong` tinyint(1) DEFAULT '0' COMMENT '空洞_单选框',
  `zhiqiguanzheng` tinyint(1) DEFAULT '0' COMMENT '支气管征_单选框',
  `zushaixingfeiyan` tinyint(1) DEFAULT '0' COMMENT '阻塞性肺炎_单选框',
  `zhongliuqingfanxueguan` tinyint(1) DEFAULT '0' COMMENT '肿瘤侵犯血管_单选框',
  `qingfangeji` tinyint(1) DEFAULT '0' COMMENT '侵犯膈肌_单选框',
  `qingfanzhuiti` tinyint(1) DEFAULT '0' COMMENT '侵犯椎体_单选框',
  `qingfanxiongbi` tinyint(1) DEFAULT '0' COMMENT '侵犯胸壁_单选框',
  `xiongshui` tinyint(1) DEFAULT '0' COMMENT '胸水_单选框',
  `tongyejiejie` tinyint(1) DEFAULT '0' COMMENT '同叶结节_单选框',
  `butongyejiejie` tinyint(1) DEFAULT '0' COMMENT '不同叶结节_单选框',
  `spn` tinyint(1) DEFAULT '0' COMMENT 'SPN_单选框',
  `linbajiezhongda` tinyint(1) DEFAULT '0' COMMENT '淋巴结肿大_单选框',
  `img_address` json DEFAULT NULL,
  `created_time` text,
  `edit_time` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_feigongneng`
--

CREATE TABLE `preoperative_feigongneng` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `feigongneng_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `feiGongNengFind` text COMMENT '检查发现(正常:异常)',
  `pre_fvc` text COMMENT 'fvc预测_输入框',
  `act_fvc` text COMMENT 'fvc实测_输入框',
  `pre_fev1` text COMMENT 'fev1预测_输入框',
  `act_fev1` text COMMENT 'fev1实测_输入框',
  `pre_dlco` text COMMENT 'dlco预测_输入框',
  `act_dlco` text COMMENT 'dlco实测_输入框',
  `pre_vc` text COMMENT 'vc预测_输入框',
  `act_vc` text COMMENT 'vc实测_输入框',
  `pre_po2` text COMMENT '运动前po2_输入框',
  `pre_pco2` text COMMENT '运动前pco2_输入框',
  `after_po2` text COMMENT '运动后po2_输入框',
  `after_pco2` text COMMENT '运动后pco2_输入框',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_ganzhangct`
--

CREATE TABLE `preoperative_ganzhangct` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `ganzhangct_id` int(11) NOT NULL,
  `ganzhangct_find` text COMMENT '检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `zhuanyi` text COMMENT '转移',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_guct`
--

CREATE TABLE `preoperative_guct` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `guct_id` int(11) NOT NULL,
  `guct_find` text COMMENT '检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `zhuanyi` text COMMENT '转移',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_gusaomiao`
--

CREATE TABLE `preoperative_gusaomiao` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `gusaomiao_id` int(11) NOT NULL,
  `gusaomiao_find` text COMMENT '检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `zhuanyi` text COMMENT '转移',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_pet`
--

CREATE TABLE `preoperative_pet` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `pet_find` text COMMENT '检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `benyuan_check` text COMMENT '本院检查',
  `pet_num` text COMMENT 'PET号',
  `check_time` text COMMENT '检查时间',
  `suv` text COMMENT 'SUV',
  `pet_fenqi_t` text COMMENT 'PET分期T',
  `pet_fenqi_m` text COMMENT 'PET分期M',
  `pet_fenqi_n` text COMMENT 'PET分期N',
  `other_find` text COMMENT '意外发现',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_qiguanjing`
--

CREATE TABLE `preoperative_qiguanjing` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `qiguanjing_id` int(11) NOT NULL,
  `qiguanjing_find` text COMMENT '检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `zhuzhiqiguan` json DEFAULT NULL COMMENT '主支气管_多选框',
  `zuofei` json DEFAULT NULL COMMENT '左肺_多选框',
  `youfei` json DEFAULT NULL COMMENT '右肺_多选框',
  `jingxiahuojian` json DEFAULT NULL COMMENT '镜下活检_多选框',
  `jingxiahuojian_other` text COMMENT '镜下活检_输入框',
  `tbna` json DEFAULT NULL COMMENT 'tbna_多选框',
  `tbna_other` text COMMENT 'tbna_输入框',
  `ebus` json DEFAULT NULL COMMENT 'ebus_多选框',
  `ebus_other` text COMMENT 'ebus_输入框',
  `tblb_other` text COMMENT 'tblb_输入框',
  `who` text COMMENT 'who标准',
  `other` text COMMENT '其他_输入框',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_toulu`
--

CREATE TABLE `preoperative_toulu` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `toulu_id` int(11) NOT NULL,
  `toulu_find` text COMMENT '检查发现(正常:异常)',
  `created_time` text,
  `edit_time` text,
  `zhuanyi` text COMMENT '转移',
  `img_address` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_xuechanggui`
--

CREATE TABLE `preoperative_xuechanggui` (
  `id` int(11) NOT NULL,
  `xuechanggui_id` int(11) DEFAULT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `xuechanggui_find` text COMMENT '检查发现(正常:异常)',
  `checkdatexuechanggui` text COMMENT '检查时间',
  `baixibaojishu` text COMMENT '白细胞计数_输入框',
  `zhongxinglixibaojishu` text COMMENT '中性粒细胞计数_输入框',
  `xuehongdanbai` text COMMENT '血红蛋白_输入框',
  `xuexiaoban` text COMMENT '血小板_输入框',
  `xuexing` text COMMENT '血型'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_xueliubiaozhiwu`
--

CREATE TABLE `preoperative_xueliubiaozhiwu` (
  `id` int(11) NOT NULL,
  `xueliubiaozhiwu_id` int(11) DEFAULT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `xueliubiaozhiwu_find` text COMMENT '检查发现(正常:异常)',
  `checkdate` text COMMENT '检查时间',
  `cea` text COMMENT 'CEA_输入框',
  `cyfra211` text COMMENT 'CYFRA211_输入框',
  `ca199` text COMMENT 'CA199_输入框',
  `nse` text COMMENT 'NSE_输入框',
  `scc` text COMMENT 'SCC_输入框',
  `ca125` text COMMENT 'CA125_输入框'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `preoperative_xueshenghua`
--

CREATE TABLE `preoperative_xueshenghua` (
  `id` int(11) NOT NULL,
  `xueshenghua_id` int(11) DEFAULT NULL,
  `patients_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `xueshenghua_find` text COMMENT '检查发现(正常:异常)',
  `checkdate` text COMMENT '检查时间',
  `gubinzhuananmei` text COMMENT '谷丙转氨酶_输入框',
  `gucaozhuananmei` text COMMENT '谷草转氨酶_输入框',
  `zongdanhongsu` text COMMENT '总胆红素_输入框',
  `niaosuan` text COMMENT '尿素氨_输入框',
  `jigan` text COMMENT '肌酐_输入框',
  `xuedanbai` text COMMENT '血蛋白_输入框',
  `zongdanbai` text COMMENT '总蛋白_输入框',
  `jianxinglingsuanmei` text COMMENT '碱性磷酸酶_输入框',
  `xuetang` text COMMENT '血糖'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `referral`
--

CREATE TABLE `referral` (
  `id` int(11) NOT NULL,
  `patients_id` int(50) NOT NULL COMMENT '患者ID',
  `fuzhen_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `referral_time` text COMMENT '复诊时间',
  `referral_doctor` text COMMENT '复诊医生',
  `referral_zhengzhuang` text COMMENT '症状',
  `referral_tizheng` text COMMENT '体征',
  `xueliubiao_cea` text COMMENT '血瘤标CEA',
  `xueliubiao_cyfra211` text COMMENT '血瘤标CYFRA211',
  `xueliubiao_ca199` text COMMENT '血瘤标CA199',
  `xueliubiao_nse` text COMMENT '血瘤标NSE',
  `xueliubiao_scc` text COMMENT '血瘤标SCC',
  `xueliubiao_ca125` text COMMENT '血瘤标CA125',
  `chaosheng_zhuanyi` json DEFAULT NULL COMMENT '超声_多选框',
  `chaosheng_zhuanyi_other` text COMMENT '超声_输入框',
  `ct_zhuanyi` json DEFAULT NULL COMMENT 'ct_多选框',
  `ct_zhuanyi_other` text COMMENT 'ct_输入框',
  `ect_zhuanyi` json DEFAULT NULL COMMENT 'ECT_多选框',
  `ect_zhuanyi_other` text COMMENT 'ECT_输入框',
  `toulu_zhuanyi` json DEFAULT NULL COMMENT '头颅MRI_多选框',
  `toulu_zhuanyi_other` text COMMENT '头颅MR_输入框',
  `pet_zhuanyi` json DEFAULT NULL COMMENT 'pet_多选框',
  `pet_zhuanyi_other` text COMMENT 'pet_输入框',
  `referral_other` text COMMENT '其他_输入框',
  `img_address_chaoshen` json DEFAULT NULL,
  `img_address_ct` json DEFAULT NULL,
  `img_address_ect` json DEFAULT NULL,
  `img_address_toulu` json DEFAULT NULL,
  `img_address_pet` json DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surgical_info_shoushu`
--

CREATE TABLE `surgical_info_shoushu` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `shoushu_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `shoushu_date` text COMMENT '手术日期',
  `zhudao` text COMMENT '主刀医师',
  `mazui` text COMMENT '麻醉医师',
  `zhushou1` text COMMENT '第一助手',
  `zhushou2` text COMMENT '第二助手',
  `shuqianbingli` text COMMENT '术前病理获取方式',
  `shoushumudi` text COMMENT '手术目的',
  `huojianfangshi` text COMMENT '活检方式',
  `huojianzuzhi` json DEFAULT NULL COMMENT '活检组织_多选框',
  `huojianzuzhi_other` text COMMENT '活检组织-其他',
  `tanchaguxi` json DEFAULT NULL COMMENT '探查姑息缘由_多选框',
  `tanchaguxi_other` text COMMENT '探查姑息缘由-其他',
  `shuzhongqiguanjing` varchar(10) DEFAULT '否' COMMENT '术中气管镜',
  `qiguanchaguan` text COMMENT '气管插管',
  `shoushushichang` text COMMENT '手术时长',
  `shuzhongchuxue` text COMMENT '术中出血量',
  `shuzhong_shuye` text COMMENT '输液量',
  `shuzhong_jingti` text COMMENT '晶体量',
  `shuzhong_jiaoti` text COMMENT '胶体量',
  `shuzhong_xuejiang` text COMMENT '血浆',
  `shuzhong_hongxibao` text COMMENT '红细胞',
  `shuzhong_liaoliang` text COMMENT '尿量',
  `tiwei` text COMMENT '体位',
  `qiekoubuwei` text COMMENT '切口部位',
  `qiekoufangshi` json DEFAULT NULL COMMENT '切口方式_多选框',
  `zanlian` text COMMENT '粘连',
  `xiongshui` text COMMENT '胸水',
  `yeliefayu` text COMMENT '叶裂发育',
  `shuzhongfeiweisuo` text COMMENT '术中肺萎缩',
  `linbajiezhuanyi` text COMMENT '淋巴结转移',
  `shoushufangshi` json DEFAULT NULL COMMENT '手术方式_多选框',
  `shoushufangshi_other` text COMMENT '手术方式-其他',
  `shuzhongqiechubingdong` varchar(10) DEFAULT '否' COMMENT '术中楔形切除冰冻病理',
  `who` text COMMENT 'who',
  `shoushugengzhi` text COMMENT '手术根治程度',
  `qiguancanduanbihe` text COMMENT '气管残端闭合方式',
  `qiguancanduanchengxing` text COMMENT '气管残端成形',
  `xueguanchengxing` text COMMENT '血管成形',
  `xiongqiangchongxi` text COMMENT '胸腔冲洗',
  `xiongguanshuliang` text COMMENT '胸管数量',
  `shuzhongteshuchuli` text COMMENT '术中特殊处理',
  `img_address` json DEFAULT NULL,
  `qingshao1` text,
  `qingshao2` text,
  `qingshao3` text,
  `qingshao4` text,
  `qingshao5` text,
  `qingshao6` text,
  `qingshao7` text,
  `qingshao8` text,
  `qingshao9` text,
  `qingshao10` text,
  `qingshao11` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `surgical_info_zhongwu`
--

CREATE TABLE `surgical_info_zhongwu` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `zhongwu_id` int(11) NOT NULL,
  `created_time` text,
  `edit_time` text,
  `zhongwu_name` text COMMENT '肿物名',
  `zhongwu_buwei` text COMMENT '肿瘤部位',
  `zhongwu_daxiao_1` text COMMENT '肿瘤大小_输入框1',
  `zhongwu_daxiao_2` text COMMENT '肿瘤大小_输入框2',
  `zhongwu_daxiao_3` text COMMENT '肿瘤大小_输入框3',
  `zhongwu_kuaye` text COMMENT '跨叶生长_文本',
  `zhongwu_zhousuo` text COMMENT '胸膜皱缩_文本',
  `zhongwu_bichengxiongmo` text COMMENT '壁层胸膜受累_文本',
  `zhongwu_xiongbi` text COMMENT '胸壁受累_文本',
  `zhongwu_qiangjingmai` text COMMENT '腔静脉受累_文本',
  `zhongwu_feimenxueguan` text COMMENT '肺门血管受累_文本',
  `zhongwu_xinbao` text COMMENT '心包受累_文本',
  `zhongwu_geshenjing` text COMMENT '膈神经受累_文本',
  `zhongwu_geji` text COMMENT '膈肌受累_文本'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `tizheng`
--

CREATE TABLE `tizheng` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `ecog` int(1) DEFAULT '0' COMMENT 'OCOG评分_数字',
  `suogushanglingbajie` tinyint(1) DEFAULT '0' COMMENT '锁骨上淋巴结肿大_单选框',
  `jingmaiquzhang` tinyint(1) DEFAULT '0' COMMENT '静脉曲张_单选框',
  `xiongbijixing` tinyint(1) DEFAULT '0' COMMENT '胸壁畸形_单选框',
  `xiongkuochukou` tinyint(1) DEFAULT '0' COMMENT '胸廓出口综合症_单选框',
  `ziqian` tinyint(1) DEFAULT '0' COMMENT '紫绀_单选框',
  `chuzhuangzhi` tinyint(1) DEFAULT '0' COMMENT '杵状指_单选框',
  `shuizhong` tinyint(1) DEFAULT '0' COMMENT '水肿_单选框',
  `leiluo` tinyint(1) DEFAULT '0' COMMENT '雷诺现象_单选框',
  `xiongmo` tinyint(1) DEFAULT '0' COMMENT '胸膜摩擦音_单选框',
  `zhiduanfeida` tinyint(1) DEFAULT '0' COMMENT '肢端肥大症_单选框',
  `tizhongxiajiang` tinyint(1) DEFAULT '0' COMMENT '体重下降_单选框',
  `other` text COMMENT '其他_输入框',
  `created_time` text,
  `edit_time` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `name` varchar(100) DEFAULT NULL COMMENT '用户名',
  `user_age` int(3) DEFAULT NULL COMMENT '年龄',
  `user_sex` tinyint(1) DEFAULT NULL COMMENT '性别(1:男0:女)',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否管理员(1:是0:否)',
  `password` varchar(32) DEFAULT NULL COMMENT '密码',
  `neckname` varchar(50) DEFAULT '请输入医生姓名' COMMENT '医生姓名',
  `user_class` varchar(100) DEFAULT NULL COMMENT '所属科室',
  `user_level` varchar(100) DEFAULT NULL COMMENT '级别职称',
  `face_img` varchar(255) DEFAULT NULL COMMENT '头像地址',
  `active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用(1:是0:否)',
  `user_reg_date` date DEFAULT NULL COMMENT '注册时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userid`, `name`, `user_age`, `user_sex`, `level`, `password`, `neckname`, `user_class`, `user_level`, `face_img`, `active`, `user_reg_date`) VALUES
(9, 'admin', 38, 1, 1, 'fuyouwei', '张宏', '胸外科', '教授', '../static/upload/userhead/20210624/a7858a223f48b3cb1ee3652220c95be4.png', 1, NULL),
(20, 'xiaoming', 0, 1, 1, 'xiaoming', '笨笨', '麻醉科', '副教授', '../static/upload/userhead/20200103/487e37dc3fbc3a78b350dfb894ade077.jpeg', 1, '2019-12-25'),
(21, 'huang', 15, 0, 0, 'huang', '小米', '胸外科', '无', '../static/upload/userhead/20210622/882b5e4c41bfb508af0d4215da38d9de.png', 1, '2019-12-25'),
(23, 'aa', 0, 0, 0, 'aa', '华为', '胸外科', '副主任医师', '../static/upload/userhead/20210624/db4d66840440a7f72f10e97879098276.png', 0, '2019-12-25'),
(19, 'benben', 7, 0, 0, 'benben', '啊笨', '胸外科', '副教授', '../static/upload/userhead/20191225/173e159df07e08a4e27d0e6068ae282b.jpeg', 1, '2019-12-25'),
(24, 'bb', 0, 1, 0, 'bb', '苹果', '麻醉科', '副教授', '', 1, '2019-12-25'),
(34, 'doctor', 0, 1, 0, 'doctor', '刘医生', '呼吸科', '无', '', 1, '2019-12-27'),
(33, 'cc', 0, 1, 0, 'aa', 'cc', '麻醉科', '无', '', 1, '2019-12-27'),
(35, 'llll', 34, 1, 0, 'llll', 'llll', '麻醉科', '教授', '', 1, '2020-01-08');

-- --------------------------------------------------------

--
-- Table structure for table `user_login`
--

CREATE TABLE `user_login` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL COMMENT '用户ID',
  `login_ip` text NOT NULL COMMENT '登录IP',
  `datetime` text NOT NULL COMMENT '登录时间'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `zhengzhuang`
--

CREATE TABLE `zhengzhuang` (
  `id` int(11) NOT NULL,
  `patients_id` int(11) NOT NULL,
  `tijianfaxian` text COMMENT '体检发现(是:否)',
  `check_xiongneifeibiaoxian` json DEFAULT NULL COMMENT '胸内肺表现_多选框',
  `check_xiongneifeiwaibiaoxian` json DEFAULT NULL COMMENT '胸内肺外表现_多选框',
  `check_xiongwaifeizhuanyibiaoxian` json DEFAULT NULL COMMENT '胸外非转移表现_多选框',
  `check_xiongwaizhuanyibiaoxian` json DEFAULT NULL COMMENT '胸外转移表现_多选框',
  `other_xiongneifeibiaoxian` text COMMENT '胸内肺表现_输入框',
  `other_xiongneifeiwaibiaoxian` text COMMENT '胸内肺外表现_输入框',
  `other_xiongwaifeizhuanyibiaoxian` text COMMENT '胸外非转移表现_输入框',
  `other_xiongwaizhuanyibiaoxian` text COMMENT '胸外转移表现_输入框',
  `created_time` text,
  `edit_time` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `complementary_treatment_baxiang`
--
ALTER TABLE `complementary_treatment_baxiang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complementary_treatment_fangliao`
--
ALTER TABLE `complementary_treatment_fangliao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complementary_treatment_gamadao`
--
ALTER TABLE `complementary_treatment_gamadao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complementary_treatment_hualiao`
--
ALTER TABLE `complementary_treatment_hualiao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follow_fufahouzhiliao_baxiang`
--
ALTER TABLE `follow_fufahouzhiliao_baxiang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follow_fufahouzhiliao_fangliao`
--
ALTER TABLE `follow_fufahouzhiliao_fangliao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follow_fufahouzhiliao_gamadao`
--
ALTER TABLE `follow_fufahouzhiliao_gamadao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follow_fufahouzhiliao_hualiao`
--
ALTER TABLE `follow_fufahouzhiliao_hualiao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follow_fufazhuanyi`
--
ALTER TABLE `follow_fufazhuanyi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `follow_lianxiren`
--
ALTER TABLE `follow_lianxiren`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_family`
--
ALTER TABLE `history_family`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_menstrual`
--
ALTER TABLE `history_menstrual`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_self`
--
ALTER TABLE `history_self`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `history_surgical`
--
ALTER TABLE `history_surgical`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_baxiang`
--
ALTER TABLE `induction_therapy_baxiang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_fangliao`
--
ALTER TABLE `induction_therapy_fangliao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_houct`
--
ALTER TABLE `induction_therapy_houct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_houfenqi`
--
ALTER TABLE `induction_therapy_houfenqi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_houpet`
--
ALTER TABLE `induction_therapy_houpet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_hualiao`
--
ALTER TABLE `induction_therapy_hualiao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `induction_therapy_qianfenqi`
--
ALTER TABLE `induction_therapy_qianfenqi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patients_id`);

--
-- Indexes for table `postoperative_complications_erci`
--
ALTER TABLE `postoperative_complications_erci`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_complications_icu`
--
ALTER TABLE `postoperative_complications_icu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_complications_shuhou`
--
ALTER TABLE `postoperative_complications_shuhou`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_complications_shuzhong`
--
ALTER TABLE `postoperative_complications_shuzhong`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_pathology_bingli`
--
ALTER TABLE `postoperative_pathology_bingli`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_pathology_jiyin`
--
ALTER TABLE `postoperative_pathology_jiyin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_pathology_linba`
--
ALTER TABLE `postoperative_pathology_linba`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_pathology_mianyi`
--
ALTER TABLE `postoperative_pathology_mianyi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `postoperative_pathology_zhongwu`
--
ALTER TABLE `postoperative_pathology_zhongwu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_chaosheng`
--
ALTER TABLE `preoperative_chaosheng`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_ct`
--
ALTER TABLE `preoperative_ct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_feigongneng`
--
ALTER TABLE `preoperative_feigongneng`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_ganzhangct`
--
ALTER TABLE `preoperative_ganzhangct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_guct`
--
ALTER TABLE `preoperative_guct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_gusaomiao`
--
ALTER TABLE `preoperative_gusaomiao`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_pet`
--
ALTER TABLE `preoperative_pet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_qiguanjing`
--
ALTER TABLE `preoperative_qiguanjing`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_toulu`
--
ALTER TABLE `preoperative_toulu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_xuechanggui`
--
ALTER TABLE `preoperative_xuechanggui`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_xueliubiaozhiwu`
--
ALTER TABLE `preoperative_xueliubiaozhiwu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `preoperative_xueshenghua`
--
ALTER TABLE `preoperative_xueshenghua`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral`
--
ALTER TABLE `referral`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surgical_info_shoushu`
--
ALTER TABLE `surgical_info_shoushu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `surgical_info_zhongwu`
--
ALTER TABLE `surgical_info_zhongwu`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tizheng`
--
ALTER TABLE `tizheng`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `user_login`
--
ALTER TABLE `user_login`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zhengzhuang`
--
ALTER TABLE `zhengzhuang`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `complementary_treatment_baxiang`
--
ALTER TABLE `complementary_treatment_baxiang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complementary_treatment_fangliao`
--
ALTER TABLE `complementary_treatment_fangliao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complementary_treatment_gamadao`
--
ALTER TABLE `complementary_treatment_gamadao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `complementary_treatment_hualiao`
--
ALTER TABLE `complementary_treatment_hualiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_fufahouzhiliao_baxiang`
--
ALTER TABLE `follow_fufahouzhiliao_baxiang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_fufahouzhiliao_fangliao`
--
ALTER TABLE `follow_fufahouzhiliao_fangliao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_fufahouzhiliao_gamadao`
--
ALTER TABLE `follow_fufahouzhiliao_gamadao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_fufahouzhiliao_hualiao`
--
ALTER TABLE `follow_fufahouzhiliao_hualiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_fufazhuanyi`
--
ALTER TABLE `follow_fufazhuanyi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `follow_lianxiren`
--
ALTER TABLE `follow_lianxiren`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_family`
--
ALTER TABLE `history_family`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_menstrual`
--
ALTER TABLE `history_menstrual`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_self`
--
ALTER TABLE `history_self`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `history_surgical`
--
ALTER TABLE `history_surgical`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_baxiang`
--
ALTER TABLE `induction_therapy_baxiang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_fangliao`
--
ALTER TABLE `induction_therapy_fangliao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_houct`
--
ALTER TABLE `induction_therapy_houct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_houfenqi`
--
ALTER TABLE `induction_therapy_houfenqi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_houpet`
--
ALTER TABLE `induction_therapy_houpet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_hualiao`
--
ALTER TABLE `induction_therapy_hualiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `induction_therapy_qianfenqi`
--
ALTER TABLE `induction_therapy_qianfenqi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patients_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '患者ID';

--
-- AUTO_INCREMENT for table `postoperative_complications_erci`
--
ALTER TABLE `postoperative_complications_erci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_complications_icu`
--
ALTER TABLE `postoperative_complications_icu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_complications_shuhou`
--
ALTER TABLE `postoperative_complications_shuhou`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_complications_shuzhong`
--
ALTER TABLE `postoperative_complications_shuzhong`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_pathology_bingli`
--
ALTER TABLE `postoperative_pathology_bingli`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_pathology_jiyin`
--
ALTER TABLE `postoperative_pathology_jiyin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_pathology_linba`
--
ALTER TABLE `postoperative_pathology_linba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_pathology_mianyi`
--
ALTER TABLE `postoperative_pathology_mianyi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `postoperative_pathology_zhongwu`
--
ALTER TABLE `postoperative_pathology_zhongwu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_chaosheng`
--
ALTER TABLE `preoperative_chaosheng`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_ct`
--
ALTER TABLE `preoperative_ct`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_feigongneng`
--
ALTER TABLE `preoperative_feigongneng`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_ganzhangct`
--
ALTER TABLE `preoperative_ganzhangct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_guct`
--
ALTER TABLE `preoperative_guct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_gusaomiao`
--
ALTER TABLE `preoperative_gusaomiao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_pet`
--
ALTER TABLE `preoperative_pet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_qiguanjing`
--
ALTER TABLE `preoperative_qiguanjing`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_toulu`
--
ALTER TABLE `preoperative_toulu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_xuechanggui`
--
ALTER TABLE `preoperative_xuechanggui`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_xueliubiaozhiwu`
--
ALTER TABLE `preoperative_xueliubiaozhiwu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preoperative_xueshenghua`
--
ALTER TABLE `preoperative_xueshenghua`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral`
--
ALTER TABLE `referral`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surgical_info_shoushu`
--
ALTER TABLE `surgical_info_shoushu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `surgical_info_zhongwu`
--
ALTER TABLE `surgical_info_zhongwu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tizheng`
--
ALTER TABLE `tizheng`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID', AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `user_login`
--
ALTER TABLE `user_login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `zhengzhuang`
--
ALTER TABLE `zhengzhuang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
