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
            $id = $_POST["id"];
            $database->OpenConnection();
            $projdata = $database->GetProject($id);
            $filename = $projdata[2];
            $database->CloseConnection();

            // Check if file exists and has content
            if (file_exists($filename) && filesize($filename) > 0) {
                $myfile = fopen($filename, "r");
                $webdoc = fread($myfile, filesize($filename));
                fclose($myfile);
                echo $webdoc;
            } else {
                // Return a valid empty document structure
                $emptyDoc = [
                    'name' => 'New Document',
                    'guid' => uniqid(),
                    'Nodes' => [],
                    'Functions' => [],
                    'Variabes' => []
                ];
                echo json_encode($emptyDoc);
            }
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
            try {
                // Debug: Log what we're receiving
                error_log("Compile request received. POST data: " . print_r($_POST, true));
                
                if (!isset($_POST["doc"])) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'alert-danger', 'message' => 'No document data received']);
                    exit();
                }
                
                $obj = json_decode($_POST["doc"], false);
                if ($obj === null) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'alert-danger', 'message' => 'Invalid JSON data: ' . json_last_error_msg()]);
                    exit();
                }
                
                error_log("JSON decoded successfully. Nodes count: " . (isset($obj->Nodes) ? count($obj->Nodes) : 0));
                
                $wd = new WebNodeDocument;
                $wd->FromJSON($obj);
                
                error_log("Document loaded. Final node: " . ($wd->finalOutputNode ? $wd->finalOutputNode->name : 'null'));
                
                // Add more detailed logging
                error_log("Starting compilation...");
                $wd->CompileReverse($_POST["dir"],FALSE);
                error_log("Compilation completed successfully");
            } catch (Exception $e) {
                error_log("Compilation error: " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine());
                error_log("Stack trace: " . $e->getTraceAsString());
                header('Content-Type: application/json');
                echo json_encode(['status' => 'alert-danger', 'message' => 'Compilation error: ' . $e->getMessage()]);
            } catch (Error $e) {
                error_log("Fatal error: " . $e->getMessage() . " in " . $e->getFile() . " line " . $e->getLine());
                error_log("Stack trace: " . $e->getTraceAsString());
                header('Content-Type: application/json');
                echo json_encode(['status' => 'alert-danger', 'message' => 'Fatal error: ' . $e->getMessage()]);
            }
            exit();
        break;

        case "launch-document":
            try {
                $obj = json_decode($_POST["doc"], false);
                if ($obj === null) {
                    header('Content-Type: application/json');
                    echo json_encode(['status' => 'alert-danger', 'message' => 'Invalid JSON data']);
                    exit();
                }
                $wd = new WebNodeDocument;
                $wd->FromJSON($obj);
                $wd->CompileReverse($_POST["dir"],TRUE);
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode(['status' => 'alert-danger', 'message' => 'Launch error: ' . $e->getMessage()]);
            }
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
            $this->guid = $json->guid;           
            $this->A = $this->parent;
            //Perform this after all vars have been loaded
            //store B GUID
            $this->bguid = $json->B;
            //$this->B = $this->FindWebVar($json->B->guid);
        }

        function FindWebVar()
        {
            //A is parent - need to find B
            //go up to main webdoc
            $doc = $this->parent->parent->parent;
            if($doc!=null)
            {
                //echo "Find Webvar";
                $this->B = $doc->FindWebVar($this->bguid);
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
            if(isset($this->connections[$i]) && $this->connections[$i] != null)
            {
                if($this->connections[$i]->t != 4)
                {
                    //need previous parents to compile to get final output
                    if(isset($this->connections[$i]->B) && isset($this->connections[$i]->B->parent)) {
                        $this->connections[$i]->B->parent->Compile();
                        $this->currentval = $this->connections[$i]->B->currentval;
                    }
                }
            }
            //perform node action on value

        }


        function AttachConnections()
        {
            if(isset($this->connections) && is_array($this->connections)) {
                for ($i=0; $i < sizeof($this->connections); $i++) { 
                    if(isset($this->connections[$i])) {
                        $this->connections[$i]->FindWebVar();
                    }
                }
            }          
        }

        function FromJSON($json)
        {
            $this->name = isset($json->name) ? $json->name : '';
            $this->t = isset($json->t) ? $json->t : 0;
            $this->containerType = isset($json->containerType) ? $json->containerType : 0;
            $this->val = isset($json->val) ? $json->val : '';
            $this->isInput = isset($json->isInput) ? $json->isInput : false;
            $this->defaultval = isset($json->defaultval) ? $json->defaultval : '';
            $this->currentval = isset($json->currentval) ? $json->currentval : '';
            $this->guid = isset($json->guid) ? $json->guid : '';

            if(isset($json->connections) && is_array($json->connections)) {
                for ($i=0; $i < sizeof($json->connections); $i++) { 

                    $wp = new WebPinConnection;
                    $wp->parent = $this;
                    $wp->FromJSON($json->connections[$i]);
                    array_push($this->connections,$wp);
                }
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
                if(isset($this->inputs[$i]) && $this->inputs[$i]->t!=4)
                {
                    //need the final values before we can compile node.
                    //does our input have any connections?
                    //if(sizeof($this->inputs[$i]->connections)>0)
                    //{
                        //for inputs there should only be one connection.
                    $this->inputs[$i]->GetFinalVal($i);
                    //}
                    
                }
                if(isset($this->inputs[$i])) {
                    array_push($req->inputs,$this->inputs[$i]->currentval);
                } else {
                    array_push($req->inputs,'');
                }
            }
            $templateUrl = 'https://vizmos.io/'.$this->templatepath;
            error_log("Compiling node: " . $this->name . " with template: " . $templateUrl);
            $res = $this->PerformTemplate($templateUrl,$req);
            //echo "CURL: ".$res;
            //update values
            //once all input vals are set we can set output values
            if (isset($res->outputs) && is_array($res->outputs)) {
                for ($i=0; $i < sizeof($res->outputs); $i++) { 
                    # code...
                    if (isset($this->outputs[$i])) {
                        $this->outputs[$i]->currentval = $res->outputs[$i];

                        if($this->outputs[$i]->t==4)
                        {
                            if(isset($this->outputs[$i]->connections) && sizeof($this->outputs[$i]->connections)>0)
                            {
                                if(isset($this->outputs[$i]->connections[0]->B) && isset($this->outputs[$i]->connections[0]->B->parent)) {
                                    $this->nextNode = $this->outputs[$i]->connections[0]->B->parent;
                                    //echo "Set Next Node: ".$this->outputs[$i]->connections[0]->B->parent->name."\n";
                                }
                            }
                        }
                    }
                }
            }

            //echo "Compile finished for: ".$this->name."\n";
            // Return the compiled output instead of empty string
            $output = '';
            if (isset($this->outputs) && is_array($this->outputs)) {
                foreach ($this->outputs as $output_pin) {
                    if ($output_pin->t != 4) { // Skip flow pins
                        $output .= $output_pin->currentval;
                    }
                }
            }
            return $output;
            
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
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($result === false || $httpCode !== 200) {
                // Return a default response if template fails
                $defaultResponse = new stdClass();
                $defaultResponse->outputs = [];
                return $defaultResponse;
            }
            
            $decoded = json_decode($result);
            if ($decoded === null) {
                // Return a default response if JSON decode fails
                $defaultResponse = new stdClass();
                $defaultResponse->outputs = [];
                return $defaultResponse;
            }
            
            return $decoded;
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
            $this->name = isset($json->name) ? $json->name : '';
            $this->x = isset($json->x) ? $json->x : 0;
            $this->y = isset($json->y) ? $json->y : 0;
            $this->w = isset($json->w) ? $json->w : 200;
            $this->h = isset($json->h) ? $json->h : 100;
            $this->templatepath = isset($json->templatepath) ? $json->templatepath : '';
            $this->radius = isset($json->radius) ? $json->radius : 20;
            $this->fontsize = isset($json->fontsize) ? $json->fontsize : 12;
            $this->guid = isset($json->guid) ? $json->guid : '';

            if(isset($json->inputs) && is_array($json->inputs)) {
                for ($i=0; $i < sizeof($json->inputs); $i++) { 
                    $wv = new WebVar;
                    $wv->parent = $this;
                    $wv->FromJSON($json->inputs[$i]);
                    array_push($this->inputs,$wv);
                }
            }

            if(isset($json->outputs) && is_array($json->outputs)) {
                for ($i=0; $i < sizeof($json->outputs); $i++) { 
                    $wv = new WebVar;
                    $wv->parent = $this;
                    $wv->FromJSON($json->outputs[$i]);
                    array_push($this->outputs,$wv);
                }
            }


        }

        function Reattach()
        {
            //attach all webvars connections
            if(isset($this->inputs) && is_array($this->inputs)) {
                for ($i=0; $i < sizeof($this->inputs); $i++) { 
                    if(isset($this->inputs[$i])) {
                        $this->inputs[$i]->AttachConnections();
                    }
                }
            }

            if(isset($this->outputs) && is_array($this->outputs)) {
                for ($i=0; $i < sizeof($this->outputs); $i++) { 
                    if(isset($this->outputs[$i])) {
                        $this->outputs[$i]->AttachConnections();
                    }
                }
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
        public $compiledNodes = []; // Track which nodes have been compiled
        public $finalOutputNode = null; // The final output node (like document.write)


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

        // New improved compilation system
        function CompileReverse($launchFile, $launch)
        {
            $webdat = new WebData;
            $this->compiledNodes = []; // Reset compiled nodes tracking
            
            // Find the final output node (document.write or similar)
            $this->FindFinalOutputNode();
            
            if ($this->finalOutputNode == null) {
                $webdat->status = 'alert-warning';
                $webdat->message = 'No final output node found! Add a document.write node.';
                header('Content-Type: application/json');
                echo json_encode($webdat);
                return;
            }
            
            // Compile starting from the final node, working backwards
            $result = $this->CompileNodeWithDependencies($this->finalOutputNode);
            
            if ($result === false) {
                $webdat->status = 'alert-danger';
                $webdat->message = 'Compilation failed due to dependency issues.';
                header('Content-Type: application/json');
                echo json_encode($webdat);
                return;
            }
            
            // Get the final output from the final node's compilation
            $finalOutput = $this->finalOutputNode->Compile();
            
            if ($launch == TRUE) {
                $filename = $launchFile.'_'.$_SESSION['username'].'_'.md5(rand().time()).'_Launch.html';
                $myfile = fopen("Launch/".$filename, "w");  
                fwrite($myfile, $finalOutput);
                fclose($myfile);
                echo $filename;
            } else {
                if (empty($finalOutput)) {
                    $webdat->status = 'alert-success';
                    $webdat->message = 'Compiled Successfully!';
                } else {
                    $webdat->status = 'alert-info';
                    $webdat->message = 'Compiled Successfully! Output: ' . substr($finalOutput, 0, 100) . (strlen($finalOutput) > 100 ? '...' : '');
                }
                header('Content-Type: application/json');
                echo json_encode($webdat);
            }
        }
        
        // Find the final output node (document.write or similar)
        function FindFinalOutputNode()
        {
            foreach ($this->Nodes as $node) {
                if ($node->name == "Write HTML Document" || 
                    strpos($node->name, "write") !== false ||
                    strpos($node->name, "output") !== false) {
                    $this->finalOutputNode = $node;
                    return;
                }
            }
            
            // If no specific output node found, use the last node with no outputs
            foreach ($this->Nodes as $node) {
                if (isset($node->outputs) && sizeof($node->outputs) == 0) {
                    $this->finalOutputNode = $node;
                    return;
                }
            }
            
            // If still no final node found, use the first node as fallback
            if (sizeof($this->Nodes) > 0) {
                $this->finalOutputNode = $this->Nodes[0];
            }
        }
        
        // Compile a node and all its dependencies recursively
        function CompileNodeWithDependencies($node)
        {
            try {
                // Check if already compiled to avoid cycles
                if (in_array($node->guid, $this->compiledNodes)) {
                    return true;
                }
                
                error_log("Compiling node: " . $node->name . " (GUID: " . $node->guid . ")");
                
                // Compile all input dependencies first
                foreach ($node->inputs as $input) {
                    if ($input->t != 4) { // Skip flow pins
                        if (isset($input->connections) && is_array($input->connections)) {
                            foreach ($input->connections as $connection) {
                                if (isset($connection->B) && isset($connection->B->parent)) {
                                    $sourceNode = $connection->B->parent;
                                    if (!$this->CompileNodeWithDependencies($sourceNode)) {
                                        error_log("Failed to compile dependency: " . $sourceNode->name);
                                        return false;
                                    }
                                    $input->currentval = $connection->B->currentval;
                                }
                            }
                        }
                    }
                }
                
                // Now compile this node
                $result = $node->Compile();
                error_log("Node " . $node->name . " compiled successfully. Output length: " . strlen($result));
                
                // Mark as compiled
                $this->compiledNodes[] = $node->guid;
                
                return true;
            } catch (Exception $e) {
                error_log("Error compiling node " . $node->name . ": " . $e->getMessage());
                return false;
            } catch (Error $e) {
                error_log("Fatal error compiling node " . $node->name . ": " . $e->getMessage());
                return false;
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
            $this->name = isset($json->name) ? $json->name : '';
            $this->guid = isset($json->guid) ? $json->guid : '';

            if(isset($json->Nodes)) {
                for ($i=0; $i < sizeof($json->Nodes); $i++) { 
                    # code...
                    $wn = new WebNode;
                    $wn->parent = $this;
                    $wn->FromJSON($json->Nodes[$i]);
                    if(isset($json->Entrynode) && $json->Entrynode->guid==$wn->guid)
                    {
                        $this->Entrynode = $wn;
                    }
                    array_push($this->Nodes,$wn);
                }
            }

            if(isset($json->Variabes)) {
                for ($i=0; $i < sizeof($json->Variabes); $i++) { 
                    $wv = new WebVar;
                    //$wv->parent = $this;
                    $wv->FromJSON($json->Variabes[$i]);
                    array_push($this->Variabes,$wv);
                }
            }

            if(isset($json->Functions)) {
                for ($i=0; $i < sizeof($json->Functions); $i++) { 
                    # code...
                    $wf = new WebNodeFunction;
                    $wf->FromJSON($json->Functions[$i]);
                    array_push($this->Functions,$wf);
                }
            }

            if(isset($this->Nodes)) {
                for ($i=0; $i < sizeof($this->Nodes); $i++) { 
                    $this->Nodes[$i]->Reattach();
                }
            }
        }
    }


    class WebNodeFunction extends WebNodeDocument
    {

    }
?>