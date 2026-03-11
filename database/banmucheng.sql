/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 80012 (8.0.12)
 Source Host           : localhost:3306
 Source Schema         : banmucheng

 Target Server Type    : MySQL
 Target Server Version : 80012 (8.0.12)
 File Encoding         : 65001

 Date: 09/03/2026 21:05:35
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码（bcrypt哈希）',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uniq_username`(`username` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES (1, 'admin', '$2y$10$d2RXxS5Gt1l8zikEQ/u4GO43FhrE0RRnQNDh7YhGsVoQNpr25XuKW', '2026-03-04 14:40:57', '2026-03-04 14:53:19');

-- ----------------------------
-- Table structure for article_categories
-- ----------------------------
DROP TABLE IF EXISTS `article_categories`;
CREATE TABLE `article_categories`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '父分类ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分类名称',
  `slug` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '别名',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `article_categories_slug_unique`(`slug`) USING BTREE,
  INDEX `article_categories_parent_id_index`(`parent_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article_categories
-- ----------------------------
INSERT INTO `article_categories` VALUES (1, NULL, 'banner', 'banner', 0, 1, '2026-03-04 23:59:54', '2026-03-05 19:45:47');
INSERT INTO `article_categories` VALUES (2, NULL, '生产实力', 'cat-1772675346', 1, 1, '2026-03-05 17:49:06', '2026-03-05 19:45:47');
INSERT INTO `article_categories` VALUES (3, NULL, '一线大牌合作', 'cat-1772696577', 0, 1, '2026-03-05 23:42:57', '2026-03-05 23:42:57');
INSERT INTO `article_categories` VALUES (4, NULL, '我们的证书', 'cat-1772696889', 0, 1, '2026-03-05 23:48:09', '2026-03-05 23:48:09');
INSERT INTO `article_categories` VALUES (5, NULL, '快问快答', 'cat-1772696988', 0, 1, '2026-03-05 23:49:48', '2026-03-05 23:49:48');

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '分类ID',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '文章标题',
  `title_en` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '英文标题',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '文章描述',
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '英文描述',
  `cover_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '封面图片',
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '富文本内容',
  `content_en` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '英文富文本内容',
  `published_at` timestamp NULL DEFAULT NULL COMMENT '发布时间',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_frontend_visible` tinyint(1) NOT NULL DEFAULT 1 COMMENT '前端是否可见',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `articles_category_id_index`(`category_id`) USING BTREE,
  INDEX `articles_published_at_index`(`published_at`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES (1, 1, '板木诚，为空间注入生活质感', 'english', '专业三聚氰胺贴面板的制造商，传承工厂匠心，结合现代设计理念，满足全屋定制，家具厂商，贸易商，终端消费者的多元需求。丰富花色，稳定品质，灵活定制，满足每位消费者的需求', '', 'products/1772615839_69a7f89f91068.png', '<p><br></p>', '<p><br></p>', '2026-03-04 17:16:00', 1, 1, 0, '2026-03-05 01:23:21', '2026-03-06 00:15:50');
INSERT INTO `articles` VALUES (2, 2, '板木诚总部生产车间实拍', NULL, '', NULL, 'products/1772678566_69a8eda609ef4.png', '<p><br></p>', NULL, '2026-03-05 18:39:00', 1, 1, 0, '2026-03-05 18:42:51', '2026-03-05 21:32:57');
INSERT INTO `articles` VALUES (3, 2, '广西基材生产车间实拍', NULL, '', NULL, 'products/1772678611_69a8edd305b6f.png', '<p><br></p>', NULL, '2026-03-05 18:43:00', 1, 1, 1, '2026-03-05 18:43:32', '2026-03-05 21:32:57');
INSERT INTO `articles` VALUES (4, 2, '江西基材生产车间实拍', NULL, '', NULL, 'products/1772694544_69a92c10d6239.png', '<p><br></p>', NULL, '2026-03-05 23:08:00', 1, 1, 0, '2026-03-05 23:40:07', '2026-03-05 23:40:07');
INSERT INTO `articles` VALUES (5, 2, '浸胶纸厂生产车间实拍', NULL, '', NULL, 'products/1772696427_69a9336b27857.png', '<p><br></p>', NULL, '2026-03-05 23:40:00', 1, 1, 0, '2026-03-05 23:40:30', '2026-03-05 23:40:30');
INSERT INTO `articles` VALUES (6, 3, '欧派', NULL, '', NULL, 'products/1772696616_69a93428a65f7.png', '<p><br></p>', NULL, '2026-03-05 23:43:00', 1, 1, 0, '2026-03-05 23:43:40', '2026-03-05 23:43:40');
INSERT INTO `articles` VALUES (7, 3, '尚品宅配', NULL, '', NULL, 'products/1772696651_69a9344bcd21b.png', '<p><br></p>', NULL, '2026-03-05 23:43:00', 1, 1, 0, '2026-03-05 23:44:14', '2026-03-05 23:44:14');
INSERT INTO `articles` VALUES (8, 3, '富兰克卫浴', NULL, '', NULL, 'products/1772696683_69a9346b1bfd6.png', '<p><br></p>', NULL, '2026-03-05 23:44:00', 1, 1, 0, '2026-03-05 23:44:49', '2026-03-05 23:44:49');
INSERT INTO `articles` VALUES (9, 3, '索菲亚', NULL, '', NULL, 'products/1772696711_69a93487c4f72.png', '<p><br></p>', NULL, '2026-03-05 23:44:00', 1, 1, 0, '2026-03-05 23:45:13', '2026-03-05 23:45:13');
INSERT INTO `articles` VALUES (10, 3, '箭牌卫浴', NULL, '', NULL, 'products/1772696760_69a934b810043.png', '<p><br></p>', NULL, '2026-03-05 23:45:00', 1, 1, 0, '2026-03-05 23:46:02', '2026-03-05 23:46:02');
INSERT INTO `articles` VALUES (11, 4, '一级证书', NULL, '', NULL, 'products/1772696948_69a93574e6b1d.jfif', '<p><br></p>', NULL, '2026-03-05 23:48:00', 1, 1, 0, '2026-03-05 23:49:11', '2026-03-05 23:49:11');
INSERT INTO `articles` VALUES (12, 4, '二级证书', NULL, '', NULL, 'products/1772696968_69a9358840bee.jfif', '<p><br></p>', NULL, '2026-03-05 23:49:00', 1, 1, 0, '2026-03-05 23:49:29', '2026-03-05 23:49:29');
INSERT INTO `articles` VALUES (13, 5, '你们的主营业务是？', NULL, '我们是三聚氰胺免漆饰面板生产商，主营饰面板的相关业务。压贴类型涵盖直贴，复贴，基材涵盖，刨花，中纤，多层，橡胶木，欧松，碳酸钙板等！上千种花色，上百种钢板任您选择！', NULL, NULL, '<p><br></p>', NULL, '2026-03-05 23:49:00', 1, 1, 0, '2026-03-05 23:50:04', '2026-03-05 23:52:20');
INSERT INTO `articles` VALUES (14, 5, '你们板材尺寸一般是？', NULL, '涵盖48尺，49尺，1厘，5厘，8厘，9厘，12厘，15厘，18厘，25厘等规格的板材。', NULL, NULL, '<p><br></p>', NULL, '2026-03-05 23:50:00', 1, 1, 1, '2026-03-05 23:50:22', '2026-03-05 23:52:20');
INSERT INTO `articles` VALUES (15, 5, '有没有起订量？', NULL, '一张起订！', NULL, NULL, '<p><br></p>', NULL, '2026-03-05 23:50:00', 1, 1, 2, '2026-03-05 23:50:36', '2026-03-05 23:52:17');
INSERT INTO `articles` VALUES (16, 5, '有售后问题怎么办？', NULL, '第一时间联系我们，我们会有专门的售后团队跟进！', NULL, NULL, '<p><br></p>', NULL, '2026-03-05 23:50:00', 1, 1, 3, '2026-03-05 23:50:50', '2026-03-05 23:52:14');
INSERT INTO `articles` VALUES (17, 5, '怎么联系你们？', NULL, '我们的电话：13535717734\n我们的地址：广东省佛山市南海区丹灶镇明沙南路22号\n我们的邮箱：995754135@qq.com\n我们的视频号：板木诚小夏', NULL, NULL, '<p><br></p>', NULL, '2026-03-05 23:51:00', 1, 1, 4, '2026-03-05 23:51:55', '2026-03-05 23:52:09');
INSERT INTO `articles` VALUES (18, 5, '你们的交付周期一般是?', NULL, '一般是7-15天，如果有库存则更快，如果没有原纸或者基材则时间会长一些。', NULL, NULL, '<p><br></p>', NULL, '2026-03-05 23:52:00', 1, 1, 0, '2026-03-05 23:52:46', '2026-03-05 23:52:46');
INSERT INTO `articles` VALUES (19, 4, '三级证书', '', '', '', 'products/1772700922_69a944fa3d221.jfif', '', '<p><br></p>', '2026-03-06 00:55:00', 1, 1, 0, '2026-03-06 00:55:24', '2026-03-06 00:55:24');
INSERT INTO `articles` VALUES (20, 4, '五级证书', '', '', '', 'products/1772700946_69a9451218149.jfif', '', '<p><br></p>', '2026-03-06 00:55:00', 1, 1, 0, '2026-03-06 00:55:48', '2026-03-06 00:55:48');

-- ----------------------------
-- Table structure for categories
-- ----------------------------
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_zh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '中文名称',
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '英文名称',
  `parent_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '父分类ID（NULL=顶级）',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT '排序（越小越靠前）',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_parent`(`parent_id` ASC) USING BTREE,
  CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of categories
-- ----------------------------
INSERT INTO `categories` VALUES (1, '素色系列', 'Laminated Panel', NULL, 1, '2026-03-04 14:40:57', '2026-03-06 01:04:44');
INSERT INTO `categories` VALUES (6, '木纹系列', 'muwen', NULL, 0, '2026-03-06 01:04:29', '2026-03-06 01:04:29');
INSERT INTO `categories` VALUES (7, '钢板', 'gangban', NULL, 0, '2026-03-06 01:05:09', '2026-03-06 01:05:09');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2024_01_01_000006_create_articles_tables', 1);
INSERT INTO `migrations` VALUES (2, '2024_01_01_000007_add_parent_id_to_article_categories', 2);
INSERT INTO `migrations` VALUES (3, '2024_01_01_000008_add_en_fields_to_articles', 3);
INSERT INTO `migrations` VALUES (4, '2024_01_01_000009_add_frontend_visible_to_articles', 4);
INSERT INTO `migrations` VALUES (5, '2024_01_01_000009_create_settings_table', 5);
INSERT INTO `migrations` VALUES (6, '2024_01_01_000010_add_en_settings', 6);
INSERT INTO `migrations` VALUES (7, '2024_01_01_000011_add_contact_settings', 7);

-- ----------------------------
-- Table structure for product_images
-- ----------------------------
DROP TABLE IF EXISTS `product_images`;
CREATE TABLE `product_images`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) UNSIGNED NOT NULL COMMENT '所属产品ID',
  `image_path` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '图片路径（相对 uploads/）',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT '图片排序',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_product_sort`(`product_id` ASC, `sort_order` ASC) USING BTREE,
  CONSTRAINT `fk_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_images
-- ----------------------------
INSERT INTO `product_images` VALUES (1, 1, 'products/1772702319_69a94a6f6fd5e.jpg', 0, '2026-03-06 01:18:39', '2026-03-06 01:18:39');
INSERT INTO `product_images` VALUES (2, 3, 'products/1772702953_69a94ce9889ae.jpg', 0, '2026-03-06 01:29:13', '2026-03-06 01:29:13');

-- ----------------------------
-- Table structure for product_options
-- ----------------------------
DROP TABLE IF EXISTS `product_options`;
CREATE TABLE `product_options`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'substrate | spec | thickness',
  `value_zh` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '值（中文）',
  `value_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '值（英文）',
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `product_options_type_index`(`type`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 29 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of product_options
-- ----------------------------
INSERT INTO `product_options` VALUES (4, 'substrate', '中纤板', 'MDF', 40, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (5, 'spec', '1.22×2.44m', '1.22×2.44m', 10, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (6, 'spec', '1.22×2.745m', '1.22×2.745m', 20, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (7, 'thickness', '1mm', '1mm', 10, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (8, 'thickness', '5mm', '5mm', 20, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (9, 'thickness', '8mm', '8mm', 30, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (10, 'thickness', '9mm', '9mm', 40, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (11, 'thickness', '12mm', '12mm', 50, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (12, 'thickness', '15mm', '15mm', 60, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (13, 'thickness', '18mm', '18mm', 70, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (14, 'thickness', '25mm', '25mm', 80, 1, '2026-03-04 23:33:09', '2026-03-04 23:33:09');
INSERT INTO `product_options` VALUES (17, 'substrate', '欧松板', 'OSB', 30, 1, NULL, NULL);
INSERT INTO `product_options` VALUES (19, 'spec', '1.22×2.44m', '1.22×2.44m', 10, 1, NULL, NULL);
INSERT INTO `product_options` VALUES (20, 'spec', '1.22×2.745m', '1.22×2.745m', 20, 1, NULL, NULL);

-- ----------------------------
-- Table structure for products
-- ----------------------------
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products`  (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) UNSIGNED NULL DEFAULT NULL COMMENT '分类ID',
  `name_zh` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品中文名称',
  `name_en` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '产品英文名称',
  `description_zh` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '产品中文描述',
  `description_en` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '产品英文描述',
  `substrates` json NULL COMMENT '可选基材，如 [\"包烧板\",\"夹板\"]',
  `specs` json NULL COMMENT '可选规格，如 [\"1.22×2.44m\"]',
  `thicknesses` json NULL COMMENT '可选厚度，如 [\"5mm\",\"8mm\"]',
  `cover_image` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '封面图片路径（相对 uploads/）',
  `status` tinyint(4) NOT NULL DEFAULT 1 COMMENT '状态：1=上架 0=下架',
  `sort_order` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_status_sort`(`status` ASC, `sort_order` ASC) USING BTREE,
  INDEX `idx_category`(`category_id` ASC) USING BTREE,
  CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of products
-- ----------------------------
INSERT INTO `products` VALUES (1, 6, '白樱桃(直）', 'baiyingtao', '', '', '[]', '[]', '[]', 'products/1772702319_69a94a6f6fd5e.jpg', 1, 0, '2026-03-06 01:18:39', '2026-03-06 01:18:47');
INSERT INTO `products` VALUES (2, 6, '沙丘柚木(山)', 'shaqiu', '', '', '[]', '[]', '[]', NULL, 1, 0, '2026-03-06 01:28:36', '2026-03-06 01:28:36');
INSERT INTO `products` VALUES (3, 6, '专业20年，三聚氰胺面漆饰面板生产商，结合现代设计理念，传承工厂匠心，满足全屋定制，家具厂商与贸易商的多元需求。丰富花色，稳定品质，灵活定制', 'kakaka', '', '', '[\"包烧板\"]', '[\"1.22×2.44m\"]', '[\"1mm\"]', 'products/1772702953_69a94ce9889ae.jpg', 1, 0, '2026-03-06 01:29:13', '2026-03-06 02:14:20');

-- ----------------------------
-- Table structure for settings
-- ----------------------------
DROP TABLE IF EXISTS `settings`;
CREATE TABLE `settings`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置键',
  `value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '配置值',
  `label` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL COMMENT '配置名称',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `settings_key_unique`(`key`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of settings
-- ----------------------------
INSERT INTO `settings` VALUES (1, 'footer_brand', '板木诚', '底部品牌名称', '2026-03-06 23:06:11', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (2, 'footer_copyright', '© 2024 板木诚 版权所有', '版权文案', '2026-03-06 23:06:11', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (3, 'footer_address', '广东省佛山市南海区丹灶镇明沙南路22号啊', '联系地址', '2026-03-06 23:06:11', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (4, 'footer_phone', '13535717734', '联系电话', '2026-03-06 23:06:11', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (5, 'footer_email', '995754135@qq.com', '联系邮箱', '2026-03-06 23:06:11', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (6, 'footer_brand_en', 'Banmucheng', '底部品牌名称-英文', '2026-03-06 23:10:01', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (7, 'footer_copyright_en', '© 2024 Banmucheng. All rights reserved.', '版权文案-英文', '2026-03-06 23:10:01', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (8, 'footer_address_en', 'No.22 Mingsha South Road, Danzao Town, Nanhai District, Foshan, Guangdong', '联系地址-英文', '2026-03-06 23:10:01', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (9, 'footer_phone_en', '', '联系电话-英文', '2026-03-06 23:10:01', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (10, 'footer_email_en', '', '联系邮箱-英文', '2026-03-06 23:10:01', '2026-03-06 23:11:56');
INSERT INTO `settings` VALUES (11, 'contact_tel', '+86 13535717734', '联系电话', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (12, 'contact_tel_en', '+86 13535717734', '联系电话-英文', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (13, 'contact_whatsapp', '+86 13535717734', 'WhatsApp', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (14, 'contact_whatsapp_en', '+86 13535717734', 'WhatsApp-英文', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (15, 'contact_wechat', '13535717734', '微信', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (16, 'contact_wechat_en', '13535717734', '微信-英文', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (17, 'contact_email', '995754135@qq.com', '联系邮箱', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (18, 'contact_email_en', '995754135@qq.com', '联系邮箱-英文', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (19, 'contact_address', '广东省佛山市南海区丹灶镇明沙南路22号', '联系地址', '2026-03-06 23:35:57', '2026-03-06 23:35:57');
INSERT INTO `settings` VALUES (20, 'contact_address_en', 'No. 22, Mingsha South Road, Danzao Town, Nanhai District, Foshan City, Guangdong Province, China', '联系地址-英文', '2026-03-06 23:35:57', '2026-03-06 23:35:57');

SET FOREIGN_KEY_CHECKS = 1;
