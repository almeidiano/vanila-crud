<?php 
    class Products {
        private $pdo;

        public function __construct() {
            $database = new Database();
            $this->pdo = $database->getPDO();
        }

        public function all() {
            $sql = $this->pdo->query("SELECT * FROM pumps");

            if($sql->rowCount() > 0) { 
                $data = $sql->fetchAll(PDO::FETCH_ASSOC);
        
                forEach($data as $item) {
                    $array['result'][] = [
                        'id'=> $item['id'],
                        'image'=> $item['image'],
                        'name' => $item['name'],
                        'description' => $item['description']
                    ];
                }

                require('return.php');
            }
        }

        public function create($name, $description) {
            $supportedTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml', 'image/gif'];
            $isImage = str_contains($_FILES['image']['type'], 'image');
            $isValidType = false;
        
            if($isImage) {                 
                if(in_array($_FILES['image']['type'], $supportedTypes)) {
                    $isValidType = true;
                }
        
                if( $isValidType ) {
                    if($_FILES['image']['size'] >= 3000000) {
                        $array['error'] = 'Image too large';
                        http_response_code(413); 
                        require('return.php');
                    }
    
                    $originalFileName = $_FILES['image']['name'];
                    $fileNameExt = explode('.', $originalFileName);
                    $ext = end($fileNameExt);
    
                    $newImageName = $_FILES['image']['name'] = md5(time()).".".$ext;
                    move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$newImageName);

                    $sql = $this->pdo->prepare("INSERT INTO pumps (image, name, description) VALUES (:image, :name, :description)");
                    $sql->bindValue("image", $newImageName, PDO::PARAM_STR);
                    $sql->bindValue("name", $name, PDO::PARAM_STR);
                    $sql->bindValue("description", $description, PDO::PARAM_STR);
                    $sql->execute();

                    $id = $this->pdo->lastInsertId();

                    if($id) {
                        $array['result'] = [
                            'id' => $id,
                            'image' => $newImageName,
                            'name' => $name,
                            'description' => $description
                        ];

                        http_response_code(201);
                        require('return.php');
                    }else {
                        $array['error'] = 'Product not inserted';
                        http_response_code(500);
                    }

                }else {
                    $array['error'] = 'Image type not supported';
                    http_response_code(406); 
                    require('return.php');
                }
            }else {
                $array['error'] = 'File type not allowed';
                http_response_code(406);
                require('return.php');
            }
        }

        public function get($id) {
            $sql = $this->pdo->prepare("SELECT * FROM pumps WHERE id = :id");
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();
    
            if($sql->rowCount() > 0) { 
                $data = $sql->fetch(PDO::FETCH_ASSOC);
        
                $array['result'] = [
                    'id'=> $data['id'],
                    'image'=> $data['image'],
                    'name' => $data['name'],
                    'description' => $data['description']
                ];

                require('return.php');
            }else {
                $array['error'] = 'Product not found';
                http_response_code(404);
            }
        }

        public function update($id, $name, $description) {
            $sql = $this->pdo->prepare("SELECT * FROM pumps WHERE id = :id");
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();
    
            if($sql->rowCount() > 0) { 
                $data = $sql->fetch(PDO::FETCH_ASSOC);
        
                $supportedTypes = ['image/png', 'image/jpeg', 'image/webp', 'image/svg+xml', 'image/gif'];
                $isImage = str_contains($_FILES['image']['type'], 'image');
                $isValidType = false;
            
                if($isImage) {                 
                    if(in_array($_FILES['image']['type'], $supportedTypes)) {
                        $isValidType = true;
                    }
            
                    if( $isValidType ) {
                        if($_FILES['image']['size'] >= 3000000) {
                            $array['error'] = 'Image too large';
                            http_response_code(413); 
                            require('return.php');
                        }
        
                        $originalFileName = $_FILES['image']['name'];
                        $fileNameExt = explode('.', $originalFileName);
                        $ext = end($fileNameExt);
        
                        $newImageName = $_FILES['image']['name'] = md5(time()).".".$ext;
                        move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/'.$newImageName);

                        $sql = $this->pdo->prepare("UPDATE pumps SET image = :image, name = :name, description = :description WHERE id = :id");
                        $sql->bindValue(":id", $id, PDO::PARAM_STR);
                        $sql->bindValue(":image", $newImageName, PDO::PARAM_STR);
                        $sql->bindValue(":name", $name, PDO::PARAM_STR);
                        $sql->bindValue(":description", $description, PDO::PARAM_STR);
                        $sql->execute();

                        $array['result'] = [
                            'id' => $id,
                            'image' => $newImageName,
                            'name' => $name,
                            'description' => $description
                        ];

                        http_response_code(200);
                        require('return.php');
                    }else {
                        $array['error'] = 'Image type not supported';
                        http_response_code(406); 
                        require('return.php');
                    }
                }else {
                    $array['error'] = 'File type not allowed';
                    http_response_code(406);
                    require('return.php');
                }
            }else {
                $array['error'] = 'Product not found';
                http_response_code(404);
                require('return.php');
            }
        }

        public function delete($id) {
            $sql = $this->pdo->prepare("DELETE FROM pumps WHERE id = :id");
            $sql->bindValue(":id", $id, PDO::PARAM_INT);
            $sql->execute();

            $array['result'] = "Success";
            require('return.php');
        }
    }