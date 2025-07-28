<?php

class DB
{
    public $conn;
    public $successmess = 'Success';
    public $errormess = 'Error';
    public $haserror = 0;
    public $lastaddedID = 0;

    function get_lastAdded() {
        return $this->lastaddedID;
    }


    function get_error() {
        return $this->errormess;
    }

    function get_success() {
        return $this->successmess;
    }

    function OpenConnection()
    {
        $this->$conn = new mysqli($GLOBALS['servername'], $GLOBALS['username'], $GLOBALS['password'],$GLOBALS['databasename']);
        if ($this->$conn->connect_error) {
            die("Connection failed: " . $this->$conn->connect_error." ".$this->$conn->errno);
        }
        /*
        $this->$conn -> select_db($GLOBALS['databasename']);
        if ($this->$conn->errno) {
            if($this->$conn->errno==1044) {
                $this->CreateDatabase();
            }else {
                die("Database Select failed: " . $this->$conn->connect_error." ".$this->$conn->errno);
            }       
        }
        */
    }

    function CloseConnection()
    {
        $this->$conn->close();
    }

    function Install()
    {

            //$this->Install_Users();
            //$this->Install_Themes();
            //$this->Install_SiteOptions();
            //$this->Install_Posts();
            //$this->Install_Comments();
            //$this->Install_Projects();
    }


    function Install_SiteOptions()
    {
        $sql = "CREATE TABLE IF NOT EXISTS SiteOptions (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            optionkey VARCHAR(30) NOT NULL,
            optionvalue VARCHAR(50)
            )";  

