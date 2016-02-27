CREATE TABLE IF NOT EXISTS `beaucal_union` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `item` varchar(255) NOT NULL UNIQUE KEY,
  `set` varchar(255) NULL #to manually delete record, first set this to NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `beaucal_union` ADD INDEX(`set`);
ALTER TABLE `beaucal_union` ADD FOREIGN KEY (`set`)
  REFERENCES `beaucal_union`(`item`) ON DELETE RESTRICT ON UPDATE CASCADE;
