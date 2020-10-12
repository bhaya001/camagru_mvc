<?php
class Image
{
   private $_db;

    public function __construct()
   {
    $this->_db = new DB();
   }

   public function insertProfile($data)
    {
        $this->_db->query('UPDATE images SET is_profile = -1 where publisher_id = :publisher_id AND is_profile = 1');
        $this->_db->bind(':publisher_id', $data['publisher_id']);
        $this->_db->execute();
        $this->_db->query('INSERT INTO images (path, publisher_id, is_profile) VALUES (:path, :publisher_id, 1)');
        $this->_db->bind(':path', $data['path']);
        $this->_db->bind(':publisher_id', $data['publisher_id']);
        if($this->_db->execute())
            return true;
        else
            return false;
    }
    public function insert($data)
    {
        $this->_db->query('INSERT INTO images (path, publisher_id) VALUES (:path, :publisher_id)');
        $this->_db->bind(':path', $data['path']);
        $this->_db->bind(':publisher_id', $data['publisher_id']);
        if($this->_db->execute())
            return true;
        else
            return false;
    }
    public function getError()
       {
            return $this->_db->error();
       }
    public function getImagesByPublisher($publisher, $is_profile)
    {
        $this->_db->query("SELECT * FROM images WHERE publisher_id = :publisher_id AND is_profile = :is_profile ORDER BY updated_at DESC");
        $this->_db->bind(':publisher_id', $publisher);
        $this->_db->bind(':is_profile', $is_profile);
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    }
    public function getImagesRows()
    {
        $this->_db->query("SELECT * FROM images WHERE is_profile = 0");
        $this->_db->getResults();
        return $this->_db->rows();
    }
    public function getImagesPagination($start)
    {
        $this->_db->query("SELECT * FROM images WHERE is_profile = 0 ORDER BY created_at DESC LIMIT $start,5");
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    }
    public function countLikes($id_image)
    {
        $this->_db->query("SELECT COUNT(*) count FROM likes WHERE image_id = :image_id");
        $this->_db->bind(':image_id', $id_image);
        $res = $this->_db->getFirst();
        return $res->count;
    }
    public function countComments($id_image)
    {
        $this->_db->query("SELECT COUNT(*) count FROM comments WHERE image_id = :image_id");
        $this->_db->bind(':image_id', $id_image);
        $res = $this->_db->getFirst();
        return $res->count;
    }
    public function like($id_image, $id_user)
    {
        $this->_db->query("INSERT INTO likes (image_id, liker_id) VALUES (:image_id, :liker_id)");
        $this->_db->bind(':image_id', $id_image);
        $this->_db->bind(':liker_id', $id_user);
        if($this->_db->execute())
            return true;
        else
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