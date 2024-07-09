<?php
    include "../classes/User.php";


    #Create an onject
    $user = new User;

    #call the method
    $user->login($_POST);