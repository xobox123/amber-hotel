
<?php
session_start();

if (isset($_SESSION['user_id']) && isset($_SESSION['isAdmin'])) {

    include "../dbconnection.php";
    $con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);

    if(isset($_POST['id_user']))
    {
        

        $id_user=$_POST['id_user'];

        $quer = $con->prepare("SELECT * FROM users WHERE id_user = ?");
        $quer->bind_param('i',$id_user);
        

        $quer->execute();

        $result = $quer->get_result();
        $isAdmin = $result->fetch_object()->isAdmin;
        
        if($isAdmin==0)
        {   
            $query = $con->prepare("UPDATE users SET isAdmin = 1 WHERE id_user = ?");
            $query->bind_param('i',$id_user);

            $query->execute();
        }else{
            $query = $con->prepare("UPDATE users SET isAdmin = 0 WHERE id_user = ?");
            $query->bind_param('i',$id_user);

            $query->execute();
        }



        header("Location: users.php");
        }
    }else{

        header("Location: ../index.php");
        
    }


?>

