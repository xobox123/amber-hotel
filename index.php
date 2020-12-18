<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {
    // Grab user data from the database using the user_id
    // Let them access the "logged in only" pages
} else {
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
            <?php 
            if(isset($_SESSION['isAdmin'])){
                echo "<button><a href='./admin'>Panel administracyjny</a></button>";
            }
            ?>
            <button><a href="logout.php">Wyloguj</a></button>
        </div>
        <div style="clear:both"></div>
    </nav>
</body>

</html>