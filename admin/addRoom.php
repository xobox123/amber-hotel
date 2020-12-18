<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();

if (isset($_SESSION['user_id'])) {

    if(isset($_SESSION['isAdmin']))
    {

        if(!empty($_POST))
        {
            if(isset($_POST['name'])&&isset($_POST['square_meters'])&&isset($_POST['guests_number'])&&
            isset($_POST['price'])&&isset($_POST['ammount_rooms']))
            {
                include "../dbconnection.php";
                $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

                $name = $_POST['name'];
                $square_meters = $_POST['square_meters'];
                $guests_number = $_POST['guests_number'];
                $price = $_POST['price'];
                $ammount_rooms = $_POST['ammount_rooms'];
                //not required
                //$photo = $_POST['photo'];
                $description = $_POST['description'];

                $photoPath;
                if (isset($_FILES['photo']['name']))
                {
                    $file_name = $_FILES["photo"]["name"];

                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);


                    $fileName = $name.".".$ext;
                    $target_path = $dir = "..\\photos\\". $fileName;

                    move_uploaded_file($_FILES["photo"]["tmp_name"],$target_path);
                    $photoPath = "photos\\". $fileName;
                }

                $stmt = $con->prepare("INSERT INTO rooms (name,square_meters,guests_number,price,ammount_rooms,photo,description) values(?,?,?,?,?,?,?)");
                $stmt->bind_param('sssssss', $name, $square_meters,$guests_number,$price,$ammount_rooms,$photoPath,$description);

                if($stmt->execute())
                {
                    echo "Pomyślnie dodano pokój.";
                    RenderPage();
                    $_POST = array();
                }else{
                    RenderPage();
                }
            }
        }else{
            RenderPage();
        }

        
    }else {
            
            header("Location: ..\\index.php");
        }
    }


 else {
    // Redirect them to the login page
    header("Location: .\\login.php");
}
    


    function RenderPage()
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
                    <h4>Wypełnij formularz, aby dodać pokój.</h4>
                    <div id="addRoom_form">
                        <form action="addRoom.php" method="POST" enctype="multipart/form-data">
                            <input type="text" name="name" placeholder="Nazwa pokoju" required><br/>
                            <input type="number" name="square_meters" placeholder="Ilość metrów kwadratowych" required><br/>
                            <input type="number" name="guests_number" placeholder="Możliwa ilość gości" required><br/>
                            <input type="number" name="price" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required placeholder="Cena za dobę"><br/>
                            <input type="number" name="ammount_rooms" placeholder="Ilość pokoi" required><br/>
                            <label for="photo">Załącz zdjęcie pokoju</label><br/>
                            <input type="file" name="photo"><br/><br/>
                            <input type="text" name="description" placeholder="Opis" style="height: 50px;"><br/>
                            <input type="submit" value="Dodaj">
                        </form>
                    </div>

                </div>
            </div>
        </body>

        </html>



    <?php
    }
?>