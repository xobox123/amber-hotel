
<?php
session_start();

if (isset($_SESSION['isAdmin'])) {

    include "../dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if(isset($_POST['id_reservation']))
    {
        

        $id_reservation=$_POST['id_reservation'];

        $quer = $con->prepare("DELETE FROM reservations WHERE id_reservation=?");
        $quer->bind_param('i',$id_reservation);

        $quer->execute();

        $result = $quer->get_result();


        header("Location: confirmReservation.php");
        }
    }else{

        header("Location: ../index.php");
        
    }


?>

