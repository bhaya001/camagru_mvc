<?php
  class Home extends Controller
  {

    public function __construct() {
      $this->user=$this->model('User');
      $this->image=$this->model('Image');
    }

    public function indexAction()
    {
      $data = [
        'page'=>"Gallery",
      ];
      $this->view('home/index', $data);
    }
    public function error404Action()
    {
      $this->view('404Error');
    }
    public function editAction()
    {
      if(!(isLoggedIn()))
        redirect('home/index');
      else
      {
        $images = $this->image->getImagesByPublisher($_SESSION['user_id']);
        $data = [
          'page'=>"Camera",
          'images' =>$images
        ];
        $this->view('home/edit', $data);
      } 
    }
    public function generateAction()
    {
      if(!(isLoggedIn()))
        redirect('home/index');
      if(isset($_POST['data']))
      {
        $decoded = json_decode($_POST['data']);
        if(!($decoded->celebrate) && !($decoded->feeling))
          echo "You must choose a filter!";
        elseif(!$decoded->pic)
          echo "You must upload a picture";
        else
        {
          
          $imgString = preg_replace('/\s/', '+', $decoded->pic);
          $dataImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imgString));
          if($decoded->celebrate)
            $dataImage = mergePics($dataImage, $decoded->celebrate);
          if($decoded->feeling)
            $dataImage = mergePics($dataImage, $decoded->feeling, $pos=1);
          echo "data:image/png;base64,".base64_encode($dataImage);
        }
      }
    }
    public function saveAction()
    {
      if(isset($_POST['data']))
      {
        $data = [];
        $img = $imgString = preg_replace('/\s/', '+', $_POST['data']);
        $uniquesavename=time().uniqid(rand());
        $img = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img));
        $data['path'] = 'uploads/'.$uniquesavename.'.png';
        $data['publisher_id'] = $_SESSION['user_id'];
        if($this->image->insert($data))
        {
          file_put_contents($data['path'],$img);
          echo PROOT.$data['path'];
        }
        else
          echo "problem in insert";
      }
    }

  }
