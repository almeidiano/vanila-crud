<?php 
require('autoload.php');
require('config.php');

if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {

    if($_SERVER['REQUEST_METHOD'] === 'POST') {

        $name = filter_input(INPUT_POST,'name', FILTER_SANITIZE_STRING);
        $description = filter_input(INPUT_POST,'description', FILTER_SANITIZE_STRING);
        $id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT); 
        
        if($id && $name && $description) {
            try {
                $products = new Products();
                return $products->update($id, $name, $description);
            } catch (\Throwable $th) {
                $array['error'] = 'Error: '.$th->getMessage();
                http_response_code(500);
                require('return.php');
            }
        }else {
            $array['error'] = 'Body not send';
            http_response_code(401);
        }

    }else {
        $array['error'] = 'Method not allowed';
        http_response_code(405);
    }
    
}else {
    $array['error'] = 'Forbidden';
    http_response_code(403);
}

require('return.php');