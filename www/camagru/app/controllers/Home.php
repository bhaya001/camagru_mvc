<?php
  class Home extends Controller
  {

    public function indexAction()
    {
      $data = [
        'page'=>"Gallery",
        'profile' => 'profile.png'
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
        $data = [
          'page'=>"Camera",
          'profile' => 'profile.png'
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
