<?php

    // get access to database
    include_once "common/base.php";

    //  If login form has been submitted
    if(isset($_POST['employee_id']) && isset($_POST['password'])){

            // include user class
        include "users.php";
            // Create new user object
        $user = new user($db);
            // If login method successful: set session variables and redirect to planner.php page
        echo json_encode($user->logIn($_POST['employee_id'], $_POST['password']));
    }



?>