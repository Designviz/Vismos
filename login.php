<?php
//make sure db was included int he script this is included in
class USERAUTH
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

    function signup($database)
    {

            $_first_name = $_last_name = $_email = $_username = $_password = $_birthday = $_birthmonth = $_birthyear = $_gender = "";
            $firstname      = $_POST["firstname"];
            $lastname       = $_POST["lastname"];
            $email          = $_POST["email"];
            $username       = $_POST["username"];
            $birthmonth     = $_POST["month"];
            $birthday       = $_POST["day"];
            $birthyear      = $_POST["year"];
            $gender         = $_POST["gender"];
            $password       = $_POST["password"];    
            $repassword       = $_POST["repassword"];  
            
            //PHP Validation
            if(!empty($firstname) && !empty($lastname) && !empty($email) && !empty($username) && !empty($password) && !empty($repassword) && !empty($gender) && !empty($birthday) && !empty($birthyear) && !empty($birthmonth))
            {
                if($database->EmailExists($email)>0)
                {
                    $this->errormess = '<div class="alert alert-danger" role="alert">
                    User with that email already exists!
                    </div>';
                    $this->haserror = 1;
                    return FALSE;
                } else {
                    if($database->UserExists($username)>0)
                    {
                        $this->errormess = '<div class="alert alert-danger" role="alert">
                        Bummer.. that username is already taken!
                        </div>';
                        $this->haserror = 1;
                        return FALSE;
                    } else {
                        //clean the data
                        $_first_name = mysqli_real_escape_string($database->$conn, $firstname);
                        $_last_name = mysqli_real_escape_string($database->$conn, $lastname);
                        $_email = mysqli_real_escape_string($database->$conn, $email);
                        $_username = mysqli_real_escape_string($database->$conn, $username);
                        $_password = mysqli_real_escape_string($database->$conn, $password);  
                        $_repassword = mysqli_real_escape_string($database->$conn, $repassword); 
                        $_gender = mysqli_real_escape_string($database->$conn, $gender); 
                        $_birthmonth = mysqli_real_escape_string($database->$conn, $birthmonth); 
                        $_birthday = mysqli_real_escape_string($database->$conn, $birthday); 
                        $_birthyear = mysqli_real_escape_string($database->$conn, $birthyear); 
                        //perform more validation
                        if(!preg_match("/^[a-zA-Z ]*$/", $_first_name)) {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Only letters and white space allowed in First Name.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;
                        }
                        if(!preg_match("/^[a-zA-Z ]*$/", $_last_name)) {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Only letters and white space allowed in Last Name.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;
                        }
                        if(!preg_match("/^[a-zA-Z ]*$/", $_username)) {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Only letters and white space allowed in Username.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;
                        }
                        if(!filter_var($_email, FILTER_SANITIZE_EMAIL)) {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Email: '.$_email.' is invalid.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;
                        }    
                        if(!preg_match("/^(?=.*\d)(?=.*[@#\-_$%^&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{6,20}$/", $_password)) {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Password should be between 6 to 20 charcters long, contains atleast one special chacter, lowercase, uppercase and a digit.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;
                        } 
                        if(strcmp($_password,$_repassword)!==0)
                        {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Passwords do not match.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;                           
                        }
                        // Generate random activation token
                        $token = md5(rand().time());   
                        $password_hash = password_hash($_password, PASSWORD_BCRYPT);     
                        $timenow = date('H:i:s');
                        //$btimestamp = strtotime($_birthyear.'-'.$_birthmonth.'-'.$_birthday.' '.$timenow);
                        //set a userdata object
                        $userdata = [
                            "firstname" => $_first_name,
                            "lastname" => $_last_name,
                            "email" => $_email,
                            "username" => $_username,
                            "userpass" => $password_hash,
                            "token" => $token,
                            "birth_date" => $_birthyear.'-'.$_birthmonth.'-'.$_birthday,
                            "gender" => $_gender
                        ];  

                        if($database->AddUser($userdata))
                        {
                            //send verification email
                            $msg = 'Hello '.$_first_name.',<br><br>Click on the activation link to verify your email. <br><br>
                            <a href="https://vizmos.io/?a=user_verification&token='.$token.'"> Click here to verify email</a>';

                            $to      = $_email;
                            $subject = "Vismos Verification email";
                            $header = "From:no-reply@vismos.io \r\n";
                            $header .= "MIME-Version: 1.0\r\n";
                            $header .= "Content-type: text/html\r\n";
                            
                            $retval = mail ($to,$subject,$msg,$header);


                            if( $retval == true ) {
                                $this->successmess = '<div class="alert alert-success" role="alert">
                                Verification email has been sent!
                                </div>';
                                $this->haserror = 0;
                                return TRUE;
                             }else {
                                $this->successmess = '<div class="alert alert-warning" role="alert">
                                Your account was created, however there was a problem sending a verification email to you.
                                </div>';
                                $this->haserror = 0;
                                return TRUE;
                             }

                        } else {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Could not add user.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;  
                        }

                    }
                }
            } else {
                $this->errormess = '<div class="alert alert-danger" role="alert">
                Missing Information.
                </div>';
                $this->haserror = 1;
                return FALSE;  
            }
        
    }

    function login($database)
    {
        $email_signin        = $_POST['email'];
        $password_signin     = $_POST['password'];
        $user_email = filter_var($email_signin, FILTER_SANITIZE_EMAIL);
        $pswd = mysqli_real_escape_string($database->$conn, $password_signin);
        if(!empty($email_signin) && !empty($password_signin))
        {
            $exists = $database->EmailExists($email_signin);
            if($exists <= 0) {
                $this->errormess = '<div class="alert alert-danger" role="alert">
                User account does not exist.
                </div>';
                $this->haserror = 1;
                return FALSE;  
            } else {
                $user = $database->GetUser($email_signin);
                if(!empty($user['username']))
                {
                    $password = password_verify($password_signin, $user['userpass']);
                    if($user['is_active'] == 1)
                    {
                        if($password)
                        {
                            $_SESSION['id'] = $user['id'];
                            $_SESSION['firstname'] = $user['firstname'];
                            $_SESSION['lastname'] = $user['lastname'];
                            $_SESSION['email'] = $user['email'];
                            $_SESSION['username'] = $user['username'];
                            $_SESSION['token'] = $user['token'];
                            $this->successmess = '<div class="alert alert-success" role="alert">
                            Welcome back '.$_SESSION['username'].'!
                            </div>
                            <script id="runscript">
                            setTimeout(location.reload.bind(location), 1000);
                            </script>';
                            $this->haserror = 0;
                            return TRUE;                            
                        }  else {
                            $this->errormess = '<div class="alert alert-danger" role="alert">
                            Incorrect email or password.
                            </div>';
                            $this->haserror = 1;
                            return FALSE;   
                        }                    
                    } else {
                        $this->errormess = '<div class="alert alert-danger" role="alert">
                        User account has not been activated.
                        </div>';
                        $this->haserror = 1;
                        return FALSE;   
                    }


                } else {
                    $this->errormess = '<div class="alert alert-danger" role="alert">
                    User account '.$user['username'].' does not exist 01.
                    </div>';
                    $this->haserror = 1;
                    return FALSE;                    
                }
            }          
        } else {
            $this->errormess = '<div class="alert alert-danger" role="alert">
            Missing Information.
            </div>';
            $this->haserror = 1;
            return FALSE;              
        }
        

    }

    function resetpass()
    {

    }

    function authemail($database,$token)
    {
        if(!empty($token)) {
            return $database->VerifyToken($token);
        }
        return FALSE;
    }
}
?>