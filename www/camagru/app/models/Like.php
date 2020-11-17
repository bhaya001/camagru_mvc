<?php
class Like
{
    private $_db;
    public function __construct()
    {
        $this->_db =new DB();
    }

    public function insert($data)
    {
        $this->_db->query('INSERT INTO likes (image_id, liker_id) VALUES (:image_id, :liker_id)');
        $this->_db->bind(':image_id', $data['image_id']);
        $this->_db->bind(':liker_id', $data['liker_id']);
        if($this->_db->execute())
            return true;
        else
            return false;
    }

    public function remove($data)
    {
        $this->_db->query('DELETE FROM likes WHERE image_id = :image_id AND liker_id = :liker_id');
        $this->_db->bind(':image_id', $data['image_id']);
        $this->_db->bind(':liker_id', $data['liker_id']);
        if($this->_db->execute())
            return true;
        else
            return false;
    }

    public function countLikes($id_image)
    {
        $this->_db->query("SELECT COUNT(*) count FROM likes WHERE image_id = :image_id");
        $this->_db->bind(':image_id', $id_image);
        $res = $this->_db->getFirst();
        return $res->count;
    }

    public function getLikesByIdImage($id_image)
    {
        $this->_db->query("SELECT * FROM likes INNER JOIN users ON likes.liker_id = users.id_user WHERE image_id = :image_id");
        $this->_db->bind(':image_id', $id_image);
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    }

    public function isliked($id_image, $id_user)
    {
        $this->_db->query("SELECT * FROM likes WHERE image_id = :image_id AND liker_id = :liker_id");
        $this->_db->bind(':image_id', $id_image);
        $this->_db->bind(':liker_id', $id_user);
        $res = $this->_db->getFirst();
        if($this->_db->rows()==1)
            return true;
        return false;
    }
}

?>