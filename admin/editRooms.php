<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    if(isset($_SESSION['isAdmin']))
    {
        include "../dbconnection.php";
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        
        $query = $con->prepare("SELECT * FROM rooms");

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



function RenderPage($rooms)
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
                <?php if($rooms==null){
                    echo "<h3>Brak dostępnych pokoi.</h3>";
                }else{?>
                <div id="table-rooms">
                <table style="border-collapse:collapse; width: 1000px;">
                        <tr>
                            <th>Nazwa pokoju</th>
                            <th>Ilość metrów</th>
                            <th>Liczba pokoi</th>
                            <th>Cena za dobę</th>
                            <th>Opis</th>
                            <th>Podgląd</th>
                            <th>Akcja</th>
                        </tr>
                        <?php
                            foreach($rooms as $room)
                            {
                                //rom = $room->fetch_object();
                                echo "<tr>";
                                    echo "<td>".$room['name']."</td>";
                                    echo "<td>".$room['square_meters']."m</td>";
                                    echo "<td>".$room['guests_number']."</td>";
                                    echo "<td>".$room['price']."zł</td>";
                                    echo "<td>".$room['description']."zł</td>";
                                    echo "<td><img style='height: 250px;width:250px;'src='..\\".$room['photo']."'</td>";
                                    echo "<td><a href='deleteRoom.php?roomId=".$room['id_room']."' >Usuń pokój</a><br/><br/>
                                                <a href='modifyRoom.php?roomId=".$room['id_room']."' >Zmodyfikuj</a></td>";
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