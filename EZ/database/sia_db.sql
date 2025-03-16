CREATE TABLE `accounts` (
  `account_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(1) NOT NULL DEFAULT 'U',
  PRIMARY KEY (`account_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`account_id`, `username`, `password`, `role`) VALUES
(1, 'johndoe01', '$2y$10$MuEgt5yRBLdcUD54pc7.suqCSLJ0zuvs9H4imdsA8TOWmHjXXsPsS', 'U'),
(2, 'admin', '$2y$10$NDyuVm1IILse1AXErLiI0O3xReFi1NjQk52PlmfqqZpZSizzn2tRm', 'A'),
(3, 'janedoe01', '$2y$10$mQ7xWrW2wOB0BtMLSEzllerD.my0Hm6bVHyIpxF0CaNPoSX1yTN8u', 'U');


ALTER TABLE `accounts` MODIFY `role` ENUM('U', 'A') NOT NULL DEFAULT 'U';


