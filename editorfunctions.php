<?php
    include("actions.php");
    $jsonheader = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
    $gaction = $_GET["action"];
    $postaction = $_POST["postaction"];


    switch($gaction)
    {


        case "load-nodes":
            //$filteredDir = array_slice( array_diff( scandir( "/Nodes" ), array( '..', '.', '.DS_Store' ) ), 0 );
            //header('Content-Type: application/json');
            //$f = getDirContents('Nodes');
            $path = $_GET["dir"];
            GetNodeList($path);
            //echo json_encode(getDirContents('Nodes'));  
            exit();
        break;

        case "load-node":
            $path = $_GET["path"];
            $myfile = fopen($path .".json", "r") or die("Unable to open file!");
            header("Content-type: application/json; charset=utf-8");
            echo fread($myfile,filesize($path .".json"));
            fclose($myfile);
            exit();
        break;

        default:



    }
    switch($postaction)
    {
        case "load-document":
            $myfile = '';
            $id = $_POST["id"];
            $database->OpenConnection();
            $projdata = $database->GetProject($id);
            $filename = $projdata[2];
            
            $database->CloseConnection();

             //fopen("proj.txt", "r") or $myfile = fopen("proj.txt", "w");
            $myfile = fopen($filename, "r") or $myfile = fopen($filename, "w");
            $webdoc = fread($myfile,filesize($filename));

            
            echo $webdoc;

            fclose($myfile);
            exit();
        break;

        case "save-document":
            $filename = 'Documents/'.$_POST["dir"].'_'.$_SESSION['username'].'_'.md5(rand().time()).'.json';
            $myfile = fopen($filename, "w");           
            $obj = json_decode($_POST["doc"], false);
            $obj->name = $_POST["dir"];
            fwrite($myfile, json_encode($obj));
            $postdata['id'] = $_SESSION['docid'];
            $postdata['owner'] = $_SESSION['id'];
            $postdata['filename'] = $filename;
            $postdata['docname'] = $_POST["dir"];
            $database->OpenConnection();
            $database->UpdateProject($postdata);
            $database->CloseConnection();
            echo $obj->name.' Saved';
            exit();
        break;

        case "save-node":
            $myfile = fopen($_POST["dir"].'.json', "w");
            $obj = json_decode($_POST["node"], false);
            fwrite($myfile, json_encode($obj));
        break;

        case "compile-document":
            $obj = json_decode($_POST["doc"], false);
            $wd = new WebNodeDocument;
            $wd->FromJSON($obj);
            $wd->Compile($_POST["dir"],FALSE);
            exit();
        break;

        case "launch-document":
            $obj = json_decode($_POST["doc"], false);
            $wd = new WebNodeDocument;
            $wd->FromJSON($obj);
            $wd->Compile($_POST["dir"],TRUE);
            exit();
        break;

        default:
    }

    function GrabNode($f)
    {
        $content =  file_get_contents($f);
        return json_decode($content);
    }

    function GetNodeList($dir)
    {
        if ($handle = opendir($dir)) {
            //echo "Directory handle: $handle\n";
            //echo "Entries:\n";
            echo '<nav aria-label="breadcrumb">
            <ol class="breadcrumb">';

            $bc = explode('/',$dir);
            for ($i=0; $i < sizeof($bc); $i++) { 
                # code...
                if($i==sizeof($bc)-1)
                {
                    echo '<li class="breadcrumb-item active" aria-current="page">'.$bc[$i].'</li>';
                } else {
                    echo '<li class="breadcrumb-item" aria-current="page"><a href="#" onclick="AddNodeFunction('."'".$bc[$i]."'".')">'.$bc[$i].'</a></li>';
                }
            }
            echo '  </ol>
            </nav>';

            echo '<div class="list-group">';
            /* This is the correct way to loop over the directory. */
            while (false !== ($entry = readdir($handle))) {

                if($entry!='.' && $entry!='..')
                {
                    if(is_dir($dir.'/'.$entry))
                    {
                        $fc = glob($dir.'/'.$entry.'/*.json');
                        echo '<button type="button" class="list-group-item d-flex justify-content-between align-items-center list-group-item-action" onclick="AddNodeFunction('."'".$dir.'/'.$entry."'".')">'.$entry.'<span class="badge badge-primary badge-pill">'.sizeof($fc).'</span></button>';

                    } 
                }
       
            }
            $fc = glob($dir.'/*.json');
            for ($i=0; $i < sizeof($fc); $i++) { 
                $n = GrabNode($fc[$i]);
                echo '<button type="button" class="list-group-item list-group-item-action" data-toggle="tooltip" data-html="true" data-placement="top" title="'.$n->description.'" onclick="WebNodeUI.MainDoc.AddWebNode('."'".$dir.'/'.basename($fc[$i], '.json')."'".');" data-dismiss="modal">'.$n->name.'</button>';
            }
            echo '</div>'; 
            /* This is the WRONG way to loop over the directory. */

            closedir($handle);
        }

    }

    function getDirContents($dir, &$results = array()) {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        $files = array(); 
        
        foreach ($rii as $file) {
        
            if ($file->isDir()){ 
                continue;
            }
            $files[] = $file->getPathname(); 
        
        }
    
        return $files;
    }

    class WebData
    {
        public $status;
        public $message;
    }

    class WebPinConnection 
    {
        public $t;
        public $A;
        public $B;
        public $guid;
        public $bguid;
        public $parent;

        function Compile()
        {
            return $this->B->Compile();
        }

        function FromJSON($json)
        {
            $this->t = $json->t;
            $this->guid = $json->$guid;           
            $this->A = $this->parent;
            //Perform this after all vars have been loaded
            //store B GUID
            $this->$bguid = $json->B;
            //$this->B = $this->FindWebVar($json->B->$guid);
        }

        function FindWebVar()
        {
            //A is parent - need to find B
            //go up to main webdoc
            $doc = $this->parent->parent->parent;
            if($doc!=null)
            {
                //echo "Find Webvar";
                $this->B = $doc->FindWebVar($this->$bguid);
            }
            return null;
            
        }
    }


    class WebVar
    {
        public $name;
        public $t;
        public $containerType;
        public $x = 0;
        public $y = 0;
        public $connections=[];
        public $parent;
        public $val;
        public $isInput = false;
        public $defaultval;
        public $currentval;
        public $guid;


        function GetFinalVal($i)
        {
            $this->currentval = $this->defaultval;
                //ignore if flow node
            if($this->connections[$i]!=null)
            {
                if($this->connections[$i]->t!=4)
                {
                    //need previous parents to compile to get final output
                    $this->connections[$i]->B->parent->Compile();
                    $this->currentval = $this->connections[$i]->B->currentval;
                }
            }
            //perform node action on value

        }


        function AttachConnections()
        {
            for ($i=0; $i < sizeof($this->connections); $i++) { 

                $this->connections[$i]->FindWebVar();
            }          
        }

        function FromJSON($json)
        {
            $this->name = $json->name;
            $this->t = $json->t;
            $this->containerType = $json->containerType;
            $this->val = $json->val;
            $this->isInput = $json->isInput;
            $this->defaultval = $json->defaultval;
            $this->currentval = $json->currentval;
            $this->guid = $json->guid;

            for ($i=0; $i < sizeof($json->connections); $i++) { 

                $wp = new WebPinConnection;
                $wp->parent = $this;
                $wp->FromJSON($json->connections[$i]);
                array_push($this->connections,$wp);
            }
        }
    }

    class NodeRequest
    {
        public $inputs = [];
        public $outputs = [];
    }

    class WebNode
    {
        public $name;
        public $x;
        public $y;
        public $w;
        public $h;
        public $t;
        public $templatepath;
        public $context;
        public $inputs=[];
        public $outputs=[];
        public $radius = 20;
        public $fontsize = 12;
        public $g;
        public $selectedinputPin = -1;
        public $selectedoutputPin = -1;
        public $guid;
        public $parent;
        public $nextNode = null;

        function Compile()
        {
            //get input values
            //echo "Begin Compile for: ".$this->name."\n";
            

            $req = new NodeRequest;
            for ($i=0; $i < sizeof($this->inputs); $i++) { 
                # code...
                if($this->inputs[$i]->t!=4)
                {
                    //need the final values before we can compile node.
                    //does our input have any connections?
                    //if(sizeof($this->inputs[$i]->connections)>0)
                    //{
                        //for inputs there should only be one connection.
                    $this->inputs[$i]->GetFinalVal(0);
                    //}
                    
                }
                array_push($req->inputs,$this->inputs[$i]->currentval);
            }
            $res = $this->PerformTemplate('https://vizmos.io/'.$this->templatepath,$req);
            //echo "CURL: ".$res;
            //update values
            //once all input vals are set we can set output values
            for ($i=0; $i < sizeof($res->outputs); $i++) { 
                # code...

                $this->outputs[$i]->currentval = $res->outputs[$i];

                if($this->outputs[$i]->t==4)
                {
                    if(sizeof($this->outputs[$i]->connections)>0)
                    {
                        $this->nextNode = $this->outputs[$i]->connections[0]->B->parent;
                        //echo "Set Next Node: ".$this->outputs[$i]->connections[0]->B->parent->name."\n";
                    }
                }
            }

            //echo "Compile finished for: ".$this->name."\n";
            return '';
            
        }

        function PerformTemplate($url,$req)
        {
            $fields = array(
                'req' => json_encode($req)
             );
            $postvars = http_build_query($fields);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            return json_decode($result);
        }

        function FindWebVar($guid)
        {
            for ($i=0; $i < sizeof($this->inputs); $i++) { 
                if($this->inputs[$i]->guid==$guid)
                {
                    //echo "Found Webvar Input for ".$this->name."\n";
                    return $this->inputs[$i];
                }
            }

            for ($i=0; $i < sizeof($this->outputs); $i++) { 
                if($this->outputs[$i]->guid==$guid)
                {
                    //echo "Found Webvar Output for ".$this->name."\n";
                    return $this->outputs[$i];
                }
            }  
            return null;          
        }

        function FromJSON($json)
        {
            $this->name = $json->name;
            $this->x = $json->x;
            $this->y = $json->y;
            $this->w = $json->w;
            $this->h = $json->h;
            $this->templatepath = $json->templatepath;
            $this->radius = $json->radius;
            $this->fontsize = $json->fontsize;
            $this->guid = $json->guid;

            for ($i=0; $i < sizeof($json->inputs); $i++) { 
                $wv = new WebVar;
                $wv->parent = $this;
                $wv->FromJSON($json->inputs[$i]);
                array_push($this->inputs,$wv);
            }

            for ($i=0; $i < sizeof($json->outputs); $i++) { 
                $wv = new WebVar;
                $wv->parent = $this;
                $wv->FromJSON($json->outputs[$i]);
                array_push($this->outputs,$wv);
            }


        }

        function Reattach()
        {
            //attach all webvars connections
            for ($i=0; $i < sizeof($this->inputs); $i++) { 
                $this->inputs[$i]->AttachConnections();
            }

            for ($i=0; $i < sizeof($this->outputs); $i++) { 
                $this->outputs[$i]->AttachConnections();
            }
        }

    }

    class WebNodeDocument
    {
        public $canvas;
        public $context;
        public $name;
        public $Nodes=[];
        public $Functions=[];
        public $Variabes=[];
        public $Entrynode;
        public $guid;


        function Compile($launchFile,$launch)
        {
            //add vars

            //add functions

            //do main code
            $webdat = new WebData;

            $nextNode = null;
            $lastNode = null;
            if($this->Entrynode!=null)
            {
                $webdat->message = $this->Entrynode->Compile();
                $nextNode = $this->Entrynode->nextNode;
                $lastnode = $nextNode;
            }else{
                //echo "Entry Node was Null";
                $webdat->message = 'Entry Node was Null';
            }
            
            while ($nextNode!=null) {
                $webdat->message .= $nextNode->Compile();

                if($nextNode->nextNode==null)
                {
                    $lastNode = $nextNode;
                }
                $nextNode = $nextNode->nextNode;
            }
            //echo 'Try to save';
            if($lastnode!=null)
            {
                //make sure last node is compiled
                $webdat->message .= $lastnode->Compile();
                //echo $lastnode->outputs[1]->currentval;
                //print_r($lastnode->outputs);
                if(empty($webdat->message))
                {
                    $webdat->status = 'alert-success';
                    $webdat->message = 'Compiled Successfully!';
                } else {
                    $webdat->status = 'alert-warning';
                }
                if($launch==TRUE)
                {
                    $filename = $launchFile.'_'.$_SESSION['username'].'_'.md5(rand().time()).'_Launch.html';
                    //echo 'Launch file: '.$filename;
                    $myfile = fopen("Launch/".$filename, "w");  
                    fwrite($myfile, $lastnode->outputs[1]->currentval);
                    fclose($myfile);
                    echo $filename;
                } else {
                    header('Content-Type: application/json');
                    echo json_encode($webdat);
                }

                
            } else {
                $webdat->status = 'alert-warning';
                $webdat->message = 'No Nodes connected to Start!';
                header('Content-Type: application/json');
                echo json_encode($webdat);
            }


        }



        function FindWebVar($guid)
        {
            for ($i=0; $i < sizeof($this->Nodes); $i++) { 
                $wv = $this->Nodes[$i]->FindWebVar($guid);
                if($wv!=null)
                {
                    return $wv;
                }
            }    
            return null;        
        }

        function FromJSON($json)
        {
            $this->name = $json->name;
            $this->guid = $json->guid;

            for ($i=0; $i < sizeof($json->Nodes); $i++) { 
                # code...
                $wn = new WebNode;
                $wn->parent = $this;
                $wn->FromJSON($json->Nodes[$i]);
                if($json->Entrynode->guid==$wn->guid)
                {
                    $this->Entrynode = $wn;
                }
                array_push($this->Nodes,$wn);
            }

            for ($i=0; $i < sizeof($json->Variabes); $i++) { 
                $wv = new WebVar;
                //$wv->parent = $this;
                $wv->FromJSON($json->Variabes[$i]);
                array_push($this->Variabes,$wv);
            }

            for ($i=0; $i < sizeof($json->Functions); $i++) { 
                # code...
                $wf = new WebNodeFunction;
                $wf->FromJSON($json->Functions[$i]);
                array_push($this->Functions,$wf);
            }

            
            for ($i=0; $i < sizeof($this->Nodes); $i++) { 
                $this->Nodes[$i]->Reattach();
            }
        }
    }


    class WebNodeFunction extends WebNodeDocument
    {

    }
?>