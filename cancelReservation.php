<html>

<head>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link rel = "stylesheet" type = "text/css" href = "styles/style.css" />
</head>

<body>
    <header>
        <h3>Witaj na stronie</h3>
    </header>

    <nav>
        <div id="menu" style="float:left">
            <button><a id="reservation-link">Utwórz rezerwację</a></button>
            <button><a href="mybooks.php">Moje rezerwacje</a></button>
            <button><a href="profile.php">Profil</a></button>
            <button><a href="logout.php">Wyloguj</a></button>
        </div>
        <div style="clear:both"></div>
    </nav>
    <div id="content">

<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    include "dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if(isset($_POST['id_reservation']))
    {
        $id_reservation=$_POST['id_reservation'];
        if(isset($_POST['confirm'])&&isset($_POST['id_reservation']))
        {
            $quer = $con->prepare("DELETE FROM reservations WHERE id_reservation= ? AND id_user = ?");
            $quer->bind_param('ii',$_POST['id_reservation'],$_SESSION['user_id']);

            $quer->execute();

            $result = $quer->get_result();
            
            // $user = $result->fetch_object();

            // $userId = $user->id_user;
            header("Location: mybooks.php");

        }else {
            ?>
            <p>Czy chcesz anulować rezerwację?</p>
            <form action="cancelReservation.php" method="POST">
                <!--<input type="hidden" name="confirm" value="true">-->
                <input type="hidden" name="id_reservation" value="<?=$id_reservation?>"/>
                <input type="submit" value="Tak." name="confirm"/>
            </form>
            <?php
        }
    }else{

        header("Location: mybooks.php");
        
    }

    }
else{
// Redirect them to the login page
    header("Location: login.php");
}

?>

    </div>
</body>