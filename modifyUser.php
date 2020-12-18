<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {
    include "dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    
    if(isset($_POST['login']) && isset($_POST['name']) && isset($_POST['surname']) && isset($_POST['email']))
    {
        $query = $con->prepare("UPDATE users SET login=?, name=?, surname=?, email=?, address=?, city=?, postalcode=? WHERE id_user=?");

        $query->bind_param('sssssssi',$_POST['login'],$_POST['name'],$_POST['surname'],$_POST['email'],$_POST['address'],$_POST['city'],$_POST['postalcode'],$_SESSION['user_id']);
    
        $query->execute();
        header("Location: profile.php");
    }

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
            echo "<p> Obecnie nie posiadasz żanych rezerwacji</p>";
        
        }else {?>
        <div id="user-data">
            <h3><p>Wypełnij formularz w celu modyfikacji danych <?=$user->name?></p></h3>

            <form action="modifyUser.php" method="POST">
                <input type="text" name="login" placeholder="Login" required value="<?=$user->login?>"><br/><br/>
                <input type="text" name="name" placeholder="Imię" required value="<?=$user->name?>"><br/><br/>
                <input type="text" name="surname" placeholder="Nazwisko" required value="<?=$user->surname?>"><br/><br/>
                <input type="text" name="email" placeholder="Adres email" required value="<?=$user->email?>"><br/><br/>
                <input type="text" name="address" placeholder="Ulica, numer domu i lokalu" value="<?=$user->address?>"><br/><br/>
                <input type="text" name="city" placeholder="Miasto" value="<?=$user->city?>"><br/><br/>
                <input type="text" name="postalcode" placeholder="Kod pocztowy" value="<?=$user->postalcode?>"><br/><br/>

                <input type="submit" value="Modyfikuj"/>
            </form>

        </div>

        <?php }
        
        ?>
        

        </div>
    </div>

</body>

</html>
<?php }