<?php

    require_once "Database.php";

    class User extends Database{

        public function store($request){  //store method

                //request for the data
                $first_name = $request['first_name'];
                $last_name = $request['last_name'];
                $username = $request['username'];
                $password = $request['password'];
                
                $password = password_hash($password, PASSWORD_DEFAULT);
                # admin12345 -----> aiw5887(8*7$23)...

                # Quert string
                $sql = "INSERT INTO users(`first_name`, `last_name`, `username`,`password`) 
                VALUES('$first_name', '$last_name', '$username', '$password')";


                #Execute the query
                if ($this->conn->query($sql)) {
                    header('location: ../views'); //go to index.php(login page)
                    exit(); //same as die()
                }else {
                    die("Error in creating the user: " . $this->conn->error);
                }


            }

        public function login($request){
            $username = $request['username'];
            $password = $request['password'];



            $sql = "SELECT * FROM users WHERE username = '$username'";

            $result = $this->conn->query($sql);


            #Check for the username

            if ($result->num_rows == 1) {
                #check for the password
                $user = $result->fetch_assoc(); //retrieved everything from the row
                #$user = [ 'id' => 1, 'username'=> 'john', 'password'=>xxxxxx....] all data for user willbe read


                if(password_verify($password, $user['password'])) {
                        //if same create the sesstion variables for future use
                    session_start();
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['first_name'] . " " . $user['last_name'];

                    header('location: ../views/dashboard.php'); //main dashboard ... will create this later on
                    exit();
                }else {
                    die("Password is incorrect.");
                }
            }else{
                die("username does not exits.");
            }
        }

        #logout
        public function logout(){;
            session_start();  //start the session
            session_unset();    //unset the session
            session_destroy(); //delete or destroy the sesstion

            header('location: ../views'); // redirect the user to the login page 
            exit;
        }
        # Get or retrieved all the users from the users table

        public function getAllUsers(){
            # Query string
            $sql = "SELECT id, first_name, last_name, username, photo FROM users";

            #Execute the query
            if ($result = $this->conn->query($sql)) {
                return $result;
            }else{
                die("Error retrieveing users . " . $this->conn->error);
            }
        }
        
        #get or retrieved a specific user (the user to edit)
        public function getUser($id){
            $sql =  "SELECT * FROM users WHERE id = $id";

            if($result = $this->conn->query($sql)) {
                return $result->fetch_assoc();
            }else{
                die("Error in retrieving the user . " . $this->conn->error);
            }


        }
    

        public function update($request, $files){
            session_start();
            $id = $_SESSION['id'];

            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];

            $photo = $files['photo']['name'];
            $tmp_photo = $files['photo']['tmp_name'];

            # Sql query string
            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name' ,
            username ='$username' WHERE id = $id";

            # Execute the query
            if ($this->conn->query($sql)) {
                $_SESSION['username'] = $username;
                $_SESSION['fullname'] = "$first_name $last_name";


                # Check if there is an uploaded image/photo, save it to the Db and save the file to images folder
                if ($photo) { //true or false  only run if there is data or go to next if
                    $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
                    $destination = "../assets/images/$photo"; //file from tmp_photo

                    if ($this->conn->query($sql)) { //save the image to the Db
                        #save the file to the image folder
                        if (move_uploaded_file($tmp_photo, $destination)) { //is okay?
                            header('location: ../views/dashboard.php');
                            exit;
                            #code...
                        }else {
                            die("Error in moving the photo");
                        }
                    }else {
                        die("Error in uploading image. " .$this->conn->error);
                    }

                }

                header('location: ../views/dashboard.php');
                exit;
            }else {
                die("Error in updateing the user ." . $this->conn->error);
            }
                
        }

        public function delete(){
            session_start();
            $id =$_SESSION['id']; //id of the currently logged-in user

            $sql = "DELETE FROM users WHERE id = $id";

            if($this->conn->query($sql)){
                $this->logout(); //call the logout method
            }else {
                die("Error in deleting your account . " . $this->conn->error);
            }
        }
        }
    

?>
