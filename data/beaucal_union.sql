CREATE TABLE IF NOT EXISTS `beaucal_union` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item` varchar(255) NOT NULL UNIQUE KEY,
  `set` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
