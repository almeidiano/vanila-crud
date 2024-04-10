<?php 
    require('database.php');

    class Login {
        private $pdo;

        public function login($email, $password) {
            $database = new Database();
            $this->pdo = $database->getPDO();

            $sql = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
            $sql->bindValue(":email", $email, PDO::PARAM_STR);
            $sql->execute();

            if($sql->rowCount() > 0) { 
                $data = $sql->fetch(PDO::FETCH_ASSOC);

                $matchPassword = password_verify($password, $data['password']);

                if($matchPassword) {
                    unset($_SESSION['loginAttempt']);
                    $_SESSION['user'] = $data;
                    return true;
                } else {
                    return $this->sessionError();
                }
            }else {
                return $this->sessionError();
            }
        }

        private function isBlocked($ip) {
            $database = new Database();
            $this->pdo = $database->getPDO();

            $sql = $this->pdo->prepare("SELECT * FROM blocked_ips WHERE ip = :ip");
            $sql->bindValue(":ip", $ip);
            $sql->execute();

            return $sql->rowCount() > 0 ? true : false;
        }

        private function block($ip) {
            $database = new Database();
            $this->pdo = $database->getPDO();

            $sql = $this->pdo->prepare("INSERT INTO blocked_ips SET ip = :ip");
            $sql->bindValue(":ip", $ip);
            $sql->execute();

            $array['error'] = 'Blocked Access';
            http_response_code(403);
            require('../return.php');
        }

        private function sessionError() {

            $ip = $_SERVER['REMOTE_ADDR'];

            if(!$this->isBlocked($ip)) {
                if(isset($_SESSION['loginAttempt']) && !empty($_SESSION['loginAttempt'])) {
                    if($_SESSION['loginAttempt'] >= 10) {
    
                        $ip = $_SERVER['REMOTE_ADDR'];
                        return $this->block($ip);
                        
                    }else $_SESSION['loginAttempt'] ++;
                }else {
                    $_SESSION['loginAttempt'] = 1;
                }
            }else {
                $array['error'] = 'Blocked Access';
                http_response_code(403);
                require('../return.php'); 
            }

            $array['error'] = 'Wrong email or password';
            $array['result'] = $_SESSION['loginAttempt'];
            http_response_code(401);
            require('../return.php');
        }
    }