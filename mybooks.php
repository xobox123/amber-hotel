<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    include "dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);


    $query = $con->prepare("SELECT r.name,res.price, res.dateStart,res.dateEnd,r.ammount_rooms,res.message,res.id_reservation, res.status from reservations as res INNER JOIN rooms as r on res.id_room=r.id_room WHERE id_user = ?");
       

    
    $query->bind_param('i',$_SESSION['user_id']);

    $query->execute();

    $result = $query->get_result();
    RenderForm($result);
    

    }
else{
// Redirect them to the login page
    header("Location: login.php");
}

function RenderForm($data){
    ?>
<html>

<head>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="scripts/scripts.js"></script>
    <link rel = "stylesheet" type = "text/css" href = "styles/style.css" />
</head>

<body>
    <header>
        
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
        <div id="reservation-list">
        <?php
        if($data==null)
        {
            echo "<p> Obecnie nie posiadasz żanych rezerwacji</p>";
        
        }else {?>
            <p>Lista Twoich rezerwacji:</p>
            <table>
                <tr>
                    <th>Nazwa pokoju</th>
                    <th>Do zapłaty</th>
                    <th>Początek rezerwacji</th>
                    <th>Koniec rezerwacji</th>
                    <th>Liczba pokoi</th>
                    <th>Informacje dla obsługi</th>
                    <th>Status rezerwacji</th>
                    <th>Akcja</th>
                </tr>
                <?php
                foreach($data as $dat)
                {
                    echo "<tr>";
                    echo "<td>".$dat['name']."</td>";
                    echo "<td>".$dat['price']."zł.</td>";
                    echo "<td>".$dat['dateStart']."</td>";
                    echo "<td>".$dat['dateEnd']."</td>";
                    echo "<td>".$dat['ammount_rooms']."</td>";
                    echo "<td>".$dat['message']."</td>";
                    echo "<td>".$dat['status']."</td>";
                    ?> 
                    <td>
                    <form action="cancelReservation.php" method="POST">
                        <input type="hidden" name="id_reservation" value="<?=$dat['id_reservation']?>"/>
                        <input type="submit" value="Anuluj rezerwację"/>
                    </form>

                    </td>
                    <?php
                    
                    echo "</tr>";
                }?>
            </table>

        <?php } ?>
        

        </div>
    </div>

</body>

</html>
<?php }