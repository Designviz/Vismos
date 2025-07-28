# Vismos - Visual Programming Platform

## Overview

Vismos is a PHP-based web application that provides a visual programming environment for creating web applications, documents, and interactive content through a node-based interface. Users can connect "nodes" (representing operations, functions, or content) via "pins" (inputs/outputs) to create complex programs without traditional coding.

## 🚀 Key Features

- **Visual Programming**: Drag-and-drop node-based programming interface
- **Multiple Language Support**: HTML, JavaScript, Python, Java, C#, Arduino, Go, Node.js, Phaser, Three.js, and more
- **Real-time Compilation**: Reverse dependency resolution system for efficient code generation
- **User Management**: Registration, authentication, and project management
- **Cloud Storage**: Save and load projects from the cloud
- **Responsive Design**: Bootstrap-based modern UI

## 🏗️ System Architecture

### Core Components

#### 1. **Frontend (Client-Side)**
- **`nodeweb.js`**: Main JavaScript engine handling UI interactions, node management, and connection logic
- **`editor.php`**: Main editor interface with canvas-based node editor
- **`PixelFontCanvas.js`**: Custom font rendering system for node labels

#### 2. **Backend (Server-Side)**
- **`editorfunctions.php`**: AJAX handlers for loading/saving documents and compilation
- **`db.php`**: Database operations and user/project management
- **`config.php`**: Configuration and session management

#### 3. **Node System**
- **`Nodes/`**: Directory containing node templates for different programming languages
- **PHP Templates**: Each node type has a corresponding PHP template that processes inputs and generates outputs
- **JSON Configuration**: Node definitions stored as JSON files with metadata

### Data Structures

#### WebNode
Represents a single node in the visual graph:
```javascript
class WebNode {
    guid;           // Unique identifier
    name;           // Node name
    inputs;         // Array of input pins (WebVar objects)
    outputs;        // Array of output pins (WebVar objects)
    templatepath;   // PHP template file path
    position;       // Visual position on canvas
}
```

#### WebVar (Pin)
Represents an input or output pin on a node:
```javascript
class WebVar {
    name;           // Pin name
    type;           // Data type (string, number, etc.)
    connections;    // Array of WebPinConnection objects
    currentval;     // Current value/data
    isInput;        // Boolean indicating input/output
    guid;           // Unique identifier
}
```

#### WebPinConnection
Represents a connection between two pins:
```javascript
class WebPinConnection {
    guid;           // Unique identifier
    A;              // Source pin (WebVar)
    B;              // Target pin (WebVar)
}
```

## 🔄 Compilation System

### Reverse Dependency Resolution

Vismos uses a sophisticated reverse dependency resolution system that:

1. **Identifies Final Output Node**: Automatically finds the final output node (e.g., `document.write`)
2. **Resolves Dependencies**: Works backwards through the dependency graph
3. **Compiles Nodes**: Only compiles nodes after their dependencies are satisfied
4. **Handles Complex Graphs**: Supports multiple execution paths and branches

### Compilation Process

```php
// Example: HTML Document Creation
[Document Begin] → [Document Body] → [Document Write]
     <html>           <body>content</body>    Final HTML
```

1. System finds `Document Write` as final output node
2. Resolves dependency: `Document Write` needs content from `Document Body`
3. Resolves dependency: `Document Body` needs content from `Document Begin`
4. Compiles `Document Begin` → outputs `<html>`
5. Compiles `Document Body` → outputs `<body>content</body>`
6. Compiles `Document Write` → outputs final HTML document

## 📁 Project Structure

```
Vismos/
├── index.php              # Main entry point
├── editor.php             # Editor interface
├── config.php             # Configuration
├── db.php                 # Database operations
├── editorfunctions.php    # AJAX handlers
├── nodeweb.js            # Main JavaScript engine
├── PixelFontCanvas.js    # Font rendering
├── Nodes/                # Node templates by language
│   ├── HTML/            # HTML node templates
│   ├── JS/              # JavaScript node templates
│   ├── PYTHON/          # Python node templates
│   └── ...              # Other language templates
├── Documents/            # User project storage
├── Theme/                # UI theme and styling
├── Media/                # Media assets
└── Lib/                  # External libraries
```

## 🎯 Supported Languages & Technologies

### Web Technologies
- **HTML**: Document structure, elements, and content
- **JavaScript**: Client-side scripting and interactivity
- **CSS**: Styling and layout (via nodes)

