<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    if(isset($_SESSION['isAdmin']))
    {
        if(isset($_POST['reservationStatus'])&&isset($_POST['id_reservation']))
        {
            $reservationStatus = $_POST['reservationStatus'];
            $id_reservation = $_POST['id_reservation'];

            include "../dbconnection.php";
            $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
            
            $query = $con->prepare("UPDATE reservations SET status = ? WHERE id_reservation = ?");
            $query->bind_param('si',$reservationStatus,$id_reservation);
            $query->execute();


            header("Location: confirmReservation.php");
            //$result = $query->get_result();
        }else {
            header("Location: confirmReservation.php");
        }


    }else {
            
        header("Location: ..\\index.php");
    }
}else {
// Redirect them to the login page
    header("Location: ..\\login.php");
}


