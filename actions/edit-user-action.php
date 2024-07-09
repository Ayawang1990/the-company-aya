<?php
    include "../classes/User.php";

    #Create the onject
    $user = new User;

    #call the update method
    $user->update($_POST, $_FILES);
    # $_POST = [ firstname, lastname, username]
    # $_FILES = [image]



?>