<?php
class Comments extends Controller
{
    public function __construct()
    {
        $this->comment = $this->model('Comment');
        $this->user = $this->model('User');
        $this->image = $this->model('Image');
    }
    public function addAction()
    {
        if(!(isLoggedIn()))
            redirect('user/login');
        if(isset($_POST['data']))
        {
            $decoded = json_decode($_POST['data']);
            if(hash_equals($_SESSION['csrf'],$decoded->csrf))
            {
                $img = $this->image->getImageById($decoded->imageId);
                $user = $this->user->getUserById($img->publisher_id);
                if(trim($decoded->comment))
                {
                    $data = [
                        'image_id' => $decoded->imageId,
                        'author_id' => $_SESSION['user_id'],
                        'comment' => trim(htmlspecialchars($decoded->comment)),
                    ];
                    if($id_comment = $this->comment->insert($data))
                    {
                        $_SESSION['csrf'] = hash_hmac('whirlpool', 'comment', randomizer());
                        $profile = "";
                        if(isset($_SESSION['user_profile']))
                            $profile = PROOT.$_SESSION['user_profile'];
                        $response = [
                            'id_comment'=> $id_comment,
                            'comment' => $data['comment'],
                            'profile' => $profile,
                            'user' => $_SESSION['user_name'],
                            'message' => '',
                            'csrf' => $_SESSION['csrf'],
                            'time' => new DateTime()
                        ];
                    }
                    else
                        $response = [
                            'message' => 'There is an error pleae try to comment later'
                        ];
                    
                }
            }
            else
                $response = [
                    'message' => '502 internal Error Server'
                ];
            echo json_encode($response);
        }
    }
    public function deleteAction()
    {
        if(!(isLoggedIn()))
            redirect('user/login');
        if(isset($_POST['data']))
        {
            $decoded = json_decode($_POST['data']);
            if(hash_equals($_SESSION['csrf'],$decoded->csrf))
            {
                if($this->comment->delete($decoded->commentId))
                {
                    $_SESSION['csrf'] = hash_hmac('whirlpool', 'comment', randomizer());
                    $response=[
                        'message' => "comment deleted",
                        'csrf' => $_SESSION['csrf']
                    ];
                }
                else
                $response=[
                    'message' => "There is an error please try later",
                    'csrf' => ''
                ];
            }
            else
                $response=[
                    'message' => "502 internal Error Server",
                    'csrf' => ''
                ];
            echo json_encode($response);
        }
    }
    public function updateAction($id)
    {
        if(!(isLoggedIn()))
            redirect('user/login');
        if(isset($_POST['data']))
        {
            $decoded = json_decode($_POST['data']);
            if(hash_equals($_SESSION['csrf'],$decoded->csrf))
            {
                if(trim($decoded->comment))
                {
                    $data = [
                        'id_comment'=>$decoded->commentId,
                        'comment' => trim(htmlspecialchars($decoded->comment)),
                    ];
                    if($this->comment->update($data))
                    {
                        $_SESSION['csrf'] = hash_hmac('whirlpool', 'comment', randomizer());
                        $profile = "";
                        if(isset($_SESSION['user_profile']))
                            $profile = PROOT.$_SESSION['user_profile'];
                        $response = [
                            'comment' => $data['comment'],
                            'profile' => $profile,
                            'user' => $_SESSION['user_name'],
                            'message' => '',
                            'csrf' => $_SESSION['csrf'],
                            'time' => new DateTime()
                        ];
                        
                    }
                    else
                        $response=[
                            'message' => "There is an error pleae try to edit your comment later"
                        ];
                    
                }
            }
            else
                $response=[
                    'message' => '502 internal Error Server'
                ];
            echo json_encode($response);
        }
    }
    public function notificationAction()
    {
        if(!(isLoggedIn()) || !(isset($_POST['data'])))
            redirect('users/login');
        $decoded = json_decode($_POST['data']);
        $img = $this->image->getImageById($decoded->imageId);
        $user = $this->user->getUserById($img->publisher_id);
        if(($_SESSION['user_id'] != $img->publisher_id) && ($user->notif == 1))
        {
            $to = $user->email;
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            if($decoded->post)
            {
                $subject = "Camagru New Comment Notification";
                $message = "You have a new comment from ".ucfirst($_SESSION['user_name'])." <a href='".PROOT."images/show/".$decoded->imageId."'>see it now</a>";
            }
            else
            {
                $subject = "Camagru Edit Comment Notification";
                $message = ucfirst($_SESSION['user_name'])." has edited his comment <a href='".PROOT."images/show/".$decoded->imageId."'>see it now</a>";
            }
            mail($to,$subject,$message,$headers);
        }

    }
}
?>