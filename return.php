<?php 
    include_once __DIR__ . '/vendor/autoload.php';

    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");

    echo json_encode($array);
    exit;