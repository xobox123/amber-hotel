<?php
// You'd put this code at the top of any "protected" page you create

// Always start this first
session_start();


if (isset($_SESSION['user_id'])) {

    
    if(isset($_SESSION['isAdmin']))
    {
        
        include "../dbconnection.php";
        $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        
        if(!empty($_POST))
        {
            
            if(isset($_POST['name'])&&isset($_POST['square_meters'])&&isset($_POST['guests_number'])&&
            isset($_POST['price'])&&isset($_POST['ammount_rooms']))
            {
                


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

                    
                    echo $_FILES["photo"]["tmp_name"];

                    move_uploaded_file($_FILES["photo"]["tmp_name"],$target_path);
                    $photoPath = "photos\\". $fileName;
                }

                $stmt = $con->prepare("UPDATE rooms SET name = ?, square_meters =?, guests_number=?,price=?,ammount_rooms=?,photo=?,description=?
                                        WHERE id_room = ?");
                $stmt->bind_param('ssssssss', $name, $square_meters,$guests_number,$price,$ammount_rooms,$photoPath,$description,$_GET['roomId']);

                if($stmt->execute())
                {
                    header ("Location: editRooms.php");
                }else{
                    RenderPage();
                }
            }
        }else{
            $query = $con->prepare("SELECT * from rooms WHERE id_room=?");
            $query->bind_param('s',$_GET['roomId']);
            $query->execute();
            
            $result = $query->get_result();
            $room = $result->fetch_object();
           
            

            RenderPage($room->name,$room->square_meters,$room->guests_number,$room->price);
        }

        
    }else {
            
            header("Location: ..\\index.php");
        }
    }


 else {
    // Redirect them to the login page
    header("Location: ..\\login.php");
}
    


    function RenderPage($roomName, $roomMeters, $roomGuests, $roomPrice)
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
                        <button><a href="users.php">Przeglądaj użytkowników</a></button>
                        <button><a href="../logout.php">Wyloguj</a></button>
                    </div>
                    <div style="clear:both"></div>
                </nav>
                <div id="content">
                    <h4>Wypełnij formularz edycji pokoju.</h4>
                    <div id="addRoom_form">
                        <form action="<?=$_SERVER['REQUEST_URI']?>" method="POST" enctype="multipart/form-data">
                            <input type="text" value ="<?=$roomName?>" name="name" placeholder="Nazwa pokoju" required><br/>
                            <input type="number" value ="<?=$roomMeters?>" name="square_meters" placeholder="Ilość metrów kwadratowych" required><br/>
                            <input type="number" value ="<?=$roomGuests?>" name="guests_number" placeholder="Możliwa ilość gości" required><br/>
                            <input type="number" value ="<?=$roomPrice?>" name="price" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required placeholder="Cena za dobę"><br/>
                            <input type="number" name="ammount_rooms" placeholder="Liczba pokoi" required><br/>
                            <label for="photo">Załącz zdjęcie pokoju</label><br/>
                            <input type="file" name="photo"><br/><br/>
                            <input type="text" name="description" placeholder="Opis" style="height: 50px;"><br/>
                            <input type="submit" value="Modyfikuj">
                        </form>
                    </div>

                </div>
            </div>
        </body>

        </html>



    <?php
    }
?>