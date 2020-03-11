<?php
$host = "localhost";
$base = "BlogArt";
$user = "root";
$pass = "root";

try {
    $conn = new PDO("mysql:host=$host;dbname=$base;charset=utf8", $user, $pass);
}
    catch(PDOException $e) {
    echo "Impossible de se connecter!";
}

?>