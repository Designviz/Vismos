


<main role="main" class="inner cover">
    <div class="container-fluid w-100">
        <div class="d-flex align-content-center flex-wrap">
            <div class="container px-lg-5">
                <div class="row mx-lg-n5">
                    <div class="col"></div>
                </div>

                <div class="row mx-lg-n5 d-none d-lg-block" style="margin-top: 80px; height: 150px;">
                    <div class="col"><h1>Why Write Code Anymore?</h1></div>
                </div>
                <div class="row mx-lg-n5 d-none d-lg-block" style="height: 80px;">
                    <div class="col"><h3>With our tools you can craft code for countless projects without writing a single line!</h3></div>
                </div>
                <div class="d-none d-lg-block">
                <div class="row align-items-center mx-lg-n5">
                    
                    <div class="col-8"><img src="Theme/Main/image/logo.png" class="img-fluid w-75" alt="Responsive image" />  </div>
                    
                    <div class="col-4 ">
                        <ul class="list-group bg-spiel">
                            <li class="list-group-item bg-spiel">
                            <form method="post" action="" id="LargeLoginForm">
                                <div class="form-group" id="LargeLoginInfoBox">
                                <?php echo $authmessage; ?>
                                </div>
                                <div class="form-group">
                                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                                    <small id="emailHelp" class="form-text text-muted-spiel">We'll never share your email with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                                </div>
                                <div class="form-group form-check">
                                    <a href="#">Forgot Password?</a>
                                </div>
                                <input type="hidden" name="action" value="login"/>
                                <button type="button" id="submitloginLarge" class="btn btn-spiel btn-lg btn-block">Login</button>
                            </form>
                            </li>
                            <li class="list-group-item bg-spiel">
                            <button type="button" class="btn btn-lg btn-block btn-spiel" data-toggle="modal" data-target="#SignupModal" id="SignUpButtonLG">Sign Up</button>
                            </li>
                        </ul>
                    </div>
                </div>
                </div>

            </div>
        </div>    
    </div>
    </main>
    <div class="d-lg-none w-100">

    <ul class="list-group">
    <li class="list-group-item">
        <h3>Making Gizmos Easy!</h3>
    </li>   
    <li class="list-group-item">
        <img src="Theme/Main/image/logo.png" class="img-fluid w-25" alt="Responsive image" />     
    </li>
    <li class="list-group-item">
        <form method="post" action="" id="MobileLoginForm">
        <div class="form-group" id="MobileLoginInfoBox">
            <?php echo $authmessage; ?>
        </div>
        <div class="form-group">
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Email">
                <small id="emailHelp" class="form-text text-muted-spiel" style="font-size: 0.6rem;">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <input type="password" name="password"  class="form-control" id="exampleInputPassword1" placeholder="Password" >
            </div>
            <div class="form-group form-check">
                <a href="#" style="font-size: 0.8rem;">Forgot Password?</a>
            </div>
            <input type="hidden" name="action" value="login"/>
            <button type="submit" name="button" id="submitloginMobile" class="btn btn-primary btn-sm btn-block">Login</button>
        </form>
    </li>
    <li class="list-group-item">                          
    <button type="button" class="btn btn-sm btn-secondary btn-block" data-toggle="modal" data-target="#SignupModal" id="SignUpButtonM">Sign Up</button>
    </li>
    </ul>

    </div>



