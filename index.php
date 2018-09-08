<?php
    include('xcrud/xcrud.php');
    $xcrud = Xcrud::get_instance();
    $xcrud->table('project');

?><!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>

</head>

<body>

<?php
    echo $xcrud->render();
?>
</body>
</html>