        if ($this->$conn->query($sql) === TRUE) {
            echo "Table SiteOptions created successfully";
        } else {
            echo "Error creating table: " . $this->$conn->error;
        }      
    }

    function Install_Themes()
    {
        echo "Creating themes";
        $sql = "CREATE TABLE IF NOT EXISTS Themes (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            themename VARCHAR(30) NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";  
            
        if ($this->$conn->query($sql) === TRUE) {
            echo "Table Themes created successfully";
        } else {
            echo "Error creating table: " . $this->$conn->error;
        }
        echo "Finished";
    }

    function Install_Comments()
    {
           $sql = "CREATE TABLE IF NOT EXISTS Comments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            commentor INT NOT NULL,
            comment BLOB NOT NULL,
            media TEXT NOT NULL,
            commenttype TINYINT NOT NULL,
            visibility TEXT NOT NULL,
            commentmeta TEXT NOT NULL,
            commentpoints BIGINT NOT NULL,
            numreplies BIGINT NOT NULL,
            comment_date DATE NOT NULL
            )";

        if ($this->$conn->query($sql) === TRUE) {
            echo "Table Posts created successfully";
        } else {
            echo "Error creating table: " . $this->$conn->error;
        }       
    }

    function Install_Posts()
    {
          //$hash = password_hash($password, PASSWORD_DEFAULT);
          $sql = "CREATE TABLE IF NOT EXISTS Posts (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            originalposter INT NOT NULL,
            content TEXT NOT NULL,
            categories TEXT NOT NULL,
            media TEXT NOT NULL,
            posttype TINYINT NOT NULL,
            visibility TEXT NOT NULL,
            postmeta TEXT NOT NULL,
            postpoints BIGINT NOT NULL,
            numcomments BIGINT NOT NULL,
            numshares BIGINT NOT NULL,
            post_date DATE NOT NULL
            )";

        if ($this->$conn->query($sql) === TRUE) {
            echo "Table Posts created successfully";
        } else {
            echo "Error creating table: " . $this->$conn->error;
        }
      
    }

    function Install_Users()
    {
        //$hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "CREATE TABLE IF NOT EXISTS Users (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            firstname VARCHAR(50) NOT NULL,
            lastname VARCHAR(50) NOT NULL,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            userpass VARCHAR(255) NOT NULL,
            token VARCHAR(255) NOT NULL,
            gender VARCHAR(50) NOT NULL,
            is_active enum('0','1') NOT NULL,
            birth_date DATE NOT NULL,
            reg_date DATE NOT NULL
            )";

        if ($this->$conn->query($sql) === TRUE) {
            echo "Table Users created successfully";
        } else {
            echo "Error creating table: " . $this->$conn->error;
        }

    }

    function Install_Projects()
    {
        //$hash = password_hash($password, PASSWORD_DEFAULT);
        $sql = "CREATE TABLE IF NOT EXISTS Projects (
            id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            projname VARCHAR(255) NOT NULL,
            pfilename VARCHAR(255) NOT NULL,
            pthumbnail VARCHAR(255) NOT NULL,
            ownerid INT NOT NULL,
            pdescription TEXT NOT NULL,
            updated_date DATE NOT NULL,
            reg_date DATE NOT NULL
            )";

        if ($this->$conn->query($sql) === TRUE) {
            echo "Table Projects created successfully";
        } else {
            echo "Error creating table: " . $this->$conn->error;
        }

    }


    function TableExists($table) {
        $sql = "SHOW TABLES LIKE '".$table."'";
        $result = $this->$conn->query($sql);
        if($result != false) {
            return true;
        }
        return false;
    }


    function CreateDatabase()
    {
        $sql = "CREATE DATABASE IF NOT EXISTS ".$GLOBALS['databasename'];
        if (mysqli_query($this->$conn, $sql)) {
            echo "Database created successfully";
        } else {
            echo "Error creating database: "  . $this->$conn->connect_error." ".$this->$conn->errno;
        }
    }

    function EmailExists($email)
    {
        $sql = "SELECT * FROM Users WHERE email = '{$email}' ";
        $email_check_query = mysqli_query($this->$conn, $sql);
        return mysqli_num_rows($email_check_query);        
    }

    function UserExists($username)
    {
        $sql = "SELECT * FROM Users WHERE username = '{$username}' ";
        $email_check_query = mysqli_query($this->$conn, $sql);
        return mysqli_num_rows($email_check_query);        
    }

    function GetUser($email)
    {
        $sql = "SELECT * FROM Users WHERE email = '{$email}' ";
        $email_check_query = mysqli_query($this->$conn, $sql);
        while($row = mysqli_fetch_array($email_check_query)) {           
            return $row;
        }       
    }

    function AddUser($userdata)
    {
        $sql = "INSERT INTO Users (firstname, lastname, email, username, userpass, token, gender, is_active,
        birth_date, reg_date) VALUES ('".$userdata['firstname']."', '".$userdata['lastname']."', '".$userdata['email']."', '".$userdata['username']."', '".$userdata['userpass']."', '".$userdata['token']."', 
        '".$userdata['gender']."', '0', '".$userdata['birth_date']."',now())";
        
        // Create mysql query
        $sqlQuery = mysqli_query($this->$conn, $sql);
        
        if(!$sqlQuery){
            return FALSE;
        } 

        return TRUE;
    }

    function VerifyToken($token)
    {
        $sqlQuery = mysqli_query($this->$conn, "SELECT * FROM Users WHERE token = '$token' ");
        $countRow = mysqli_num_rows($sqlQuery);
        if($countRow == 1){
            while($rowData = mysqli_fetch_array($sqlQuery)){
                $is_active = $rowData['is_active'];
                  if($is_active == 0) {
                     $update = mysqli_query($this->$conn, "UPDATE Users SET is_active = '1' WHERE token = '$token' ");
                       if($update){
                            return TRUE;
                       }
                  } else {
                    return FALSE;
                  }
            }
        } else {
            return FALSE;
        }


    }

    function AddProject($postdata)
    {

        $sql = "INSERT INTO Projects (projname, ownerid, doctype, updated_date, reg_date) VALUES ('document','".$postdata['userid']."', '".$postdata['doctype']."', now(), now())";
                
        // Create mysql query
        $sqlQuery = mysqli_query($this->$conn, $sql);

        if(!$sqlQuery){
            $this->haserror = TRUE;
            $this->errormess = "Could not add Project: ".mysqli_error($this->$conn);
            return FALSE;
        } 

        $this->lastaddedID = mysqli_insert_id($this->$conn);
        return TRUE;     
    }

    function AddPost($postdata)
    {

        $sql = "INSERT INTO Posts (originalposter, content, posttype, visibility, post_date) VALUES ('".$postdata['userid']."', '".$postdata['content']."', '1', '{\"visibility\" : 1}',now())";
        
        // Create mysql query
        $sqlQuery = mysqli_query($this->$conn, $sql);
        
        if(!$sqlQuery){
            $this->errormess = "Could not add Spiel: ".mysqli_error($this->$conn);
            return FALSE;
        } 
        $this->lastaddedID = mysqli_insert_id($this->$conn);
        return TRUE;       
    }

    function GetPosts($postdata)
    {
        $sql = "SELECT * FROM Posts ORDER BY post_date LIMIT 30";
        $sqlQuery = mysqli_query($this->$conn, $sql);
        $countRow = mysqli_num_rows($sqlQuery);

        return mysqli_fetch_all($sqlQuery);
     
    }

    function GetProjects($postdata)
    {
        $id = $postdata['id'];
        
        $sql = "SELECT * FROM Projects WHERE ownerid = ".$id." ORDER BY updated_date LIMIT 28";
        $sqlQuery = mysqli_query($this->$conn, $sql);
        $countRow = mysqli_num_rows($sqlQuery);
        
        return mysqli_fetch_all($sqlQuery);
     
    }

    function GetProject($id)
    {
        $sql = "SELECT * FROM Projects WHERE id = ".$id;
        $sqlQuery = mysqli_query($this->$conn, $sql);
        $countRow = mysqli_num_rows($sqlQuery);
        
        if ($countRow > 0) {
          // output data of each row
            while($row = mysqli_fetch_array($sqlQuery)) {           
                return $row;
            } 
        } else {
          return NULL;
        }       
    }

    function UpdateProject($postdata)
    {
        $id = $postdata['id'];
        $oid = $postdata['owner'];
        $pfilename = $postdata['filename'];
        $pdocname = $postdata['docname'];
        $sqlQuery = mysqli_query($this->$conn, "SELECT * FROM Projects WHERE ownerid = '$oid' AND id = '$id' ");
        $countRow = mysqli_num_rows($sqlQuery);
        if($countRow == 1){
            while($rowData = mysqli_fetch_array($sqlQuery)){
                $is_active = $rowData['is_active'];
                $update = mysqli_query($this->$conn, "UPDATE Projects SET pfilename = '$pfilename', projname = '$pdocname' WHERE ownerid = '$oid' AND id = '$id' ");
                if($update)
                {
                    return TRUE;
                }
            }
        } else {
            return FALSE;
        }


    }


    function GetPost($id)
    {
        $sql = "SELECT * FROM Posts WHERE id = ".$id;
        $sqlQuery = mysqli_query($this->$conn, $sql);
        $countRow = mysqli_num_rows($sqlQuery);
        
        if ($countRow > 0) {
          // output data of each row
            while($row = mysqli_fetch_array($sqlQuery)) {           
                return $row;
            } 
        } else {
          return NULL;
        }       
    }
}

?>