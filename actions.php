<?php
include("config.php");
include("db.php");
include("login.php");
include("poster.php");
include("libs.php");
include("projectcards.php");
include("cardbuilder.php");
$database = new DB;
$database->OpenConnection();
//$database->Install();
$userauth = new USERAUTH;
$userpost = new SPIELPOSTER;
$cardbuilder = new CARDBUILDER;
$projectcards = new PROJECTCARDS;
$action = $_POST['action'];
$uriaction = $_GET['a'];
//echo $uriaction;
   
if(!empty($action))
{
    switch($action)
    {
        case "post":
            if($userpost->post($database))
            {
                echo $userpost->get_success();
            }else{
                echo $userpost->get_error();
            }
            break;

        case "signup":
            if($userauth->signup($database))
            {
                //echo "signedup";
                echo $userauth->get_success();
            } else {
                
                echo $userauth->get_error();
                //echo "signup failed: ".$userauth->$haserror." ".$authmessage;
            }
        break;

        case "login":
            if($userauth->login($database))
            {
                echo $userauth->get_success();
            }else{
                echo $userauth->get_error();
            }
            break;

        case "delete_project":
            if(!empty($_SESSION['id']))
            {
                if(empty($_POST['project_id']))
                {
                    echo json_encode(['success' => false, 'message' => 'Project ID is required']);
                    break;
                }
                
                $projectid = $_POST['project_id'];
                $userid = $_SESSION['id'];
                
                if($database->DeleteProject($projectid, $userid))
                {
                    echo json_encode(['success' => true, 'message' => 'Project deleted successfully']);
                } else {
                    echo json_encode(['success' => false, 'message' => $database->get_error()]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'You must be logged in to delete projects']);
            }
            break;

        default:

            break;
    }
    //look for get actions
}
if(!empty($uriaction))
{
    switch($uriaction)
    {
        case "user_verification":

            $token = $_GET['token'];
            if($userauth->authemail($database,$token))
            {
                $authmessage = '<div class="alert alert-primary" role="alert">
                Email Verified! You can now login.
                </div>';
            } else {
                $authmessage = '<div class="alert alert-danger" role="alert">
                Verification Token is either expired or invalid.
                </div>';                
            }
            
            break;


        case "logout":
            session_start();
            session_destroy();
              
            header("Location: http://spielspot.org");
            break;
        default:

        case "profile":
            include("Theme/Main/profile.php");
        break;           
    }
}
$database->CloseConnection();


function get_projects($postquery)
{
    global $database,$userpost,$projectcards;   
    $posts = $userpost->showProjects($database,$postquery);
    //print_r($posts);
    return $projectcards->make_cards($posts);  
}

function get_posts($postquery)
{
    global $database,$userpost,$cardbuilder;   
    $posts = $userpost->showPosts($database,$postquery);
    return $cardbuilder->make_cards($posts);
}
?>