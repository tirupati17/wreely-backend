
---
--- For incorrect datetime value fix on new server
--- Changes Done : Localhost, smoke, production

SELECT @@GLOBAL.sql_mode global, @@SESSION.sql_mode session;
SET sql_mode = '';
SET GLOBAL sql_mode = '';

---
--- 15 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_space` ADD `isDeleted` TINYINT NOT NULL DEFAULT '0' AFTER `expiry_date`, ADD `updatedBy` INT(11) NULL AFTER `isDeleted`, ADD `updatedDtm` DATETIME NULL AFTER `updatedBy`;

ALTER TABLE `tbl_enquiries` ADD `createdBy` INT(11) NULL AFTER `tell_us_more`, ADD `isDeleted` TINYINT NOT NULL DEFAULT '0' AFTER `createdBy`, ADD `updatedBy` INT(11) NULL AFTER `isDeleted`, ADD `updatedDtm` DATETIME NULL AFTER `updatedBy`;
ALTER TABLE `tbl_enquiries` ADD `notes` TEXT NULL AFTER `updatedDtm`;
ALTER TABLE `tbl_enquiries` ADD `createdDtm` DATETIME NOT NULL AFTER `updatedDtm`;

---
--- 15 Nov 2017 - End
---

---
--- 17 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_membership_type` ADD `isDeleted` TINYINT NOT NULL DEFAULT '0' AFTER `price`;

ALTER TABLE `tbl_seats` ADD `isDeleted` TINYINT NOT NULL DEFAULT '0' AFTER `membership_type_id`;


---
--- 18 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_membership_type` ADD `quantity` INT(10) NOT NULL AFTER `price`;



---
--- 20 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_space` ADD `company_id` INT(11) NOT NULL AFTER `member_id`;


---
--- 27 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

UPDATE `tbl_plan_type` SET `id` = '2' WHERE `tbl_plan_type`.`id` = 3;

---
--- 27 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_members` ADD `user_id` INT NOT NULL AFTER `id`;
ALTER TABLE `tbl_companies` ADD `user_id` INT NOT NULL AFTER `id`;
ALTER TABLE `tbl_flexi_attendance` ADD `user_id` INT NOT NULL AFTER `id`
ALTER TABLE `tbl_enquiries` ADD `user_id` INT NOT NULL AFTER `id`;
ALTER TABLE `tbl_membership_type` ADD `user_id` INT NOT NULL AFTER `id`;
ALTER TABLE `tbl_plan_type` ADD `user_id` INT NOT NULL AFTER `id`;
ALTER TABLE `tbl_roles` ADD `user_id` INT NOT NULL AFTER `roleId`;
ALTER TABLE `tbl_seats` ADD `user_id` INT NOT NULL AFTER `id`;
ALTER TABLE `tbl_space` ADD `user_id` INT NOT NULL AFTER `id`;

---
--- 30 Nov 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_membership_type` ADD `number_of_day` INT NOT NULL AFTER `plan_type_id`;
UPDATE `tbl_membership_type` SET `number_of_day` = '5' WHERE `tbl_membership_type`.`id` = 8;
UPDATE `tbl_membership_type` SET `number_of_day` = '12' WHERE `tbl_membership_type`.`id` = 9;


---
--- 4 Dec 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_reset_password` CHANGE `createdBy` `createdBy` INT(20) NOT NULL DEFAULT '0';
ALTER TABLE `tbl_reset_password` CHANGE `isDeleted` `isDeleted` INT(20) NOT NULL DEFAULT '0';
ALTER TABLE `tbl_reset_password` CHANGE `updatedBy` `updatedBy` INT(20) NOT NULL DEFAULT '0';
ALTER TABLE `tbl_reset_password` CHANGE `updatedDtm` `updatedDtm` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP;

---
--- 4 Dec 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_users` ADD `domain_name_reference` VARCHAR(50) NOT NULL AFTER `quickbooks_authtoken`;

---
--- 4 Dec 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_companies` ADD `isDeleted` TINYINT NOT NULL DEFAULT '0' AFTER `website`;
ALTER TABLE `tbl_members` ADD `updatedBy` INT NOT NULL AFTER `isDeleted`;

---
--- 4 Dec 2017 - Start
--- Changes Done : Localhost, smoke, production

ALTER TABLE `tbl_users` ADD `access_token` VARCHAR(100) NOT NULL AFTER `updatedDtm`;
ALTER TABLE `tbl_users` ADD `vendor_logo_url` VARCHAR(200) NOT NULL AFTER `access_token`;
ALTER TABLE `tbl_users` ADD `country_code` VARCHAR(30) NOT NULL AFTER `vendor_logo_url`;
ALTER TABLE `tbl_users` ADD `is_mobile_verified` TINYINT NOT NULL DEFAULT '0' AFTER `country_code`;
ALTER TABLE `tbl_users` ADD `random_code` VARCHAR(50) NOT NULL AFTER `is_mobile_verified`;
ALTER TABLE `tbl_users` ADD `device_token` VARCHAR(100) NOT NULL AFTER `random_code`;

---
--- 12 Dec 2017 - Start
--- Changes Done : Localhost

INSERT INTO `tbl_users` (`userId`, `email`, `password`, `name`, `mobile`, `roleId`, `isDeleted`, `zohobooks_authtoken`, `zohobooks_organization_id`, `quickbooks_authtoken`, `domain_name_reference`, `createdBy`, `createdDtm`, `updatedBy`, `updatedDtm`, `access_token`, `vendor_logo_url`, `country_code`, `is_mobile_verified`, `random_code`, `device_token`) VALUES ('8', 'apoorva@workloft.in', '$2y$10$YkMoaeAPBQuj5h7leabyXOkJUy/zU2EyjGIf0QBa4cahfUMQYsK.O', 'Workloft', NULL, '', '0', '', '', '', '', '', '2017-12-12 13:00:00', NULL, NULL, '', 'http://theplayce.celerstudio.com/assets/images/8/8-logo-large.png', '+91', '0', '', '');

---
--- 3 Jan 2018 - Start
--- Changes Done : Localhost

ALTER TABLE `tbl_companies` ADD `company_fir_key` TEXT NOT NULL AFTER `isDeleted`;
ALTER TABLE `tbl_members` ADD `member_fir_key` TEXT NOT NULL AFTER `updatedBy`;
ALTER TABLE `tbl_users` ADD `user_fir_key` TEXT NOT NULL AFTER `device_token`;
