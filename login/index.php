<?php 
require('../autoload.php');

if($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = filter_input(INPUT_POST,'email', FILTER_SANITIZE_STRING);
    $password = filter_input(INPUT_POST,'password', FILTER_SANITIZE_STRING);
    
    if($email && $password) {

        try {
            $login = new Login();
            
            if($login->login($email, $password)) { 
                $array['result'] = "Success";
                require('../return.php');
            }
                
        } catch (\Throwable $th) {
            $array['error'] = 'Error: '.$th->getMessage();
            http_response_code(500);
            require('../return.php');
        }
    }else {
        $array['error'] = 'Body not send';
        http_response_code(401);
    }

}else {
    $array['error'] = 'Method not allowed';
    http_response_code(405);
}

require('../return.php');