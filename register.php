<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
}
if (!empty($_POST)) {
    if (isset($_SESSION['user_id'])) {
        header("Location index.php");
    } else {
        if (
            isset($_POST['newlogin']) && isset($_POST['newpassword']) &&
            isset($_POST['newname']) && isset($_POST['newsurname']) && isset($_POST['newemail'])
        ) {
            $newEmail = $_POST['newemail'];
            if (!CheckIfIsEmail($newEmail)) {
                WebBlock("Podaj poprawny adres email!");
            } else {
                include "dbconnection.php";
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
                $newLogin = $_POST['newlogin'];
                $newPassword = password_hash($_POST['newpassword'], PASSWORD_DEFAULT);
                $newName = $_POST['newname'];
                $newSurname = $_POST['newsurname'];
                $newAddress = $_POST['newaddress'];
                $newCity = $_POST['newcity'];
                $newPostalCode = $_POST['newpostalcode'];
                //echo $_SESSION['login'];

                $loginIsExists = $con->prepare("SELECT login FROM users WHERE login=?");
                $loginIsExists->bind_param('s', $newLogin);
                $loginIsExists->execute();

                $result = $loginIsExists->get_result();

                $user = $result->fetch_object();
                if ($user != null) {
                    WebBlock("Konto o takim loginie juz istnieje!");
                } else {
                    $stmt = $con->prepare("INSERT INTO users (login,password,name,surname,email,address,city,postalcode) values(?,?,?,?,?,?,?,?)");
                    $stmt->bind_param('ssssssss', $newLogin, $newPassword, $newName, $newSurname, $newEmail, $newAddress, $newCity, $newPostalCode);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    print("<h2>Witaj " . $_POST['newname'] . "! Twoje konto zostało założone </h2><br/>");
                    print("Login: " . $_POST['newlogin'] . "<br/>Hasło: " . $_POST['newpassword']);
                    print("<br>Zapamiętaj dane do logowania.<br/><br/><br/>Możesz przejść do strony logowania.<br> Kliknij poniżej<br>");
                    print("<a href='login.php'>Zaloguj</a>");
                };
            }
        }
    }
} else {
    WebBlock("");
}

function CheckIfIsEmail($email)
{
    $emailArray = str_split($email);

    foreach ($emailArray as $emailChar) {
        if ($emailChar == "@") return true;
    }
    return false;
}
function WebBlock($communicate)
{
?>
    <div id="register-box" style="text-align:center">
        <p style="color:red"> <?php echo $communicate; ?></p>
        <h3>Wypełnij poniższy formularz, aby zarejstrować się na stronie.</h3>
        <form action="register.php" method="POST">
            <input type="text" name="newlogin" placeholder="Twój nowy login" required><br /><br />
            <input type="password" name="newpassword" placeholder="Twoje hasło" required><br /><br />
            <input type="text" name="newname" placeholder="Imię" required><br /><br />
            <input type="text" name="newsurname" placeholder="Nazwisko" required><br /><br />
            <input type="text" name="newemail" placeholder="Adres email" required><br /><br />
            <input type="text" name="newaddress" placeholder="Ulica, numer domu i lokalu"><br /><br />
            <input type="text" name="newcity" placeholder="Miasto"><br /><br />
            <input type="text" name="newpostalcode" placeholder="Kod pocztowy"><br /><br />
            <input type="submit" value="Zarejestruj">
        </form>
        <h3>lub zaloguj się <a href="login.php">tutaj</a></h3>
    </div>


<?php
}
