<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    

        include "dbconnection.php";
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        
        $query = $con->prepare("select rooms.* from rooms left join reservations on rooms.id_room = reservations.id_room and reservations.dateEnd >= ? and reservations.dateStart <= ? where reservations.id_reservation IS NULL");
        
        $startDate = date("Y/m/d", strtotime($_GET['startDate']));
        $endDate = date("Y/m/d", strtotime($_GET['endDate']));
        $query->bind_param('ss',$startDate, $endDate);

        $query->execute();

        $result = $query->get_result();
        if($result==null)
        {
            RenderPage(null);
        }else{
            //$rooms = $result->fetch_assoc();

            RenderPage($result);
        }

    }
else{
// Redirect them to the login page
    header("Location: login.php");
}



function RenderPage($rooms)
{

    ?>
    <html>

    <head>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script type="text/javascript" src="scripts/scripts.js"></script>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel = "stylesheet" type = "text/css" href = "styles/style.css" />
    </head>

    <body style="text-align: center;">
        <div id="container">
            <header>
                <h3>Witaj na stronie</h3>
            </header>

            <nav>
        <div id="menu" style="float:left">
            <button><a id="reservation-link">Utwórz rezerwację</a></button>
            <button><a href="mybooks.php">Moje rezerwacje</a></button>
            <button><a href="profile.php">Profil</a></button>
            <?php 
            if(isset($_SESSION['isAdmin'])){
                echo "<button><a href='./admin'>Panel administracyjny</a></button>";
            }
            ?>
            <button><a href="logout.php">Wyloguj</a></button>
        </div>
        <div style="clear:both"></div>
    </nav>
            <div id="content">
        

            <script>
                
            </script>
                            <h3>Wybierz datę rezerwacji</h3>
                <input type="text" name="daterange" id="datePicker" value="<?=date("m/d/Y", strtotime($_GET['startDate']))." - ".date("m/d/Y", strtotime($_GET['endDate']))?>"/>
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
                                    echo "<td>".$room['price']."</td>";
                                    echo "<td>".$room['description']."</td>";
                                    echo "<td><img style='height: 250px;width:250px;'src='".$room['photo']."'</td>";
                                    echo "<td><a href='reserve.php?roomId=".$room['id_room']."&startDate=".date("m/d/Y", strtotime($_GET['startDate']))."&endDate=".date("m/d/Y", strtotime($_GET['endDate']))."' >Rezerwuj</a><br/><br/>";
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