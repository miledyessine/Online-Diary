<?php
    session_start();


    $host = "localhost";
    $dbUsername = "root";
    $dbPassword = "";
    $dbName = "online_diary";

    $mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);

    $location='';
    $id=0;
    $title='';
    $date='';
    $description='';
    $update=false;


    if (isset($_POST['save'])) {

        $title=$_POST['title'];
        $description=$_POST['description'];
        $date=$_POST['date'];

        $currentUser=$_SESSION['email'];
        $User=$mysqli->query("SELECT id FROM user WHERE email='$currentUser'") or die($mysqli->error);
        $row_id = mysqli_fetch_assoc($User);
        $location="?id=".$row_id['id'];
        $userId=$row_id['id'];


        $sql = "INSERT INTO journal (title, description, date, user_id)
                            VALUES ('$title', '$description', '$date', '$userId')";
        
        if (mysqli_query($mysqli, $sql)) {
            header("Location: journals.php".$location);
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($mysqli);
        }  
    }




    if (isset($_POST['login'])) {
        
        $email=$_POST['email'];
        $password=md5($_POST['password']);

        $sql = "SELECT * FROM `user` WHERE email='$email' AND password='$password'";
        $result = mysqli_query($mysqli, $sql);
        if ($result->num_rows > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['firstName'] = $row['firstName'];
            $_SESSION['email'] = $row['email'];
            $location="?id=".$row['id'];
            header("Location: journals.php".$location);
        } else {
            echo "<script>alert('Woops! Email or Password is Wrong.')</script>";
            header("Location: index.html");
        }
    }


    


    if (isset($_POST['signin'])) {

        $firstName=$_POST['firstName'];
        $lastName=$_POST['lastName'];
        $email=$_POST['email'];
        $password=md5($_POST['password']);
        $cpassword = md5($_POST['cpassword']);


        

        if (md5($_POST['password']) == md5($_POST['cpassword'])) {
            $sql = "SELECT * FROM `user` WHERE email='$email'";
            $result = mysqli_query($mysqli, $sql);
            if (!$result->num_rows > 0) {
                $sql = "INSERT INTO `user` (firstName, lastName, email, password)
                        VALUES ('$firstName', '$lastName', '$email', '$password')";
                $result = mysqli_query($mysqli, $sql);
                $row = mysqli_fetch_assoc($result);
                $_SESSION['firstName'] = $_POST['firstName'];
                $_SESSION['email'] = $_POST['email'];
                $currentUser=$_POST['email'];
                $User=$mysqli->query("SELECT id FROM user WHERE email='$currentUser'") or die($mysqli->error);
                $row_id = mysqli_fetch_assoc($User);
                $id=$row_id['id'];

                
                $location="?id=".$id;
                header("Location: journals.php".$location);
                if ($result) {
                    $firstName = "";
                    $lastName = "";
                    $email = "";
                    $_POST['password'] = "";
                    $_POST['cpassword'] = "";
                } else {
                    echo "<script>alert('Woops! Something Wrong Went.')</script>";
                }
            } else {
                echo "<script>alert('Woops! Email Already Exists.')</script>";
            }
            
        } else {
            echo "<script>alert('Password Not Matched.')</script>";
        }
    }



    if(isset($_GET['delete'])){
        $id=$_GET['delete'];
        $mysqli->query("DELETE FROM journal WHERE id=$id") or die($mysqli->error());
        $currentUser=$_SESSION['email'];
        $User=$mysqli->query("SELECT id FROM user WHERE email='$currentUser'") or die($mysqli->error);
        $row_id = mysqli_fetch_assoc($User);
        $location="?id=".$row_id['id'];
        header("Location: journals.php".$location);
    }

    if(isset($_GET['edit'])){
        
        $id=$_GET['edit'];
        $update=true;
        $result=$mysqli->query("SELECT * FROM journal WHERE id=$id") or die($mysqli->error());
        
        if (count(array($result))==1) {
            $row=$result->fetch_array();
            $title=$row['title'];
            $date=$row['date'];
            $description=$row['description'];
        }
    }


    if(isset($_POST['update'])){
        
        $id=$_POST['id'];
        $title=$_POST['title'];
        $date=$_POST['date'];
        $description=$_POST['description'];

        $mysqli->query("UPDATE journal SET title='$title', description='$description', date='$date'  WHERE id=$id") or die($mysqli->error);
        
        $currentUser=$_SESSION['email'];
        $User=$mysqli->query("SELECT id FROM user WHERE email='$currentUser'") or die($mysqli->error);
        $row_id = mysqli_fetch_assoc($User);
        $location="?id=".$row_id['id'];
        header("Location: journals.php".$location);
    }


    if(isset($_POST['upload'])){

        $title=$_POST['title'];
        $date=$_POST['date'];
        $description=$_POST['description'];
        $journal=$mysqli->query("SELECT id FROM journal WHERE title='$title' AND date='$date' AND description='$description'") or die($mysqli->error);
        $row_id = mysqli_fetch_assoc($journal);
        $id=$row_id['id'];

        $destFile='upload/'.$_FILES['uploadedImage']['name'];
        $result=$mysqli->query("INSERT INTO image (image_url,journal_id) VALUES('$destFile','$id')") or die($mysqli->error);

        if(move_uploaded_file($_FILES['uploadedImage']['tmp_name'],$destFile)){
            header("Location: create.php?edit=".$id);
            $update=true;
            $result=$mysqli->query("SELECT * FROM journal WHERE id=$id") or die($mysqli->error());
            
            if (count(array($result))==1) {
                $row=$result->fetch_array();
                $title=$row['title'];
                $date=$row['date'];
                $description=$row['description'];
            }
        }
        else{
            echo "<script>alert('Can't Upload')</script>";
        }
        
    }

    



?>