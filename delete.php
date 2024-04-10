<?php 
require('autoload.php');
require('config.php');

if(isset($_SESSION['user']) && !empty($_SESSION['user'])) {

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {

        $id = filter_input(INPUT_GET,'id', FILTER_SANITIZE_NUMBER_INT); 
        
        if($id) {
            try {
                $products = new Products();
                return $products->delete($id);
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