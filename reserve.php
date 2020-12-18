<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    include "dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if(isset($_POST['name'])&&isset($_POST['surname']))
    {

        $quer = $con->prepare("SELECT price from rooms where id_room =?");
        $quer->bind_param('i',$_GET['roomId']);
        $quer->execute();
        $res = $quer->get_result();
        $roomPrice = $res->fetch_object();
    
        $query = $con->prepare("INSERT INTO reservations (dateStart,dateEnd,price,id_room,id_user,reservation_date,message) VALUES (?,?,?,?,?,NOW(),?)");
        
        $startDate = date("Y/m/d", strtotime($_GET['startDate']));
        $endDate = date("Y/m/d", strtotime($_GET['endDate']));
        $price = date_diff(new DateTime($_GET['startDate']),new DateTime($_GET['endDate']))->format('%d');
        $priceResult = $price * $roomPrice->price;

        $query->bind_param('sssiis',$startDate, $endDate,$priceResult,$_GET['roomId'],$_SESSION['user_id'],$_POST['message']);

        $query->execute();

        $result = $query->get_result();

        header("Location: mybooks.php");
    }else{

            $query = $con->prepare("select name, surname from users WHERE id_user=?");
            
            $query->bind_param('i',$_SESSION['user_id']);

            $query->execute();

            $result = $query->get_result();
            $user = $result->fetch_object();
            RenderForm($user);
    }

    }
else{
// Redirect them to the login page
    header("Location: login.php");
}

function RenderForm($user){
    ?>
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
        <div id="reserve-box">
            <p>Formularz potwierdzający rezerwację.</p>
            <br>
            <form action="reserve.php?roomId=<?=$_GET['roomId']."&startDate=".date("m/d/Y", strtotime($_GET['startDate']))."&endDate=".date("m/d/Y", strtotime($_GET['endDate']))?>" method="POST">
                <label for="name">Imię rezerwującego</label><br>
                <input type="text" value="<?=$user->name?>" name="name" placeholder="Imię" required><br/><br/>
                <label for="surname">Nazwisko rezerwującego</label><br>
                <input type="text" value="<?=$user->surname?>" name="surname" placeholder="Nazwisko" required><br/><br/>
                <label for="message">Dodatkowa informacja do rezerwacji dla obsługi.</label><br>
                <input type="text" class="wideInput" maxlength="150"name="message" placeholder="Dodatkowe informacje dla obsługi" required><br/><br/>
                <input type="submit" value="Potwierdź">
            </form>
        </div>
    </div>

</body>

</html>
<?php }