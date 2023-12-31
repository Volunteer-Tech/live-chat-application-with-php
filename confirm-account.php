<?php
    session_start();
    require 'config/config.php';

    $confirm_id = "";
    if (!empty($_GET['confirm_id'])) {
        $confirm_id = $_GET['confirm_id'];
    }
    if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
        header("location: login.php?redirect_link=confirm-account.php?id=$confirm_id");
        exit;
    } else if (!isset($_GET['id'])) {
        header('location: index.php');
        exit();
    } else {
        $id = $_GET['id'];
        if ($_SESSION['id'] != $id) {
            $err_message = "You can only confirm the account you are logged in to!";
            header("location: home.php&err_message=$err_message");
            return;
        }
        $queryString = "SELECT * FROM users WHERE id='$id' ORDER BY id DESC LIMIT 1"; 
        $result = mysqli_query($link, $queryString);
        $row = mysqli_fetch_assoc($result);

        $user_id = $row['id'];
        $username = $row['username'];

        $sql = "UPDATE users SET verified=1 WHERE id='$user_id'";
        mysqli_query($link, $sql);
        $sql = "INSERT INTO notifications (text, userid) VALUES ('Your account has been verified.', '".$user_id."')";
        mysqli_query($link, $sql);
        $lastname = $_SESSION['username'];
        $sql = "INSERT INTO chat (action, actiontext) VALUES ('1', '$username just verified his account!')";
        mysqli_query($link, $sql);

        $err_message = "Your account has been confirmed!";
        header('location: profile.php?id='.$user_id.'&err_message='.$err_message.'');
    }
?>
