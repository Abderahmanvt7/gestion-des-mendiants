<?php

    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'gestion_des_mendiants';

    $connection = new mysqli($host, $username, $password, $database);

    if ($connection->connect_error) { 
        trigger_error($connection->connect_error);
        exit();
    }

?>