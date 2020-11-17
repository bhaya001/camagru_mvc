<?php
  class Images extends Controller
  {

    public function __construct() {
      $this->user=$this->model('User');
      $this->image=$this->model('Image');
      $this->comment=$this->model('Comment');
      $this->like=$this->model('Like');
      
    }

    public function galleryAction($page = 1)
    {
      $num_rows = $this->image->getImagesRows();
      $likes = [];
      $comments = [];
      $num_pages = ceil($num_rows / 6);
      $start = ($page - 1) * 6;
      if($images =$this->image->getImagesPagination($start))
      {
        foreach ($images as $image) {
          $likes["$image->id_image"] = $this->like->countLikes($image->id_image);
          $comments["$image->id_image"] = $this->comment->countComments($image->id_image);
        }
      }
      else
        redirect("images/error404");
      $data = [
        'page'=>"Gallery",
        'images' => $images,
        'num_pages' =>$num_pages,
        'curr_page' => $page,
        'likes' => $likes,
        'comments'=> $comments,
        'to' => 'gallery'
      ];
      $this->view('images/gallery', $data);
    }

    public function userAction($id , $page = 1)
    {
      $num_rows = $this->image->getUserImagesRows($id);
      $likes = [];
      $comments = [];
      $num_pages = ceil($num_rows/6);
      $start = ($page - 1) * 6;
      if($images = $this->image->getUserImagesPagination($id,$start))
      {
        foreach ($images as $image) {
          $likes["$image->id_image"] = $this->like->countLikes($image->id_image);
          $comments["$image->id_image"] = $this->comment->countComments($image->id_image);
        }
      }
      $data = [
        'page'=>"User Posts",
        'images' => $images,
        'num_pages' =>$num_pages,
        'curr_page' => $page,
        'likes' => $likes,
        'comments'=> $comments,
        'to' => "user/".$id
      ];
      $this->view('images/gallery', $data);
    
    }

    public function error404Action()
    {
      $data = [
        'page' => "404 Error Page Not Found"
      ];
      $this->view('404Error',$data);
    }
    public function cameraAction()
    {
      if(!(isLoggedIn()))
        redirect('users/login');
      else
      {
        if(empty($_SESSION['csrf'])) 
          $_SESSION['csrf'] = hash_hmac('whirlpool', 'camera', randomizer());
        $likes = [];
        $comments = [];
        if($images = $this->image->getImagesByPublisher($_SESSION['user_id']))
        {
          foreach($images as $image)
          {
            $likes["$image->id_image"] = $this->like->countLikes($image->id_image);
            $comments["$image->id_image"] = $this->comment->countComments($image->id_image);
          }
        }
        $data = [
          'page'=>"Camera",
          'images' =>$images,
          'likes' => $likes,
          'csrf' => $_SESSION['csrf'],
          'comments'=> $comments
        ];
        $this->view('images/camera', $data);
      }
    }
    public function generateAction()
    {
      if(!(isLoggedIn()) || !(isset($_POST['data'])))
        redirect('images/gallery');
      else
      {
        $decoded = json_decode($_POST['data']);
        if(hash_equals($_SESSION['csrf'],$decoded->csrf))
        {
          if(!($decoded->celebrate) && !($decoded->feeling))
            $response=[
              'camagru' => "",
              'message' => 'You must choose a Superposable photo',
              'csrf' => ''
            ];
          elseif(!$decoded->pic)
            $response=[
              'camagru' => "",
              'message' => 'You must upload a photo',
              'csrf' => ''
            ];
          else
          {
            $imgString = preg_replace('/\s/', '+', $decoded->pic);
            $dataImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgString));
            if($decoded->celebrate)
              $dataImage = mergePics($dataImage, $decoded->celebrate);
            if($decoded->feeling)
              $dataImage = mergePics($dataImage, $decoded->feeling, $pos=1);
            $_SESSION['csrf'] = hash_hmac('whirlpool', 'camera', randomizer());
            $response=[
              'camagru' => "data:image/png;base64,".base64_encode($dataImage),
              'message' => '',
              'csrf' => $_SESSION['csrf']
            ];
            
          }
        }
        else
          $response=[
            'camagru' => "",
            'message' => '502 Internal Error Server',
            'csrf' => ''
          ];
        echo json_encode($response);
      }
    }

    public function deleteAction($id)
    {
      if(!(isLoggedIn()) || !(isset($_POST['data'])))
          redirect('images/gallery');
      else
      {
        $decoded = json_decode($_POST['data']);
        if(hash_equals($_SESSION['csrf'],$decoded->csrf))
        {
          if($img = $this->image->getImageById($id))
          {
            if($img->publisher_id === $_SESSION['user_id'])
            {
              if($this->image->delete($id))
              {
                $_SESSION['csrf'] = hash_hmac('whirlpool', 'camera', randomizer());
                $response = [
                  'csrf' => $_SESSION['csrf'],
                  'message' => "Image was deleted successfuly"
                ];
              }
            }
            else
              redirect('images/gallery');
          }
          else
          {
            $response = [
              'csrf' => '',
              'message' => "There is an error",
            ];
          }
        }
        else
        {
          $response = [
            'csrf' => "",
            'message' => "502 Internal Error Server",
          ];
        }
        echo json_encode($response);
      }
    }

    public function saveAction()
    {
      if(!(isLoggedIn()) || !(isset($_POST['data'])))
        redirect('images/gallery');
      else
      {
        $data = [];
        $img = $imgString = preg_replace('/\s/', '+', $_POST['data']);
        $uniquesavename=time().uniqid(rand());
        $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
        $data['path'] = 'uploads/'.$uniquesavename.'.png';
        $data['publisher_id'] = $_SESSION['user_id'];
        if($id_image = $this->image->insert($data))
        {
          file_put_contents($data['path'],$img);
          $response = [
            'message' => '',
            'id_image' => $id_image,
            'path'=> PROOT.$data['path']
          ];
          
        }
        else
           $response=[
             'message' => "There is an error please try later",
             'id_image' => '',
             'path' => ''
           ];
        echo json_encode($response);
      }
    }
    public function showAction($id=null)
    {
      if(!(isLoggedIn()))
        redirect('users/login');
      else
      {
        if(!$id || !($this->image->getImageById($id)))
          redirect('images/error404');
        if(empty($_SESSION['csrf'])) 
          $_SESSION['csrf'] = hash_hmac('whirlpool', 'camera', randomizer());
        $is_liked = 0;
        $img = $this->image->getImageById($id);
        $likes = $this->like->getLikesByIdImage($id);
        $comments = $this->comment->getCommentsByIdImage($id);
        if($likes){
          foreach ($likes as $value) {
            if($value->liker_id == $_SESSION['user_id'])
            {
              $is_liked = 1;
              break;
            }
          }
        }
        if($likes)
          $count = count($likes) - $is_liked;
        else
          $count = 0;
        $string = "Liked by ";
        if($count == 0 && !$is_liked)
          $string.= "No one";
        else
        {
          if($is_liked)
              $string .= "You ";
          if(($is_liked) && $count >= 1)
              $string .= "And <span style='border-bottom:1px solid'>".$count." Others</span>";
          if(!($is_liked) && $count >= 1)
              $string .= "<span style='border-bottom:1px solid'>".$count." Person</span>";
        }

        $data = [
          'page'=>"Image Details",
          'image' => $img,
          'image_on' => new DateTime($img->created_at),
          'likes' => $likes,
          'count_likes' => $count,
          'string_like' => $string,
          'comments'=> $comments,
          'csrf' => $_SESSION['csrf'],
          'is_liked'=> $is_liked
        ];
        $this->view('images/show', $data);
      }
    }

  }
