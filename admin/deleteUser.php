<html>

<head>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link rel = "stylesheet" type = "text/css" href = "../styles/style.css" />
</head>

<body>
<header>
                    <h3>Witaj w panelu administracyjnym</h3>
                </header>

                <nav>
                    <div id="menu" style="float:left">
                        <button><a href="../index.php">Strona główna</a></button>
                        <button><a href="addroom.php">Dodaj pokój</a></button>
                        <button><a href="editrooms.php">Przeglądaj pokoje</a></button>
                        <button><a href="confirmReservation.php">Potwierdź rezerwacje</a></button>
                        <button><a href="users.php">Przeglądaj użytkowników</a></button>
                        <button><a href="../logout.php">Wyloguj</a></button>
                    </div>
                    <div style="clear:both"></div>
                </nav>
    <div id="content">

<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['isAdmin'])) {

    include "../dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if(isset($_POST['id_user']))
    {
        $id_user=$_POST['id_user'];
        if(isset($_POST['confirm'])&&isset($_POST['id_user']))
        {
            
            $quer = $con->prepare("DELETE FROM reservations WHERE id_user = ?");
            $quer->bind_param('i',$id_user);

            $quer->execute();

            $result = $quer->get_result();

            $quer = $con->prepare("DELETE FROM users WHERE id_user = ?");
            $quer->bind_param('i',$id_user);

            $quer->execute();
            
            // $user = $result->fetch_object();

            // $userId = $user->id_user;
            header("Location: users.php");

        }else {
            ?>
            <p>Czy chcesz usunąć użytkownika razem ze wszystkimi jego rezerwacjami?</p>
            <form action="deleteUser.php" method="POST">
                <!--<input type="hidden" name="confirm" value="true">-->
                <input type="hidden" name="id_user" value="<?=$id_user?>"/>
                <input type="submit" value="Tak." name="confirm"/>
            </form>
            <?php
        }
    }else{

        header("Location: users.php");
        
    }

    }
else{
// Redirect them to the login page
    header("Location: index.php");
}

?>

    </div>
</body>