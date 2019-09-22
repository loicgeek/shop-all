<?php

// app/models/UserModel.class.php

class UserModel extends Model{

    protected $table_name = 'users';

    public function getUsers(){

        $sql = "select * from $this->table_name";

        $users = $this->db->getAll($sql);

        return $users;

    }

    /*
     * @return boolean| user Row
     */
    public function findByEmail($email) {
        $sql = "select * from $this->table_name where email=\"$email\" ";

        $users = $this->db->getAll($sql);
        if(count($users)>0){
            return $users[0];
        }

        return false;
    }

    /*
 * @return boolean
 */
    public function authenticate($email,$password) {
        $sql = "select * from $this->table_name where email=\"$email\" AND password=\"$password\"";

        $users = $this->db->getAll($sql);
        if(count($users)>0){
            return true;
        }
        return false;
    }

    public function getToken($token) {
        $sql = "SELECT * FROM reset_passwords WHERE token=\"$token\" LIMIT 0,1";

        return $this->db->getAll($sql);
    }

    public function removeTokenForUser($userId) {
        try {
            $sql = "DELETE FROM reset_passwords WHERE user_id=\"$userId\"";
            return $this->db->query($sql);
        }catch (Exception $e){
            echo $e->getMessage();
        }
    }

    public function addTokenforUser($userId){
        $token = md5(microtime());
        try {
            $sql = "INSERT INTO  reset_passwords(user_id,token) VALUES(\"$userId\",\"$token\")";
            $this->db->query($sql);
            $tokenId=$this->db->getInsertId();
            $sql2 = "SELECT * FROM reset_passwords WHERE id=\"$tokenId\" LIMIT 0,1";

            $result = $this->db->getAll($sql2);
            return $result ? $result[0]: $result;

        }catch (Exception $e){
            var_dump($e->getMessage());
        }
    }

}