<?php

function connect(){
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'meditrack';

    $con = new mysqli($host, $user, $password,$database) or die("Connect failed: %s\n". $con -> error);

    return $con;
}

function closeConnection($con){
    $con->close();
}

?>