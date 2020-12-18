<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    if(isset($_SESSION['isAdmin']))
    {
        include "../dbconnection.php";
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        
        $query = $con->prepare("DELETE FROM reservations WHERE id_room=?");
        $query->bind_param('s',$_GET['roomId']);
        $query->execute();

        $query = $con->prepare("DELETE FROM rooms WHERE id_room=?");
        $query->bind_param('s',$_GET['roomId']);
        $query->execute();

        header("Location: editRooms.php");
        //$result = $query->get_result();


    }else {
            
        header("Location: ..\\index.php");
    }
}else {
// Redirect them to the login page
    header("Location: ..\\login.php");
}