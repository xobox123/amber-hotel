<?php
// Always start this first
session_start();


if ( isset( $_SESSION['user_id'] ) ) {
    header("Location: index.php");
}
if ( ! empty( $_POST ) ) {
    if ( isset( $_POST['login'] ) && isset( $_POST['password'] ) ) {
        // Getting submitted user data from database
        include "dbconnection.php";
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $stmt = $con->prepare("SELECT * FROM users WHERE login = ?");
        $stmt->bind_param('s', $_POST['login']);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_object();
    		
    	// Verify user password and set $_SESSION
    	/*if ( password_verify( $_POST['password'], $user->password ) ) {
    		$_SESSION['user_id'] = $user->ID;
        }*/

        //$pass = password_hash("test",PASSWORD_DEFAULT);
        //$hash = '$2y$10$p8CIzP4j73ZVq.29SKBqPexQKhoancMz2rJSvueU4o.IY6WET7SxC'; 
        if(!empty($user))
        {
            if ( password_verify($_POST['password'], $user->password ) ) {
            $_SESSION['user_id'] = $user->id_user;
            if($user->isAdmin == 1){
                $_SESSION['isAdmin'] = true;
            }
            header("Location: index.php");
        
    	    }else {
            echo "<h4 style='color:red'>Login lub hasło nieprawidłowe</h4>";
            }
        }else {
            echo "<h4 style='color:red'>Login lub hasło nieprawidłowe</h4>";
            }
    }
}
?>
    <header style="text-align:center;">
        <h3>Witaj na stronie hotelu, aby przejść  dalej musisz się zalogować lub zarejestrować</h3>
    </header>
    <div id="login_box" style="text-align: center;">
        <form action="login.php" method="POST">
            <input type="text" name="login" placeholder="Wpisz swój login" required><br/><br/>
            <input type="password" name="password" placeholder="Wpisz swoje hasło" required><br/>
            <input type="submit" value="Zaloguj">
        </form>
        <br/>
        <div id="register" style="text-align:center;">
        <button><a href="register.php" style="text-decoration: none; color: black;">Zarejestruj się</a></button>
    </div>
    </div>