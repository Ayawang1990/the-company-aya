<?php


    include "../classes/User.php";

    # create 
    $user = new User;


    #call the register method
    $user->store($_POST);
    # $_POST ---> holds the data coming from the form
    # Datas: firstname, lastname, username and password