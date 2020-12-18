<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    if(isset($_SESSION['isAdmin']))
    {
        RenderPage();
    }else {
        header("Location: ..\\index.php");
        // include("../dbconnection.php");
        // $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        // $stmt = $con->prepare("SELECT isAdmin FROM users WHERE id_user = ?");
        // $stmt->bind_param('s', $_SESSION['user_id']);
        // $stmt->execute();
        // $result = $stmt->get_result();
        // $user = $result->fetch_object();
        // echo $user->isAdmin;
        // if($user->isAdmin == 1)
        // {
        //     $_SESSION['isAdmin'] = true;
        //     RenderPage();
            
        // }else{
        //     echo $_SESSION['user_id'];
        //     //header("Location: ../index.php");
        // }
    }


} else {
    // Redirect them to the login page
    header("Location: ..\\login.php");
}

function RenderPage()
    {
        ?>
        <html>

        <head>
            <link rel = "stylesheet" type = "text/css" href = "../styles/style.css" />
        </head>

        <body style="text-align: center;">
            <div id="container">
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


                </div>
            </div>
        </body>

        </html>



    <?php
    }
?>