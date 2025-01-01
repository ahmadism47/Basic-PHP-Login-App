<?php
require "connection.php";

function check_login()
{

    if (empty($_SESSION['logged'])) {
        header("Location : login.php");
        die();
    }
}