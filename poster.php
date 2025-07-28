<?php

class SPIELPOSTER
{
    public $successmess = 'Success';
    public $errormess = 'Error';
    public $haserror = 0;

    function get_error() {
        return $this->errormess;
    }

    function get_success() {
        return $this->successmess;
    }

    function showPosts($database,$postdata)
    {   $database->OpenConnection();

        $posts = $database->GetPosts($postdata);
        //print_r($posts);
        $database->CloseConnection();
        return $posts;
    }

    function showProjects($database,$postdata)
    {   $database->OpenConnection();

        $posts = $database->GetProjects($postdata);
        //print_r($posts);
        $database->CloseConnection();
        return $posts;
    }

    function post($database)
    {
        $userid =  $_SESSION['id'];
        $content = $_POST["mySpiel"];
        $jcontent = json_decode($content);
        $scontent = json_encode($jcontent);
        $postdata = [
            "userid" => $userid,
            "content" => mysqli_real_escape_string($database->$conn, $scontent)
        ]; 

        if($database->AddPost($postdata))
        {
            $this->successmess = '<div class="alert alert-success" role="alert">
            Your Spiel was Posted!
            </div>
            <script id="postedscript">
            setTimeout(function() {
                $.get( "ajax.php?a=showpost&id='.$database->get_lastAdded().'",function( data ) {
                $( "#output" ).html( data );           
            });}, 1000);           
            </script>';
            $this->haserror = 0;
            return TRUE;
        }
        $this->errormess = '<div class="alert alert-danger" role="alert">
        Uh Oh! '.$database->get_error().'
        </div>';
        $this->haserror = 1;
        return FALSE;
    }
}

?>