<?php
include_once 'psl-config.php';  

$mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE);
if ($mysqli->connect_error) {
    header("Location: index.html?err=Unable to connect to MySQL");
    exit();
}