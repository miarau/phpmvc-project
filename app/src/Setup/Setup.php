<?php

namespace Miax\Setup;

class Setup extends \Mos\Database\CDatabaseBasic{

    /**
     * Constructor, make the database connection and call create methods.
     *
     * @return void
     */
    public function __construct($options = []) {
        parent::__construct($options);
        $this->connect();
		$this->dropTables();
		$this->dropViews();
        $this->createTables();
        $this->createViews();
    }


/******************************************************************************************************
 * Create tables
 *
 ******************************************************************************************************/
    /**
     * Create tables
     *
     * @return void
     */
    public function createTables() {

    // Drop all tables, if they exist
    $this->dropTables();
	
	$sql = 'CREATE TABLE `univ_users` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`acronym` varchar(20) DEFAULT NULL,
				`email` varchar(254) DEFAULT NULL,
				`name` varchar(80) DEFAULT NULL,
				`password` varchar(255) DEFAULT NULL,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				PRIMARY KEY (`id`),
				UNIQUE KEY `acronym` (`acronym`));';

	
	$sql .= 'CREATE TABLE `univ_questions` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`qNo` int(11) DEFAULT NULL,
				`commentTo` int(11) DEFAULT NULL,
				`type` char(1) NOT NULL,
				`authorId` int(11) NOT NULL,
				`title` varchar(80) DEFAULT NULL,
				`content` text,
				`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
				`edited` timestamp NULL DEFAULT NULL,
				PRIMARY KEY (`id`),
				KEY `authorId` (`authorId`),
				FOREIGN KEY (`authorId`) REFERENCES `univ_users` (`id`));';
	
	$sql .= 'CREATE TABLE `univ_tags` (
				`id` int(11) NOT NULL AUTO_INCREMENT,
				`tag` varchar(45) NOT NULL,
				PRIMARY KEY (`id`));';

	$sql .= 'CREATE TABLE `univ_questionTagLink` (
				`qNo` int(11) NOT NULL,
				`tagId` int(11) NOT NULL,
				UNIQUE KEY `questiontaglink_index` (`qNo`,`tagId`));';

    $this->execute($sql);
}

    /**
     * Drop tables
     *
     * @return void
     */
    public function dropTables() {
        $sql = 'DROP TABLE IF EXISTS `univ_users`;';
        $sql .= 'DROP TABLE IF EXISTS `univ_questions`;';
        $sql .= 'DROP TABLE IF EXISTS `univ_tags`;';
        $sql .= 'DROP TABLE IF EXISTS `univ_questionTagLink`;';

    $this->execute($sql);

    }


/******************************************************************************************************
 * Create views
 *
 ******************************************************************************************************/

    /**
     * Create views
     *
     * @return void
     */
    public function createViews() {
        // Drop all views, if they exist
        $this->dropViews();
		
        $sql = 'CREATE VIEW `univ_VInfo` AS
                SELECT
                    `univ_questions`.`id` AS `id`,
                    `univ_questions`.`qNo` AS `qNo`,
                    `univ_questions`.`commentTo` AS `commentTo`,
                    `univ_questions`.`type` AS `type`,
                    `univ_questions`.`authorId` AS `authorId`,
                    `univ_questions`.`title` AS `title`,
                    `univ_questions`.`content` AS `content`,
                    `univ_questions`.`created` AS `created`,
                    `univ_questions`.`edited` AS `edited`,
                    `univ_users`.`acronym` AS `acronym`,
                    `univ_users`.`name` AS `name`,
                    `univ_users`.`email` AS `email`
                FROM `univ_questions`
                    LEFT JOIN `univ_users`
                    ON `univ_questions`.`authorId` = `univ_users`.`id`;';
	

        $sql .= 'CREATE VIEW `univ_VTagged` AS
                SELECT
                    `univ_questionTagLink`.`qNo` AS `qNo`,
                    `univ_questionTagLink`.`tagId` AS `tagId`,
                    `univ_tags`.`tag` AS `tag`
                FROM `univ_questionTagLink`
                    LEFT JOIN `univ_tags`
                    ON `univ_questionTagLink`.`tagId` = `univ_tags`.`id`;';
		

        $sql .= 'CREATE VIEW `univ_VTaggedInfo` AS
                SELECT
                    `univ_questions`.`id` AS `id`,
                    `univ_questions`.`title` AS `title`,
                    `univ_questions`.`created` AS `created`,
                    `univ_questions`.`edited` AS `edited`,
                    `univ_questionTagLink`.`tagId` AS `tagId`,
                    `univ_users`.`acronym` AS `acronym`,
                    `univ_users`.`name` AS `name`,
                    `univ_users`.`email` AS `email`
                FROM (`univ_questionTagLink`
                    LEFT JOIN (`univ_questions`
                        LEFT JOIN `univ_users`
                        ON (`univ_questions`.`authorId` = `univ_users`.`id`))
                    ON (`univ_questions`.`id` = `univ_questionTagLink`.`qNo`));';

        
        $this->execute($sql);
    }

    /**
     * Drop views
     *
     * @return void
     */
    public function dropViews() {
        $sql = 'DROP VIEW IF EXISTS `univ_VInfo`;';
        $sql .= 'DROP VIEW IF EXISTS `univ_VTagged`;';
        $sql .= 'DROP VIEW IF EXISTS `univ_VTaggedInfo`;';

     $this->execute($sql);

    }


    /**
     * Add default users, questions, answers and tags
     *
     * @return void
     */
    public function addDefault() {
/******************************************************************************************************
 * Create some default users
 *
 ******************************************************************************************************/    

$sql = "INSERT INTO `univ_users` VALUES
            (1,'admin', 'admin@dbwebb.se', 'Administrator', '$2y$10$PTtsNk.JcXjKqyB8YKKdROQXKu.QfXrkqCnt8i10R4fOlK06xL1Ly', '2016-10-19 00:00:00'),
            (2,'doe', 'doe@dbwebb.se', 'Jane Doe', '$2y$10$DagjZF0aoaVQc.DBfsIbiOlFy5C0uhaq1v/CV9sUqdKag6h.QK/aS', '2016-10-20 00:01:01'),
            (3,'genius', 'genius@mensium.se', 'Genius Einstein', '$2y$10$SiwAUq8frh9go1b7ZcTxJuTMZ0E83sLrQgGE6PS16fEo.HzGcCkzm', '2016-10-21 00:02:02'),
            (4,'isac', 'isac@njeuton.se', 'Isac Njeuton', '$2y$10$odhuMHl.rM750uImYBF9kORllhAQdPOXWmuBcBFf.HoIScrEJzcRy', '2016-10-22 00:03:03'),
            (5,'galli', 'galli@sun.se', 'Gallilea Gallile', '$2y$10$nB691mSf2TdoczqJCHdQC.CI4ZTQ8FMOG/apvhjvd7ASLlmeOZspG', '2016-10-23 04:04:04'),
            (6,'nicke', 'nicke@universe.se', 'Niklas Kopernik', '$2y$10$pfy.e.JxamESHB4V9KKaZuJP54VZ9J00r86ipt6pa/WKLlDn.zPzm', '2016-10-24 05:05:05'),
			(7,'mia', 'mia@design4u2.se', 'Mia Raunegger', '\$2y\$10\$ZfSPu44v.cGG4kH/uYrlOukjE09v1ZoDZLWf5FkhGyfnreSYBwXk.', '2016-10-25 06:06:06'),
			(8,'undrande', 'undrande@space.se', 'Lisa diLeve', '$2y$10$LGwKY7/CiHpziLA4prWQ6.AQzlgm8X47tfnU2MCfQzKRAb6xtADYW', '2016-10-26 07:07:07'),
			(9,'fragvis', 'fragvis@skolan.se', 'Bertil Berg', '$2y$10$SY7QvXabjiGuDzf9QRRmnu4jJ0XivPOQsZQAglOEpk/AGZ5Oar7wi', '2016-10-31 08:08:08'),
            (10,'upplyst', 'upplyst@ahaa.se', 'Närma Nirvana','$2y$10$F3wNVO.EJiZvFZ4aGJxxEe.o0kM/4Ops9T2sHHHzgxWkXC/wGrxqG', '2016-10-29 09:09:09');";
		
$this->execute($sql);

/****************************************************************************************************
 * Create some default questions, answers and comments
 *
 ****************************************************************************************************/ 

 $sql .= "INSERT INTO `univ_questions` VALUES
            (1,1, NULL, 'Q', 8, 'Hur stort är universum', 'Varje kväll när jag lägger mig och tittar ut genom fönstret mot himlen ställer jag mig frågan: Hur stort är univerum? Har ännu inte fått svar. Hjälp!','2016-11-20 13:20:28',NULL),
            (2,2, NULL, 'Q', 2, 'Hur många planeter finns det', 'Undrar hur många planeter det finns i hela universum','2016-11-20 14:00:36',NULL),
            (3,3, NULL, 'Q', 9, 'Vad är mörk materia?', 'Hört talas om att det ska finnas mörk amteria i universum. Vad är det för nåt?','2016-11-20 15:04:45',NULL),
            (4,4, NULL, 'Q', 8, 'Big Bang', 'Vad är Big Bang','2016-11-20 16:22:52',NULL),
            (5,5, NULL, 'Q', 10, 'Andra dimensioner?', 'Finns det fler dimensioner är tre?','2016-11-21 15:14:08',NULL),
            (6,6, NULL, 'Q', 1, 'Varför ramlar vi inte av?', 'Undrar lite över gravitationen och hur den funkar. Varför faller vi inte ut i univerum?','2016-11-21 15:18:57',NULL),
            (7,7, NULL, 'Q', 10, 'Stjärnor', 'Hur bildas en stjärna?','2016-11-21 15:22:52',NULL),
            (8,1, NULL, 'A', 3, NULL, 'Förenklat svar: Oändligt!','2016-11-22 09:51:36',NULL),
            (9,1, 8, 'C', 4, NULL, 'Var inte det där ett lite för enkelt svar? Bättre kan du, mister!','2016-11-22 10:05:41',NULL);";
 $this->execute($sql);

/*******************************************************************************************************
* Create tags
*
********************************************************************************************************/ 
 $sql .= "INSERT INTO `univ_tags` VALUES
            (1,'Big Bang'),
            (2,'naturlagar'),
            (3,'rymdfart'),
            (4,'astronomi'),
            (5,'fysik'),
            (6,'matematik'),
            (7,'multiversum'),
            (8,'solsystemet'),
            (9,'materia'),
            (10,'grundämnen');";
$this->execute($sql);

/******************************************************************************************************
* Create link between question and tag
*
*******************************************************************************************************/ 

$sql .= "INSERT INTO `univ_questionTagLink` VALUES
            (1,4),
            (1,2),
            (1,7),
            (2,7),
            (2,5),
            (3,9),
            (3,10),
            (4,1),
            (4,5),
            (5,5),
            (5,6),
            (5,7),
            (6,2),
            (6,5),
            (6,6),
            (7,1),
            (7,5),
            (7,9),
            (7,10);";

$this->execute($sql);

}
}