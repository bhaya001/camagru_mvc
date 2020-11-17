<?php
class Likes extends Controller
{
    public function __construct()
    {
        $this->like = $this->model('Like'); 
        $this->user = $this->model('User');
        $this->image = $this->model('Image');
    }

    public function addAction()
    {
        if(!(isLoggedIn()) || !(isset($_POST['data'])))
            redirect('user/login');
        if(isset($_POST['data']))
        {
            $decoded = json_decode($_POST['data']);
            $data = [
                'image_id' => $decoded->imageId,
                'liker_id' => $_SESSION['user_id'],
            ];
            if($this->like->insert($data))
                echo true;
            else
                echo false;
        }
    }
    
    public function removeAction()
    {
        if(!(isLoggedIn()) || !(isset($_POST['data'])))
            redirect('user/login');
        if(isset($_POST['data']))
        {
            $decoded = json_decode($_POST['data']);
            $data = [
                'image_id' => $decoded->imageId,
                'liker_id' => $_SESSION['user_id'],
            ];
            if($this->like->remove($data))
                echo true;
            else
                echo false;
        }
    }
}