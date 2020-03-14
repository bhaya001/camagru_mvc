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
    public function getImagesByPublisher($publisher)
    {
        $this->_db->query("SELECT * FROM images WHERE publisher_id = :publisher_id AND is_profile = 0 ORDER BY created_at DESC");
        $this->_db->bind(':publisher_id', $publisher);
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    }
}