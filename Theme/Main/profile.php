<div class="container-fluid align-content-center w-100 justify-content-center" style="margin-top: 50px !important;" id="MainContent">
    <div class="row w-100">  
        <div class="col">   
            <div class="card flex-fill w-100 p-3">
                <div class="card-body">
                    <h5 class="card-title text-center mb-4">User Profile</h5>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $_SESSION['username']; ?></h6>
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo $_SESSION['firstname']; ?>
                        <?php echo $_SESSION['lastname']; ?></h6>
                    <p class="card-text">Email address: <?php echo $_SESSION['email']; ?></p>                     
                    <a class="btn btn-danger btn-block" href="logout.php">Log out</a>
                </div>
            </div>
        </div>    
    </div> 
</div>