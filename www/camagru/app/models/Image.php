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
        {
            $id = $this->_db->lastInsertId();
            return $id;
        }
        else
            return false;
    }
    public function insert($data)
    {
        $this->_db->query('INSERT INTO images (path, publisher_id) VALUES (:path, :publisher_id)');
        $this->_db->bind(':path', $data['path']);
        $this->_db->bind(':publisher_id', $data['publisher_id']);
        if($this->_db->execute())
        {
            $id = $this->_db->lastInsertId();
            return $id;
        }
        else
            return false;
    }
    public function delete($id)
    {
        $this->_db->query('DELETE FROM images WHERE id_image = :id_image');
        $this->_db->bind(':id_image',$id);
        if($this->_db->execute())
            return true;
        else
            return false;
    }

    public function deleteProfile($id_user)
    {
        $this->_db->query('UPDATE images SET is_profile = -1 WHERE publisher_id = :id_user AND is_profile = 1');
        $this->_db->bind(':id_user',$id_user);
        if($this->_db->execute())
            return true;
        else
            return false;
    }

    public function getError()
       {
            return $this->_db->error();
       }
    
    public function getImageById($id)
    {
           $this->_db->query("SELECT images.*, users.id_user, users.name, users.profile, users.email, users.login FROM images INNER JOIN users ON images.publisher_id = users.id_user WHERE id_image = :id");
           $this->_db->bind(':id', $id);
           $res = $this->_db->getFirst();
           if($this->_db->rows() > 0)
               return $res;
           return false;
    }

    public function getImagesByPublisher($publisher)
    {
        $this->_db->query("SELECT * FROM images WHERE publisher_id = :publisher_id AND is_profile = 0 ORDER BY updated_at DESC");
        $this->_db->bind(':publisher_id', $publisher);
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
        $this->_db->query("SELECT * FROM images WHERE is_profile = 0 ORDER BY created_at DESC LIMIT $start,6");
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    }

    public function getUserImagesRows($id)
    {
        $this->_db->query("SELECT * FROM images WHERE is_profile = 0 AND publisher_id = :publisher_id");
        $this->_db->bind(':publisher_id', $id);
        $this->_db->getResults();
        return $this->_db->rows();
    }
    public function getUserImagesPagination($id,$start)
    {
        $this->_db->query("SELECT * FROM images WHERE is_profile = 0 AND publisher_id = :publisher_id ORDER BY created_at DESC LIMIT $start,6");
        $this->_db->bind(':publisher_id', $id);
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    } 
}