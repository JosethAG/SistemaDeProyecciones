<?php 
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "gestion_datos";

$conn = new mysqli($servername, $username, $password, $dbname);

if($conn->connect_error){
    die("Conexion fallida.");
}