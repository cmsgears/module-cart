SET FOREIGN_KEY_CHECKS=0;

-- ================ Mall, Merchant and Outlets =================

--
-- Table structure for table `cmg_cart_vmall`
--

DROP TABLE IF EXISTS `cmg_cart_vmall`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_vmall` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mode` smallint(6) DEFAULT 0,
  `chargeType` smallint(6) DEFAULT 0,
  `chargeAmount` float(8,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_merchant`
--

DROP TABLE IF EXISTS `cmg_cart_merchant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_merchant` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `mallId` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_merchant_1` (`userId`),
  KEY `fk_cart_merchant_2` (`mallId`),
  CONSTRAINT `fk_cart_merchant_1` FOREIGN KEY (`userId`) REFERENCES `cmg_user` (`id`),
  CONSTRAINT `fk_cart_merchant_2` FOREIGN KEY (`mallId`) REFERENCES `cmg_cart_vmall` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_outlet`
--

DROP TABLE IF EXISTS `cmg_cart_outlet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_outlet` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `merchantId` bigint(20) NOT NULL,
  `locationId` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_outlet_1` (`merchantId`),
  KEY `fk_cart_outlet_2` (`locationId`),
  CONSTRAINT `fk_cart_outlet_1` FOREIGN KEY (`merchantId`) REFERENCES `cmg_cart_merchant` (`id`),
  CONSTRAINT `fk_cart_outlet_2` FOREIGN KEY (`locationId`) REFERENCES `cmg_address` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ================ Product =================

--
-- Table structure for table `cmg_cart_product`
--

DROP TABLE IF EXISTS `cmg_cart_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_product` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `outletId` bigint(20) NOT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `bannerId` bigint(20) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` smallint(6) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `visibility` smallint(6) DEFAULT NULL,
  `summary` text COLLATE utf8_unicode_ci,
  `content` longtext COLLATE utf8_unicode_ci,
  `price` float(8,2) DEFAULT '0.00',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_product_1` (`outletId`),
  KEY `fk_cart_product_2` (`createdBy`),
  KEY `fk_cart_product_3` (`modifiedBy`),
  KEY `fk_cart_product_4` (`bannerId`),
  CONSTRAINT `fk_cart_product_1` FOREIGN KEY (`outletId`) REFERENCES `cmg_cart_outlet` (`id`),
  CONSTRAINT `fk_cart_product_2` FOREIGN KEY (`createdBy`) REFERENCES `cmg_user` (`id`),
  CONSTRAINT `fk_cart_product_3` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_user` (`id`),
  CONSTRAINT `fk_cart_product_4` FOREIGN KEY (`bannerId`) REFERENCES `cmg_file` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_product_variation`
--

DROP TABLE IF EXISTS `cmg_cart_product_variation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_product_variation` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `productId` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float(8,2) DEFAULT '0.00',
  `increment` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `fk_cart_product_variation_1` (`productId`),
  CONSTRAINT `fk_cart_product_variation_1` FOREIGN KEY (`productId`) REFERENCES `cmg_cart_product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_product_plan`
--

DROP TABLE IF EXISTS `cmg_cart_product_plan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_product_plan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `productId` bigint(20) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `period` smallint(6) DEFAULT 0,
  `trial` smallint(6) DEFAULT 0,
  `price` float(8,2) DEFAULT '0.00',
  `interval` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `fk_cart_product_plan_1` (`productId`),
  CONSTRAINT `fk_cart_product_plan_1` FOREIGN KEY (`productId`) REFERENCES `cmg_cart_product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ================ Coupon =================

--
-- Table structure for table `cmg_cart_coupon`
--

DROP TABLE IF EXISTS `cmg_cart_coupon`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_coupon` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` smallint(6) DEFAULT 0,
  `amount` float(8,2) DEFAULT '0.00',
  `taxType` smallint(6) DEFAULT 0,
  `shippingType` smallint(6) DEFAULT 0,
  `minPurchase` float(8,2) DEFAULT '0.00',
  `maxDiscount` float(8,2) DEFAULT '0.00',
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `expireAt` datetime DEFAULT NULL,
  `usageLimit` smallint(6) DEFAULT 0,
  `usageCount` smallint(6) DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ================ Subscriptions =================

--
-- Table structure for table `cmg_cart_sub`
--

DROP TABLE IF EXISTS `cmg_cart_sub`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_sub` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `userId` bigint(20) NOT NULL,
  `productId` bigint(20) NOT NULL,
  `planId` bigint(20) NOT NULL,
  `period` smallint(6) DEFAULT 0,
  `trial` smallint(6) DEFAULT 0,
  `price` float(8,2) DEFAULT '0.00',
  `interval` smallint(6) DEFAULT 0,
  `startDate` date NOT NULL,
  `lastPaymentDate` date DEFAULT NULL,
  `nextPaymentDate` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cart_sub_1` (`userId`),
  KEY `fk_cart_sub_2` (`productId`),
  KEY `fk_cart_sub_3` (`planId`),
  CONSTRAINT `fk_cart_sub_1` FOREIGN KEY (`userId`) REFERENCES `cmg_user` (`id`),
  CONSTRAINT `fk_cart_sub_2` FOREIGN KEY (`productId`) REFERENCES `cmg_cart_product` (`id`),
  CONSTRAINT `fk_cart_sub_3` FOREIGN KEY (`planId`) REFERENCES `cmg_cart_product_plan` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

-- ================ Cart =================



-- ================ Order =================



SET FOREIGN_KEY_CHECKS=1;