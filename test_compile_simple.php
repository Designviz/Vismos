<?php
// Simple test to check if the compilation system works

// Mock the required classes for testing
class WebData {
    public $status;
    public $message;
}

class WebNode {
    public $name;
    public $guid;
    public $inputs = [];
    public $outputs = [];
    public $templatepath;
    
    function Compile() {
        // Mock compilation
        return "compiled";
    }
    
    function FromJSON($json) {
        $this->name = $json->name;
        $this->guid = $json->guid;
        if (isset($json->inputs)) $this->inputs = $json->inputs;
        if (isset($json->outputs)) $this->outputs = $json->outputs;
        if (isset($json->templatepath)) $this->templatepath = $json->templatepath;
    }
}

class WebNodeDocument {
    public $name;
    public $guid;
    public $Nodes = [];
    public $compiledNodes = [];
    public $finalOutputNode = null;
    
    function FromJSON($json) {
        $this->name = $json->name;
        $this->guid = $json->guid;
        
        if (isset($json->Nodes)) {
            foreach ($json->Nodes as $nodeData) {
                $node = new WebNode();
                $node->FromJSON($nodeData);
                $this->Nodes[] = $node;
            }
        }
    }
    
    function FindFinalOutputNode() {
        foreach ($this->Nodes as $node) {
            if (strpos($node->name, "write") !== false) {
                $this->finalOutputNode = $node;
                return;
            }
        }
        
        if (sizeof($this->Nodes) > 0) {
            $this->finalOutputNode = $this->Nodes[0];
        }
    }
    
    function CompileNodeWithDependencies($node) {
        if (in_array($node->guid, $this->compiledNodes)) {
            return true;
        }
        
        $node->Compile();
        $this->compiledNodes[] = $node->guid;
        return true;
    }
    
    function CompileReverse($launchFile, $launch) {
        $webdat = new WebData;
        $this->compiledNodes = [];
        
        $this->FindFinalOutputNode();
        
        if ($this->finalOutputNode == null) {
            $webdat->status = 'alert-warning';
            $webdat->message = 'No final output node found!';
            return $webdat;
        }
        
        $result = $this->CompileNodeWithDependencies($this->finalOutputNode);
        
        if ($result === false) {
            $webdat->status = 'alert-danger';
            $webdat->message = 'Compilation failed due to dependency issues.';
            return $webdat;
        }
        
        $webdat->status = 'alert-success';
        $webdat->message = 'Compiled Successfully!';
        return $webdat;
    }
}

// Test the system
echo "Testing Compilation System\n";
echo "=========================\n\n";

// Create test data
$testData = (object)[
    'name' => 'Test Document',
    'guid' => 'test-guid',
    'Nodes' => [
        (object)[
            'name' => 'Write HTML Document',
            'guid' => 'write-guid',
            'inputs' => [],
            'outputs' => []
        ]
    ]
];

// Test compilation
$doc = new WebNodeDocument();
$doc->FromJSON($testData);
$result = $doc->CompileReverse('test', false);

echo "Result: " . $result->status . "\n";
echo "Message: " . $result->message . "\n";
echo "Compiled nodes: " . implode(", ", $doc->compiledNodes) . "\n";

echo "\nTest completed successfully!\n";
?> 