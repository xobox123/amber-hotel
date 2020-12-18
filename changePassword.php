<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {
    include "dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
    
    if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) && isset( $_POST['newpassword'])  && isset( $_POST['newpassword2'] )) {
        // Getting submitted user data from database
        $stmt = $con->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param('s', $_POST['login']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();
    		
        if(!empty($user))
        {
            if ( password_verify($_POST['password'], $user->password ) ) {
                $newPassword = $_POST['newpassword'];
                $newPassword2 = $_POST['newpassword2'];
                if($newPassword===$newPassword2){

                    $login = $user->login;
                    $pass = password_hash($_POST['newpassword'],PASSWORD_DEFAULT);     

                    $query = $con->prepare("UPDATE users SET password=? WHERE login = ? AND id_user = ?");
                    $query->bind_param('ssi', $pass, $login, $_SESSION['user_id']);
        
                    $query->execute();
                    echo "<h4 style='color:green'>Hasło zostało zmienione!</h4>";


                }else {
                    echo "<h4 style='color:red'>Hasła się nie zgadzają!</h4>";
                }

            //header("Location: index.php");
        
    	    }else {
            echo "<h4 style='color:red'>Login lub hasło nieprawidłowe</h4>";
            }
        }else {
            echo "<h4 style='color:red'>Login lub hasło nieprawidłowe</h4>";
            }
    }
    

    }
else{
// Redirect them to the login page
    header("Location: login.php");
}

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

            <button><a href="logout.php">Wyloguj</a></button>
        </div>
        <div style="clear:both"></div>
    </nav>
    <div id="content">
        <div id="reservation-list">
        <div id="user-data">
            <h3><p>Zmiana hasła</p></h3>
            <form action="changePassword.php" method="POST">
                <label>Login</label><br>
                <input type="text" name="login" placeholder="Login" required><br/><br/>
                <label>Hasło</label><br>
                <input type="password" name="password" placeholder="Hasło" required><br/><br/>
                <label>Nowe hasło</label><br>
                <input type="password" name="newpassword" placeholder="Nowe hasło" required><br/><br/>
                <label>Potwierdź nowe hasło</label><br>
                <input type="password" name="newpassword2" placeholder="Potwierdź nowe hasło" required><br/><br/>
                
                <input type="submit" value="Zmień"/>
            </form>
    </div>
</body>
</html>