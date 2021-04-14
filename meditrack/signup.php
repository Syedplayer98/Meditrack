<?php

    include 'database.php';

    $con = connect();
    
    echo "Connected<br>";
    $email = $_POST['email'];
    $name = $_POST['name'];
    $password = $_POST['password'];
    
    $query = "insert into users(name, email, password) values('$email', '$name', '$password')";
    $result = mysqli_query($con, $query);
    
    if ($result){
        echo "Signed up succesfully";
        sleep(2);
        header("Location: index.php");
    }
    closeConnection($con);
    
?>