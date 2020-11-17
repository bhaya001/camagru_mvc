<?php
class Comment
{
    private $_db;
    public function __construct()
    {
        $this->_db =new DB();
    }

    public function insert($data)
    {
        $this->_db->query('INSERT INTO comments (image_id, author_id, comment) VALUES (:image_id, :author_id, :comment)');
        $this->_db->bind(':image_id', $data['image_id']);
        $this->_db->bind(':author_id', $data['author_id']);
        $this->_db->bind(':comment', $data['comment']);
        if($this->_db->execute())
        {
            $id = $this->_db->lastInsertId();
            return $id;
        }
        else
            return false;
    }

    public function countComments($id_image)
    {
        $this->_db->query("SELECT COUNT(*) count FROM comments WHERE image_id = :image_id");
        $this->_db->bind(':image_id', $id_image);
        $res = $this->_db->getFirst();
        return $res->count;
    }

    public function getCommentsByIdImage($id_image)
    {
        $this->_db->query("SELECT comments.*, users.id_user, users.name, users.profile, users.email, users.login  FROM comments INNER JOIN users ON comments.author_id = users.id_user WHERE image_id = :image_id ORDER BY created_at");
        $this->_db->bind(':image_id', $id_image);
        $res = $this->_db->getResults();
        if($this->_db->rows() > 0)
            return $res;
        return false;
    }

    public function delete($id)
    {
        $this->_db->query('DELETE FROM comments WHERE id_comment = :id_comment');
        $this->_db->bind(':id_comment',$id);
        if($this->_db->execute())
            return true;
        else
            return false;
    }
    public function update($data)
    {
        $this->_db->query('UPDATE comments SET comment = :comment WHERE id_comment = :id_comment');
        $this->_db->bind(':comment',$data['comment']);
        $this->_db->bind(':id_comment',$data['id_comment']);
        if($this->_db->execute())
            return true;
        else
            return false;
    }

}
?>