# Node-Based Compilation System

## Overview

The Vismos node editor uses a **reverse dependency resolution** compilation system that starts from the final output node and works backwards through the dependency graph to construct the final document.

## How It Works

### 1. Final Output Node Detection
The system automatically identifies the final output node (typically `document.write` or similar) by:
- Looking for nodes with names containing "write" or "output"
- Falling back to nodes with no output pins
- This node becomes the starting point for compilation

### 2. Reverse Dependency Resolution
Instead of following a linear forward path, the system:
- Starts from the final output node
- Recursively resolves all input dependencies
- Compiles each node only after its dependencies are satisfied
- Handles complex graphs with multiple paths and branches

### 3. Dependency Tracking
The system tracks:
- Which nodes have been compiled (prevents cycles)
- Input/output connections between nodes
- Data flow through the graph

## Key Improvements Over Original System

### Original System (Forward-Only)
```php
// Old approach - linear forward traversal
Entry Node → Node 1 → Node 2 → Final Node
```

**Problems:**
- Only follows `nextNode` connections
- Can't handle complex dependency graphs
- Limited to single execution path
- Doesn't properly resolve data dependencies

### New System (Reverse Dependency Resolution)
```php
// New approach - starts from final node, works backwards
Final Node ← Node 2 ← Node 1 ← Entry Node
```

**Benefits:**
- Handles complex dependency graphs
- Multiple execution paths
- Proper data dependency resolution
- Cycle detection and prevention
- More flexible and powerful

## Node Types and Pins

### Pin Types
- **Type 3 (STRING)**: Data pins that carry actual content
- **Type 4 (FLOW)**: Control flow pins that determine execution order

### Node Structure
```php
class WebNode {
    public $inputs = [];    // Input pins (data + flow)
    public $outputs = [];   // Output pins (data + flow)
    public $templatepath;   // PHP template file to execute
    public $guid;          // Unique identifier
}
```

## Compilation Process

### 1. Find Final Output Node
```php
function FindFinalOutputNode() {
    // Look for document.write or similar
    foreach ($this->Nodes as $node) {
        if (strpos($node->name, "write") !== false) {
            $this->finalOutputNode = $node;
            return;
        }
    }
}
```

### 2. Recursive Dependency Resolution
```php
function CompileNodeWithDependencies($node) {
    // Check if already compiled (prevent cycles)
    if (in_array($node->guid, $this->compiledNodes)) {
        return true;
    }
    
    // Compile all input dependencies first
    foreach ($node->inputs as $input) {
        if ($input->t != 4) { // Skip flow pins
            foreach ($input->connections as $connection) {
                $sourceNode = $connection->B->parent;
                $this->CompileNodeWithDependencies($sourceNode);
                $input->currentval = $connection->B->currentval;
            }
        }
    }
    
    // Now compile this node
    $node->Compile();
    $this->compiledNodes[] = $node->guid;
    
    return true;
}
```

### 3. Template Execution
Each node calls its PHP template file:
```php
function PerformTemplate($url, $req) {
    // Send input data to template
    // Template processes data and returns output
    // Output is stored in node's output pins
}
```

## Example: HTML Document Compilation

### Node Graph
```
[Document Begin] → [Document Body] → [Document Write]
     <html>           <body>content</body>    Final HTML
```

### Compilation Steps
1. **Start**: Find `Document Write` as final output node
2. **Dependency**: `Document Write` needs content from `Document Body`
3. **Dependency**: `Document Body` needs content from `Document Begin`
4. **Compile**: `Document Begin` → outputs `<html>`
5. **Compile**: `Document Body` → outputs `<body>content</body>`
6. **Compile**: `Document Write` → outputs final HTML document

## Usage

### In the Editor
1. Create your node graph with connections
2. Ensure you have a final output node (like `document.write`)
3. Click "Compile" button
4. System automatically resolves dependencies and generates output

### Programmatically
```php
$doc = new WebNodeDocument();
$doc->FromJSON($jsonData);
$doc->CompileReverse($filename, $launch);
```

## Error Handling

The system handles various error conditions:
- **No final output node**: Warns user to add a document.write node
- **Dependency cycles**: Detects and prevents infinite loops
- **Missing dependencies**: Reports compilation failures
- **Template errors**: Handles template execution failures

## Benefits

1. **Flexibility**: Can handle complex node graphs with multiple paths
2. **Reliability**: Proper dependency resolution prevents errors
3. **Scalability**: Can handle large projects with many nodes
4. **Maintainability**: Clear separation of concerns
5. **Extensibility**: Easy to add new node types and templates

## Future Enhancements

- **Parallel compilation**: Compile independent branches simultaneously
- **Caching**: Cache compiled results for better performance
- **Incremental compilation**: Only recompile changed nodes
- **Visual debugging**: Show compilation order and dependencies 