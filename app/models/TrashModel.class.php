<?php


class TrashModel extends Model
{

    protected $table_name= 'trash';

    public function getTrashByUserId($userId){
        $sql = "select  p.id,p.name,p.price,p.description,p.image from $this->table_name t,products p where t.product_id = p.id  and user_id=\"$userId\" ";

        $trash = $this->db->getAll($sql);
        return $trash;
    }

    public function exists($userId,$productId){
        $sql = "select * from $this->table_name t where t.product_id=\"$productId\" AND t.user_id=\"$userId\" ";

        $trash = $this->db->getAll($sql);

        if($trash && count($trash)>0){
            return true;
        }
        return false;

    }

    public function deleteFromTrash($userId, $ProductId) {
        $sql = "delete from $this->table_name  where product_id =\"$ProductId\" and user_id=\"$userId\" ";
        return $this->db->query($sql);

    }
}