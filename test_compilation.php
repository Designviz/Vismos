<?php
// Test file to demonstrate the new reverse compilation system

// Include the editor functions
require_once 'editorfunctions.php';

// Create a simple test document
$testDoc = new WebNodeDocument();

// Create nodes for a simple HTML document
// 1. Document Begin
$docBegin = new WebNode();
$docBegin->name = "HTML Document Begin";
$docBegin->templatepath = "Nodes/HTML/document.begin.php";
$docBegin->guid = "begin-guid";
$docBegin->outputs = [
    (object)["name" => "", "t" => 4, "currentval" => ""], // Flow pin
    (object)["name" => "string", "t" => 3, "currentval" => "<html>"] // String output
];

// 2. Document Body
$docBody = new WebNode();
$docBody->name = "HTML Body";
$docBody->templatepath = "Nodes/HTML/document.body.php";
$docBody->guid = "body-guid";
$docBody->inputs = [
    (object)["name" => "Content", "t" => 3, "currentval" => "", "connections" => []]
];
$docBody->outputs = [
    (object)["name" => "string", "t" => 3, "currentval" => "<body>Hello World</body>"]
];

// 3. Document Write (Final Output)
$docWrite = new WebNode();
$docWrite->name = "Write HTML Document";
$docWrite->templatepath = "Nodes/HTML/document.write.php";
$docWrite->guid = "write-guid";
$docWrite->inputs = [
    (object)["name" => "", "t" => 4, "currentval" => "", "connections" => []], // Flow pin
    (object)["name" => "Content", "t" => 3, "currentval" => "", "connections" => []]
];
$docWrite->outputs = [
    (object)["name" => "string", "t" => 3, "currentval" => ""]
];

// Add nodes to document
$testDoc->Nodes = [$docBegin, $docBody, $docWrite];
$testDoc->finalOutputNode = $docWrite;

echo "Testing Reverse Compilation System\n";
echo "==================================\n\n";

echo "Document has " . count($testDoc->Nodes) . " nodes:\n";
foreach ($testDoc->Nodes as $node) {
    echo "- " . $node->name . " (GUID: " . $node->guid . ")\n";
}

echo "\nFinal output node: " . $testDoc->finalOutputNode->name . "\n";

// Test the compilation
echo "\nStarting reverse compilation...\n";
$result = $testDoc->CompileNodeWithDependencies($testDoc->finalOutputNode);

if ($result) {
    echo "Compilation successful!\n";
    echo "Compiled nodes: " . implode(", ", $testDoc->compiledNodes) . "\n";
} else {
    echo "Compilation failed!\n";
}

echo "\nTest completed.\n";
?> 