<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "grc";

$dbh;
$error;

$dsn = 'mysql:host=' . $host . ';dbname=' . $dbname;
$options = array (
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION 
);

try {
    $dbh = new PDO ($dsn, $user, $pass, $options);
}	
catch ( PDOException $e ) {
    $error = $e->getMessage();
}

?>