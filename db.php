<?php

    $db_name = "movie-star";
    $db_host = "localhost";
    $db_user = "root";
    $db_pass = "Magikarp2.";

    $conn = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);