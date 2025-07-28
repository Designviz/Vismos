<?php
include("actions.php");
?>
<!doctype html>
<html lang="en">
<head>
<title>Vizmos - Making Gizmos Easy!</title>
<?php
include("Theme/Main/header.php");
?>
</head>
<body class="text-center">

<div class="cover-container d-flex  p-3 mx-auto flex-column w-100"> 
<?php
include("Theme/Main/menu.php");




if(!empty($_SESSION['id']))
{
    include("Theme/Main/dashboard.php");
}else{
    include("Theme/Main/landing.php");
}

?>
<div class="fixed-bottom">
<?php
include("Theme/Main/footer.php");

?>
</div>
</div>
<?php
    if(empty($_SESSION['id']))
    {
        include("Theme/Main/modal.register.php");
    } else {
        include("Theme/Main/modal.newgraph.php");
    }

?>
</body>
</html>
<?php
include("close.php");
?>