### Programming Languages
- **Python**: General programming and data processing
- **Java**: Object-oriented programming
- **C#**: .NET development
- **Arduino**: Embedded systems and IoT
- **Go**: Systems programming
- **Node.js**: Server-side JavaScript

### Game & Graphics
- **Phaser**: 2D game development
- **Three.js**: 3D graphics and WebGL
- **Canvas**: Custom graphics and animations

## 🛠️ Installation & Setup

### Prerequisites
- PHP 7.4 or higher
- MySQL/MariaDB database
- Web server (Apache/Nginx)
- HTTPS enabled (required by config)

### Installation Steps

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yourusername/vismos.git
   cd vismos
   ```

2. **Configure Database**
   - Update `config.php` with your database credentials
   - Ensure HTTPS is properly configured

3. **Set Up Database Tables**
   - Run the application to auto-create tables
   - Or manually execute the SQL from `db.php`

4. **Configure Web Server**
   - Point document root to the Vismos directory
   - Ensure PHP has write permissions for `Documents/` directory

5. **Access the Application**
   - Navigate to your domain
   - Register a new account
   - Start creating visual programs!

## 🎮 Usage Guide

### Creating Your First Project

1. **Register/Login**: Create an account or log in
2. **Create New Project**: Click "New Graph" and select document type
3. **Add Nodes**: Drag nodes from the palette to the canvas
4. **Connect Nodes**: Click and drag from output pins to input pins
5. **Configure Nodes**: Double-click nodes to modify properties
6. **Compile**: Click the compile button to generate output
7. **Save**: Save your project to the cloud

### Node Types

#### HTML Nodes
- **Document Begin**: Creates `<html>` structure
- **Document Body**: Creates `<body>` with content
- **Document Write**: Final output node for HTML
- **Add String**: Adds text content
- **Add Canvas**: Embeds canvas elements

#### JavaScript Nodes
- **Add JS Tag**: Embeds JavaScript code
- **Function**: Creates JavaScript functions
- **Variable**: Declares variables

#### Control Flow
- **Flow Control**: Manages execution order
- **Conditional**: If/else logic
- **Loops**: For/while loops

## 🔧 Development

### Adding New Node Types

1. **Create JSON Definition**
   ```json
   {
     "name": "My Custom Node",
     "inputs": [
       {"name": "input1", "type": "string"}
     ],
     "outputs": [
       {"name": "output1", "type": "string"}
     ],
     "templatepath": "Nodes/CUSTOM/mycustomnode.php"
   }
   ```

2. **Create PHP Template**
   ```php
   <?php
   class NodeRequest {
       public $inputs = [];
       public $outputs = [];
   }
   
   $data = $_POST['req'];
   $nr = json_decode($data);
   
   $res = new NodeRequest;
   $res->inputs = $nr->inputs;
   
   // Process inputs and generate outputs
   $output = processInputs($nr->inputs);
   array_push($res->outputs, $output);
   
   header('Content-Type: application/json');
   echo json_encode($res);
   ?>
   ```

3. **Add to Node Palette**: Update the node loading system to include your new node type

### Customizing the UI

The application uses Bootstrap for styling. Key files:
- `Theme/Main/main.css`: Custom styles
- `Theme/Main/header.php`: Header template
- `Theme/Main/footer.php`: Footer template

## 🐛 Troubleshooting

### Common Issues

1. **Connection Lines Broken**
   - Clear browser cache
   - Check for JavaScript errors in console
   - Verify `nodeweb.js` is loading correctly

2. **Compilation Errors**
   - Ensure all required input pins are connected
   - Check PHP error logs for template issues
   - Verify node templates are accessible

3. **Save/Load Issues**
   - Check database connectivity
   - Verify file permissions on `Documents/` directory
   - Check PHP session configuration

### Debug Mode

Enable debug mode by adding to `config.php`:
```php
$GLOBALS['debug'] = true;
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### Development Guidelines

- Follow existing code style
- Add comments for complex logic
- Test with multiple node types
- Update documentation for new features

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- Bootstrap for the UI framework
- jQuery for JavaScript utilities
- All contributors and users of Vismos

## 📞 Support

- **Issues**: Report bugs via GitHub Issues
- **Documentation**: Check the `COMPILATION_SYSTEM.md` for technical details
- **Community**: Join discussions in the project repository

---

**Vismos - Making Visual Programming Easy!** 🎯
