<?php
$mysqli = new mysqli();
$mysqli->connect('localhost', 'root', '', 'flippy_bizlister');

//Hostname - normalmente 'localhost'
//DBusername - seu usuário MySQL (no XAMPP costuma ser 'root')
//DBpassword - sua senha MySQL (no XAMPP padrão é vazia '')
//DBname - nome do banco criado (ex.: 'flippy_bizlister')

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
    exit;
}
?>
