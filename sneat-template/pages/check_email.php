<?php
include '../database/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $queryCheck = $connection->prepare("SELECT email FROM users WHERE email = ?");
    $queryCheck->bind_param("s", $email);
    $queryCheck->execute();
    $queryCheck->store_result();

    if ($queryCheck->num_rows > 0) {
        echo 'exists';
    } else {
        echo 'not exists';
    }

    $queryCheck->close();
    $connection->close();
}
?>