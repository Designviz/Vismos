<?php
include("actions.php");
if(empty($_SESSION['id']))
{
    //echo "Session id: ".$_SESSION['id'];
    header('Location: https://vizmos.io/');
    exit();
} else {
    $gaction = $_GET["action"];
    switch($gaction)
    {
        case "EDIT":
            $edid = $_GET["id"];
            $_SESSION['docid'] = $edid;
            break;

        case "CREATE":
            $database->OpenConnection();
            $userdata = [
                'userid' => $_SESSION['id'],
                'doctype' => $_GET["doctype"]
            ];
            $added = $database->AddProject($userdata);
            $addedid = $database->get_lastAdded();
            $database->CloseConnection();
            if($added==TRUE)
            {
                header('Location: https://vizmos.io/editor.php?action=EDIT&id='.$addedid.'&doctype='.$_GET["doctype"]);
                exit();
            }else{
                header('Location: https://vizmos.io/?error='.$database->haserror.'&'.$database->errormess);
                exit();
            }
            break;
    }
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8"/>
    <title>Vizmos - Editor</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
    <script src="PixelFontCanvas.js"></script>
    <script src="nodeweb.js"></script>
</head>

<body style="width:100%; height:100%;">
    <canvas id="canvas" style="display:block;  height: 100vh; width: 100vw; "></canvas>
    <script type="text/javascript" language="javascript">
    Init();
    </script>
<div class="modal fade hide" id="newfuncModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">New Function</h3>           
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="functionname" class="control-label">Function Name</label>
                            <input type="text" class="form-control" id="functionname"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button class="btn btn-primary" data-dismiss="modal" onclick="CreateNewFunction()">Create</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade hide" id="savedocModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Save Document</h3>           
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="documentname" class="control-label">Document Name</label>
                            <input type="text" class="form-control" id="documentname"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button class="btn btn-primary" data-dismiss="modal" onclick="SaveMyDocument()">Save</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade hide" id="newvarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="myModalLabel">New Variable</h3>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body">
                <form>
                    <label for="varname" class="control-label">Variable Name</label>
                    <input type="text" class="form-control" id="varname"></textarea>
                    <label for="vartype" class="control-label">Variable Type</label>
                    <div class="form-group" id="form-vartype">
                        <select id="vartype" class="custom-select custom-select-lg mb-3">
                            <option>Int</option>
                            <option>Float</option>
                            <option>String</option>
                            <option>Bool</option>
                            <option>Vector2</option>
                            <option>Vector3</option>
                            <option>Vector4</option>
                            <option>Object</option>
                        </select>
                    </div>
                    <label for="contype" class="control-label">Data Container</label>
                    <div class="form-group" id="form-contype">
                        <select id="varcon" class="custom-select custom-select-lg mb-3">
                            <option>Single</option>
                            <option>Array</option>
                            <option>Map</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-primary" data-dismiss="modal" onclick="CreateNewVariable()">Create</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->    
<div class="modal fade hide" id="nodesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Add Node</h3>           
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div data-spy="scroll" data-target="#navbar-example2" data-offset="0">
                    <div id="nodescontent">
                        <p>Loading Node List, Please wait...</p> 
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>                 
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<div class="modal fade hide" id="launchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Launch</h3>           
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div id="launched" class="embed-responsive embed-responsive-16by9">
                    <div class="embed-responsive embed-responsive-21by9">
                        <iframe class="embed-responsive-item" src="Launch/empty.html" id="launchme" allowfullscreen></iframe>
                      </div>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>                   
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade hide" id="compileModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myCompileModalLabel">Compile</h3>           
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div id="compilehme" class="modal-body">

                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>                   
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<div class="modal fade hide" id="updateDefaultVal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="myModalLabel">Set Default Value</h3>           
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="valuenname" class="control-label" id="defaultValLabel">Value Name</label>
                            <input type="text" class="form-control" id="valueval" placeholder="The Value"/>
                            <input type="hidden" name="guid" id="guid"/>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
                    <button class="btn btn-primary" data-dismiss="modal" onclick="UpdateVarDefaults()">Update</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</body>
