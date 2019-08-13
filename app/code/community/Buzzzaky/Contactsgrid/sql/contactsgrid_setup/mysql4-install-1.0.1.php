<?php

$installer = $this;
$installer->startSetup();

$installer->run("
  
CREATE TABLE IF NOT EXISTS `contacts_grid` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(20) NOT NULL DEFAULT '',
  `comment` text,
  `reply` text,
  `comment_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reply_date` datetime NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='ACL Asserts' AUTO_INCREMENT=11 ;
");
$installer->endSetup();
