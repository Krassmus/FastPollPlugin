<?php
class InitPlugin extends Migration
{
	function up(){
		DBManager::get()->exec("
		    CREATE TABLE IF NOT EXISTS `fastpoll` (
                `poll_id` varchar(32) NOT NULL,
                `name` varchar(128) NOT NULL,
                `user_id` varchar(32) NOT NULL,
                `chdate` bigint(20) NOT NULL,
                `mkdate` bigint(20) NOT NULL,
                PRIMARY KEY (`poll_id`),
                KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB
		");
        DBManager::get()->exec("
            CREATE TABLE IF NOT EXISTS `fastpoll_users` (
                `poll_id` varchar(32) NOT NULL,
                `user_id` varchar(32) NOT NULL,
                `mkdate` bigint(20) NOT NULL,
                UNIQUE KEY `uniqueusers` (`poll_id`,`user_id`),
                KEY `poll_id` (`poll_id`),
                KEY `user_id` (`user_id`)
            ) ENGINE=InnoDB
		");
	}
}