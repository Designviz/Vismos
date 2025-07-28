<?php

    class NodeRequest
    {
        public $inputs = [];
        public $outputs = [];
    }

    $data = $_POST['req'];
    $nr = json_decode($data);
    //input 0 on a flow node is the flow pin - ignore it
    $res = new NodeRequest;
    $res->inputs = $nr->inputs;
    $val = $nr->inputs[1].'</html>';
    
    array_push($res->outputs,'');
    array_push($res->outputs,$val);

    header('Content-Type: application/json');
    echo json_encode($res);
?>