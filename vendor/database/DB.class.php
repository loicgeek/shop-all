<?php
/**
 * PDO Singleton Class v.1.0
 *
 * @author Ademílson F. Tonato
 * @link https://twitter.com/ftonato
 *
 */
class DB {
    protected static $instance;
    protected function __construct() {}
    public static function getInstance() {
        if(empty(self::$instance)) {

            $db_info = array(
                "db_host" => AppConfig::$host,
                "db_port" => AppConfig::$port,
                "db_user" => AppConfig::$user,
                "db_pass" => AppConfig::$password,
                "db_name" => AppConfig::$dbname,
                "db_charset" => "UTF-8");
            try {
                self::$instance = new PDO("mysql:host=".$db_info['db_host'].';port='.$db_info['db_port'].';dbname='.$db_info['db_name'], $db_info['db_user'], $db_info['db_pass']);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_SILENT);
                self::$instance->query('SET NAMES utf8');
                self::$instance->query('SET CHARACTER SET utf8');
            } catch(PDOException $error) {
                echo $error->getMessage();
            }
        }
        return self::$instance;
    }
    public static function setCharsetEncoding() {
        if (self::$instance =! null) {
            self::$instance->exec(
                "SET NAMES 'utf8';
			SET character_set_connection=utf8;
			SET character_set_client=utf8;
			SET character_set_results=utf8");
        }

    }
}
