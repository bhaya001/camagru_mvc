<?php
    class User
    {
       private $_db;

        public function __construct()
       {
        $this->_db = new DB();
       }
       
        public function insert($data)
        {
           $this->_db->query('INSERT INTO users (name, email, login, password, token) VALUES (:name, :email, :login, :password, :token)');
           $this->_db->bind(':name', $data['name']);
           $this->_db->bind(':email', $data['email']);
           $this->_db->bind(':login', $data['login']);
           $this->_db->bind(':password', $data['password']);
           $this->_db->bind(':token', $data['token']);
           
           if($this->_db->execute())
                return true;
            else
                return false;
       }
       public function getLoggedUser($id)
       {
           $this->_db->query("SELECT * FROM users WHERE id_user = :id");
           $this->_db->bind(':id',$id);
           $res = $this->_db->getFirst();
           if($this->_db->rows() == 1)
                return $res;
            return false;
       }
        public function findByEmail($email)
       {
           $this->_db->query("SELECT * FROM users WHERE email = :email");
           $this->_db->bind(':email', $email);
           $res = $this->_db->getFirst();
           if($this->_db->rows() > 0)
                return $res;
            return false;
       }
        public function findByLogin($login)
       {
           $this->_db->query("SELECT * FROM users WHERE login = :login");
           $this->_db->bind(':login', $login);
           $res = $this->_db->getFirst();
           if($this->_db->rows() > 0)
                return $res;
            return false;
       }
       
       public function findByToken($token)
       {
            $this->_db->query("SELECT * FROM users WHERE token = :token");
            $this->_db->bind(':token', $token);
            $res = $this->_db->getFirst();
            if($this->_db->rows() > 0)
             return $res;
            return false;
       }

       public function getError()
       {
            return $this->_db->error();
       }
       public function verify($token)
       {
            $this->_db->query("SELECT * FROM users WHERE token = :token AND status = 0");
            $this->_db->bind(':token', $token);
            $res = $this->_db->getFirst();
            if($this->_db->rows()==1)
            {
                $this->_db->query("UPDATE users SET status = 1 WHERE token = :token");
                $this->_db->bind(':token', $token);
                if($this->_db->execute())
                    return $res;
            }
          return false;
       }

       public function resetPassword($email, $password)
       {
          $token = randomizer();
          $this->_db->query("UPDATE users SET password = :password, token = :token WHERE email = :email");
          $this->_db->bind(':password', $password);
          $this->_db->bind(':token', $token);
          $this->_db->bind(':email', $email);
          if($this->_db->execute())
               return true;
          return false;
       }
       public function edit($data,$id)
       {
          $this->_db->query("UPDATE users SET name = :name, login = :login, email = :email, notif = :notif, profile = :profile WHERE id_user = :id");
          $this->_db->bind(':id',$id);
          $this->_db->bind(':name',$data['name']);
          $this->_db->bind(':login',$data['login']);
          $this->_db->bind(':email',$data['email']);
          $this->_db->bind(':notif',$data['notif']);
          $this->_db->bind(':profile',$data['profile']);
          if($this->_db->execute())
               return true;
          return false;
       }
       public function setProfile($id_user, $profile)
       {
          $this->_db->query("UPDATE users SET profile = :profile WHERE id_user = :id_user");
          $this->_db->bind(':profile', $profile);
          $this->_db->bind(':id_user', $id_user);
          if($this->_db->execute())
               return true;
          return false;
       }
    }