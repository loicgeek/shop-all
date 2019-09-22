<?php

$GLOBALS['config'] =array();
$GLOBALS['config']['host'] ='localhost';
 $GLOBALS['config']['user']='root';
 $GLOBALS['config']['dbname'] = 'minette';
 $GLOBALS['config']['port'] ='3306';

 class AppConfig{

     public static $host = 'localhost';
     public static $user = 'root';
     public static $password = '';
     public static $port = '3306';
     public static $dbname = 'minette';

     /**
      * AppConfig constructor.
      * @param string $host
      * @param string $user
      * @param string $password
      * @param string $port
      */
     public function __construct($dbname=null,$host=null, $user=null, $password=null, $port=null)
     {
         if(!empty($host))
            $this->host = $host;

         if(!empty($user))
             $this->user = $user;

         if(!empty($password))
             $this->password = $password;

         if(!empty($port))
             $this->port = $port;

         if(!empty($dbname))
             $this->dbname= $dbname;

     }




 }
