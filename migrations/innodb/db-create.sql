/* ===================================== CMSGears Cart ====================================== */

--
-- Table structure for table `cmg_cart`
--

DROP TABLE IF EXISTS `cmg_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) DEFAULT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  `token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_cart_1` (`createdBy`),
  KEY `fk_cmg_cart_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_item`
--

DROP TABLE IF EXISTS `cmg_cart_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cartId` bigint(20) DEFAULT NULL,
  `quantityUnitId` bigint(20) DEFAULT NULL,
  `weightUnitId` bigint(20) DEFAULT NULL,
  `metricUnitId` bigint(20) DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float(8,2) NOT NULL DEFAULT 0,
  `quantity` float(8,2) NOT NULL DEFAULT 0.0,
  `weight` float(8,2) NOT NULL DEFAULT 0.0,
  `length` float(8,2) NOT NULL DEFAULT 0.0,
  `width` float(8,2) NOT NULL DEFAULT 0.0,
  `height` float(8,2) NOT NULL DEFAULT 0.0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_cart_item_1` (`cartId`),
  KEY `fk_cmg_cart_item_2` (`quantityUnitId`),
  KEY `fk_cmg_cart_item_3` (`weightUnitId`),
  KEY `fk_cmg_cart_item_4` (`metricUnitId`),
  KEY `fk_cmg_cart_item_5` (`createdBy`),
  KEY `fk_cmg_cart_item_6` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_order`
--

DROP TABLE IF EXISTS `cmg_cart_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_order` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `parentOrderId` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `status` smallint(6) NOT NULL DEFAULT 0,
  `subTotal` float(8,2) NOT NULL DEFAULT 0.0,
  `tax` float(8,2) NOT NULL DEFAULT 0.0,
  `shipping` float(8,2) NOT NULL DEFAULT 0.0,
  `total` float(8,2) NOT NULL DEFAULT 0.0,
  `discount` float(8,2) NOT NULL DEFAULT 0.0,
  `grandTotal` float(8,2) NOT NULL DEFAULT 0.0,
  `deliveryDate` date DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_cart_order_1` (`createdBy`),
  KEY `fk_cmg_cart_order_2` (`modifiedBy`),
  KEY `fk_cmg_cart_order_3` (`parentOrderId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_order_item`
--

DROP TABLE IF EXISTS `cmg_cart_order_item`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_order_item` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `orderId` bigint(20) NOT NULL,
  `quantityUnitId` bigint(20) DEFAULT NULL,
  `weightUnitId` bigint(20) DEFAULT NULL,
  `metricUnitId` bigint(20) DEFAULT NULL,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sku` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price` float(8,2) NOT NULL DEFAULT 0,
  `discount` float(8,2) NOT NULL DEFAULT 0,
  `quantity` smallint(6) NOT NULL DEFAULT 0,
  `weight` float(8,2) NOT NULL DEFAULT 0.0,
  `length` float(8,2) NOT NULL DEFAULT 0.0,
  `width` float(8,2) NOT NULL DEFAULT 0.0,
  `height` float(8,2) NOT NULL DEFAULT 0.0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_cart_order_item_1` (`orderId`),
  KEY `fk_cmg_cart_order_item_2` (`quantityUnitId`),
  KEY `fk_cmg_cart_order_item_3` (`weightUnitId`),
  KEY `fk_cmg_cart_order_item_4` (`metricUnitId`),
  KEY `fk_cmg_cart_order_item_5` (`createdBy`),
  KEY `fk_cmg_cart_order_item_6` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cmg_cart_voucher`
--

DROP TABLE IF EXISTS `cmg_cart_voucher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cmg_cart_voucher` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `createdBy` bigint(20) NOT NULL,
  `modifiedBy` bigint(20) DEFAULT NULL,
  `parentId` bigint(20) DEFAULT NULL,
  `parentType` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` smallint(6) NOT NULL DEFAULT 0,
  `amount` float(8,2) NOT NULL DEFAULT 0,
  `taxType` smallint(6) NOT NULL DEFAULT 0,
  `freeShipping` tinyint(1) NOT NULL DEFAULT 0,
  `minPurchase` float(8,2) NOT NULL DEFAULT 0.0,
  `maxDiscount` float(8,2) NOT NULL DEFAULT 0.0,
  `startTime` datetime NOT NULL,
  `endTime` datetime DEFAULT NULL,
  `usageLimit` smallint(6) NOT NULL DEFAULT 0,
  `usageCount` smallint(6) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `modifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_cmg_cart_voucher_1` (`createdBy`),
  KEY `fk_cmg_cart_voucher_2` (`modifiedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

SET FOREIGN_KEY_CHECKS=0;

--
-- Constraints for table `cmg_cart`
--
ALTER TABLE `cmg_cart`
	ADD CONSTRAINT `fk_cmg_cart_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_cart_item`
--
ALTER TABLE `cmg_cart_item`
	ADD CONSTRAINT `fk_cmg_cart_item_1` FOREIGN KEY (`cartId`) REFERENCES `cmg_cart` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_item_2` FOREIGN KEY (`quantityUnitId`) REFERENCES `cmg_core_option` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_item_3` FOREIGN KEY (`weightUnitId`) REFERENCES `cmg_core_option` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_item_4` FOREIGN KEY (`metricUnitId`) REFERENCES `cmg_core_option` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_item_5` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_item_6` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_cart_order`
--
ALTER TABLE `cmg_cart_order`
	ADD CONSTRAINT `fk_cmg_cart_order_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_3` FOREIGN KEY (`parentOrderId`) REFERENCES `cmg_cart_order` (`id`);

--
-- Constraints for table `cmg_cart_order_item`
--
ALTER TABLE `cmg_cart_order_item`
	ADD CONSTRAINT `fk_cmg_cart_order_item_1` FOREIGN KEY (`quantityUnitId`) REFERENCES `cmg_core_option` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_item_2` FOREIGN KEY (`orderId`) REFERENCES `cmg_cart_order` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_item_3` FOREIGN KEY (`weightUnitId`) REFERENCES `cmg_core_option` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_item_4` FOREIGN KEY (`metricUnitId`) REFERENCES `cmg_core_option` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_item_5` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_order_item_6` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

--
-- Constraints for table `cmg_cart_voucher`
--
ALTER TABLE `cmg_cart_voucher`
	ADD CONSTRAINT `fk_cmg_cart_voucher_1` FOREIGN KEY (`createdBy`) REFERENCES `cmg_core_user` (`id`),
	ADD CONSTRAINT `fk_cmg_cart_voucher_2` FOREIGN KEY (`modifiedBy`) REFERENCES `cmg_core_user` (`id`);

SET FOREIGN_KEY_CHECKS=1;