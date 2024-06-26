<?php
$xhost = 'localhost';
$uname = 'root';
$xpw = '';
$xdbname = 'classroom';
$xcnstr = "mysql:host=$xhost;dbname=$xdbname;charset=utf8";
$xopt = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true, PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");

try {
    // Create a PDO instance
    $link_id = new PDO($xcnstr, $uname, $xpw, $xopt);
    // Set the PDO error mode to exception
    $link_id->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connection successful"; // Connection successful message
} catch (PDOException $e) {
    // Display error message if connection fails
    echo "Connection failed: " . $e->getMessage();
}

require_once("include/lx.pdodb.php");
?>
