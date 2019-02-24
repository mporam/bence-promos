
# Dump of table email_log
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email_log`;

CREATE TABLE `email_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subject` VARCHAR(250) NOT NULL,
  `message` TEXT CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `attachment_name` VARCHAR(250) DEFAULT NULL,
  `created` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- NO EXISTING DATA TO INSERT


# Dump of table email_user_sent_status
# ------------------------------------------------------------

DROP TABLE IF EXISTS `email_user_sent_status`;

CREATE TABLE `email_user_sent_status` (
  `email_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `sent_successfully` TINYINT(1) NOT NULL,
  `sent_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- NO EXISTING DATA TO INSERT


