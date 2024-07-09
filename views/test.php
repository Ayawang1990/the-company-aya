<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <title>d-block Example</title>
</head>
<body>
    <div class="container">
        <span class="d-block">This span is displayed as a block element.</span>
        <span>This span is displayed as an inline element.</span>
    </div>
</body>
</html>
<?php
public function update($request, $files){
            session_start();
            $id = $_SESSION['id'];

            $first_name = $request['first_name'];
            $last_name = $request['last_name'];
            $username = $request['username'];

            $photo = $files['photo']['name'];
            $tmp_photo = $files['photo']['tmp_name'];

            # Sql query string
            $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = $id";

            # Execute the query
            if ($this->conn->query($sql)) {
                $_SESSION['username'] = $username;
                $_SESSION['full_name'] = "$first_name $last_name";

                # Check if there is an uploaded image/photo, save it to the Db and save the file to images folder
                if ($photo) { //true or false?
                    $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
                    $destination = "../assets/images/$photo";

                    if ($this->conn->query($sql)) { //save the image to the Db
                        # Save the file to the image folder
                        if (move_uploaded_file($tmp_photo, $destination)) { //is okay?
                            header('location: ../views/dashboard.php');
                            exit;
                        }else {
                            die("Error in moving the photo.");
                        }
                    }else {
                        die("Error in uploading image. " . $this->conn->error);
                    }

                }

                header('location: ../views/dashboard.php');
                exit;
            }else {
                die("Error in updating the user. " . $this->conn->error);
            }

        }