<?php

session_start();
include("connection.php");

if ($_POST["action"] == "comment") {
    $comment = $_POST['comment'];
    $postId = $_POST['post_id'];
    $userId = $_POST['user_id'];
    $userName = $_POST['user_name'];
    if (mysqli_query($conn, "insert into comment (User_id,User_name,Post_id,Comment) 
    values ('$userId','$userName','$postId','$comment')")) {
        echo "success";
    }
}
