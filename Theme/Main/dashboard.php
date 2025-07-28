
<main role="main" class="inner cover">
    <div class="container-fluid w-100" style="margin-top: 80px !important;" id="MainContent">
        <div class="row w-100">  
            <div class="col">      
                <div class="card">
                    <div class="card-body" style="text-align: left !important;">
                    <h5 class="card-title">Projects</h5>
                        <div class="container">
                            <div class="row align-items-center w-100 p-3">
                                <div class="col w-100">
                                    <div class="card h-100 w-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">New Project</h5>
                                            <p class="card-text">Create a new Vizmo using our visual editor!</p>
                                            <span class="align-bottom"><button type="button" class="btn btn-block btn-primary" data-bs-toggle="modal" data-bs-target="#createGraphModal" id="CreateGraphButton">Create</button></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $postquery['id'] = $_SESSION['id'];
                            echo get_projects($postquery);
                            ?>
                            <div class="row row-cols-4 align-items-center w-100 p-3">
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">New Project</h5>
                                            <p class="card-text">Create a new Vizmo using our visual editor!</p>
                                            <span class="align-bottom"><button type="button" class="btn btn-block btn-primary" data-bs-toggle="modal" data-bs-target="#createGraphModal" id="CreateGraphButton">Create</button></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">My Project</h5>
                                            <p class="card-text">Edit Project using our visual editor!</p>
                                            <span class="align-bottom"><a href="#" class="btn btn-block btn-primary">Open</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">My Project</h5>
                                            <p class="card-text">Edit Project using our visual editor!</p>
                                            <span class="align-bottom"><a href="#" class="btn btn-block btn-primary">Open</a></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col w-25">
                                    <div class="card h-100 d-inline-block">
                                        <div class="card-body">
                                            <h5 class="card-title">My Project</h5>
                                            <p class="card-text">Edit Project using our visual editor!</p>
                                            <span class="align-bottom"><a href="#" class="btn btn-block btn-primary">Open</a></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            
            </div>
        </div>
    </div>    
</main>    


