<?php
    function json_formatter($status, $msg){
        header('Content-Type: application/json');
        echo json_encode(['status' => $status, 'msg' => $msg]);
    }
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "auth";

    $con = mysqli_connect($host, $user, $pass, $db);

    $uid = mysqli_real_escape_string($con, $_POST['uid']);
    $pwd = mysqli_real_escape_string($con, $_POST['pwd']);
    
    if (empty($uid) || empty($pwd)) {
        json_formatter(false, "All fields are required");
    }else{
        $check = "SELECT * FROM users WHERE email='$uid' OR mobile='$uid'";
        $check = mysqli_query($con, $check);
        if(mysqli_num_rows($check) != 1){
            json_formatter(false, "Invalid login details");
        }else{
            $row = mysqli_fetch_assoc($check);
            $dehash = password_verify($pwd, $row['pwd']);
            if($dehash != true){
                json_formatter(false, "Invalid login details");
            }else{
                session_start();
                $_SESSION['id'] = $row['id'];
                $_SESSION['fname'] = $row['first'];
                $_SESSION['lname'] = $row['last'];
                $_SESSION['dob'] = $row['dob'];
                $_SESSION['gender'] = $row['gender'];
                $_SESSION['mobile'] = $row['mobile'];
                $_SESSION['email'] = $row['email'];
                json_formatter(true, "success");
            }
        }
    }
?>