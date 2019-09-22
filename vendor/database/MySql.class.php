<?php

/**

 *================================================================

 *vendor/database/Mysql.class.php

 *Database operation class

 *================================================================

 */

class Mysql{

    protected $conn = false;  //DB connection resources

    protected $sql;           //sql statement
    



    /**

     * Constructor, to connect to database, select database and set charset

     * @param $config string configuration array

     */

    public function __construct(){

        $this->conn = DB::getInstance();
    }


    /**

     * Set charset

     * @access private

     * @param $charset string charset

     */

    private function setChar($charest){

        $sql = 'set names '.$charest;

        $this->query($sql);

    }

    /**

     * Execute SQL statement

     * @access public

     * @param $sql string SQL query statement

     * @return $result，if succeed, return resrouces; if fail return error message and exit

     */

    public function query($sql){

        $this->sql = $sql;

        // Write SQL statement into log

        $str = $sql . "  [". date("Y-m-d H:i:s") ."]" . PHP_EOL;

        file_put_contents("log.txt", $str,FILE_APPEND);

        $result = $this->conn->query($this->sql);

        if (!$result) {

            die($this->errno().':'.$this->error().'<br />Error SQL statement is '.$this->sql.'<br />');

        }

        return $result;

    }

    /**

     * Get the first column of the first record

     * @access public

     * @param $sql string SQL query statement

     * @return  bool|row the value of this column

     */

    public function getOne($sql){

        $result = $this->query($sql);
        $row = $result->fetch(PDO::FETCH_NUM);

        if ($row) {

            return $row[0];

        } else {

            return false;

        }

    }

    /**

     * Get one record

     * @access public

     * @param  String $sql SQL query statement

     * @return array|bool associative array

     */

    public function getRow($sql){

        if ($result = $this->query($sql)) {

            $row = $result->fetch(PDO::FETCH_ASSOC);

            return $row;

        } else {

            return false;

        }

    }

    /**

     * Get all records

     * @access public

     * @param String $sql SQL query statement

     * @return array $list an 2D array containing all result records

     */

    public function getAll($sql){

        $result = $this->query($sql);

        $list = array();

        while ( $row = $result->fetch(PDO::FETCH_ASSOC)){

            $list[] = $row;

        }

        return $list;

    }

    /**

     * Get the value of a column

     * @access public

     * @param $sql string SQL query statement

     * @return $list array an array of the value of this column

     */

    public function getCol($sql){

        $result = $this->query($sql);

        $list = array();

        while ( $row = $result->fetch(PDO::FETCH_NUM)) {

            $list[] = $row[0];

        }

        return $list;

    }




    /**

     * Get last insert id

     */

    public function getInsertId(){

        return $this->conn->lastInsertId();

    }

    /**

     * Get error number

     * @access private

     * @return error number

     */

    public function errno(){


        return $this->conn->errorCode();

    }

    /**

     * Get error message

     * @access private

     * @return array error message

     */

    public function error(){

        return $this->conn->errorInfo();

    }

}