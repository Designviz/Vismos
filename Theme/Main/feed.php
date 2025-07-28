
<div class="container-fluid align-content-center w-100 justify-content-center" style="margin-top: 50px !important;" id="MainContent">

    <div class="row w-100">  
        <div class="col">      
            <div class="card flex-fill w-100 p-3">
                <div class="card-body" >
                    <form id="spielit" action="" method="post" accept-charset="UTF-8">
                        <div id="spielPostEditor" class="ql-container ql-snow">

                        </div>
                        <div class="d-grid gap-2" id="spielMediaControl">
                            <div class="btn-group" role="group" aria-label="Insert Media">
                                <button type="button" class="btn btn-link"><i class="bi bi-link-45deg"></i></button>
                                <button type="button" class="btn btn-link"><i class="bi bi-card-image"></i></button>
                                <button type="button" class="btn btn-link"><i class="bi bi-file-play-fill"></i></button>
                                <button type="button" class="btn btn-link"><i class="bi bi-joystick"></i></button>
                            </div>                               
                        </div>
                        <div class="d-grid gap-2">
                            <textarea  id="mySpiel" type="hidden" name="mySpiel" style="display: none !important;">
                            </textarea>
                            <input type="hidden" name="action" value="post"/>
                            <button class="btn btn-spiel w-100" type="button" id="spielButton">Spiel It!</button>
                        </div>
                    </form>
                </div>  
            </div>
        </div>
    </div>
    <div class="row w-100">    
        <div class="col">       
            <div class="card flex-fill w-100 p-3">
                <div class="card-body" id="output">

                </div>
            </div>
        </div>    
    </div>   
    <?php
    $postquery = '';
    echo get_posts($postquery);
    ?>
</div>




