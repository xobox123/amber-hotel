<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    if(isset($_SESSION['isAdmin']))
    {
        include "../dbconnection.php";
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        
        $query = $con->prepare("SELECT * FROM users");

        $query->execute();

        $result = $query->get_result();
        if($result==null)
        {
            RenderPage(null);
        }else{
            //$rooms = $result->fetch_assoc();

            RenderPage($result);
        }

    }else {
            
        header("Location: ..\\index.php");
    }
}else {
// Redirect them to the login page
    header("Location: ..\\login.php");
}



function RenderPage($users)
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
                <h3>Lista pokoi</h3>
                <?php if($users==null){
                    echo "<h3>Brak dostępnych pokoi.</h3>";
                }else{?>
                <div id="table-rooms">
                <table style="border-collapse:collapse; width: 1000px;">
                        <tr>
                            <th>User ID</th>
                            <th>Admin?</th>
                            <th>Login</th>
                            <th>Imię</th>
                            <th>Nazwisko</th>
                            <th>Email</th>
                            <th>Adres</th>
                            <th>Miasto</th>
                            <th>Kod pocztowy</th>
                            <th>Akcja</th>
                        </tr>
                        <?php
                            foreach($users as $user)
                            {
                                //rom = $room->fetch_object();
                                echo "<tr>";
                                    echo "<td>".$user['id_user']."</td>";
                                    echo "<td>".$user['isAdmin']."</td>";
                                    echo "<td>".$user['login']."</td>";
                                    echo "<td>".$user['name']."</td>";
                                    echo "<td>".$user['surname']."</td>";
                                    echo "<td>".$user['email']."</td>";
                                    echo "<td>".$user['address']."</td>";
                                    echo "<td>".$user['city']."</td>";
                                    echo "<td>".$user['postalcode']."</td>";
                                    ?>
                                    <td>
                                    <form action="deleteUser.php" method="POST">
                                        <input type="hidden" name="id_user" value="<?=$user['id_user']?>" />
                                        <input type="submit" value="Usuń użytkownika"/>
                                    </form>
                                    <form action="setAdmin.php" method="POST">
                                        <input type="hidden" name="id_user" value="<?=$user['id_user']?>" />
                                        <input type="submit" value="Admin"/>
                                    </form>
                                    </td>

                                    <!-- <a href='changePassword.php?roomId=".$user['id_user']."' >Zmień hasło</a></td>"; -->

                                    <?php
                                    //echo "<td><aUsuń pokój</a></td>";
                                echo "</tr>";
                            }
                        ?>

                    </table>
                </div>
                <?php }?>

                </div>

            </div>
        </div>
    </body>

    </html>



<?php
}
?>