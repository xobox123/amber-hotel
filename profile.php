<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    include "dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);


    $query = $con->prepare("SELECT * from users WHERE id_user = ?");
       

    
    $query->bind_param('i',$_SESSION['user_id']);

    $query->execute();

    $result = $query->get_result();
    RenderForm($result->fetch_object());
    

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
        <div id="reservation-list">
        <?php
        if($user==null)
        {
            echo "<p> Użytkownik jest pusty?</p>";
        
        }else {?>
        <div id="user-data">
            <h3><p>Witaj <?=$user->name?></p></h3>
            <br/><br/>
            <p>Twoje dane:</p>
            Imię: <?=$user->name?> <br/>
            Nazwisko: <?=$user->surname?><br/>
            Email: <?=$user->email?><br/>
            Adres: <?=$user->address?><br/>
            Miasto: <?=$user->city?><br/>
            Kod pocztowy: <?=$user->postalcode?><br/>
        </div>
        <div id="forms-modify">
            <form action="modifyUser.php" method="POST">
                <input type="hidden" name="id_user" value="<?=$user->id_user?>" />
                <input type="submit" value="Modyfikuj"/>
            </form>
        </div>
        <div id="forms-modify">
            <form action="changePassword.php" method="POST">
                <input type="hidden" name="id_user" value="<?=$user->id_user?>" />
                <input type="submit" value="Zmień hasło"/>
            </form>
        </div>
        <?php } ?>
        

        </div>
    </div>

</body>

</html>
<?php }