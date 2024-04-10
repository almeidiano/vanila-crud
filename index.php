<?php 
require('autoload.php');
require('config.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {
    $products = new Products();
    return $products->all();
}else {
    $array['error'] = 'Method not allowed';
    http_response_code(405);
}

require('return.php');