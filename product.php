<?php 
require('autoload.php');
require('config.php');

if($_SERVER['REQUEST_METHOD'] === 'GET') {

    $id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT); 

    if($id) {
        $products = new Products();
        return $products->get($id);
    }else {
        $array['error'] = 'Id not send';
        http_response_code(405);
    }

}else {
    $array['error'] = 'Method not allowed';
    http_response_code(405);
}

require('return.php');