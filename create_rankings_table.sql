CREATE TABLE `rankings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `difficulty` varchar(10) NOT NULL,
  `time` varchar(10) NOT NULL,
  `reset` varchar(15) NOT NULL,
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;
