<?php
  
  $host = "localhost";
  $dbUsername = "root";
  $dbPassword = "";
  $dbName = "online_diary";

  $mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);
?>



<html>
  <head>
    <title>Journal</title>
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="journals.css" />
    <meta charset="UTF-8">	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous"></script>
  </head>
  <body>
    <?php require_once 'config.php' ;
      
      $currentUser=$_SESSION['email'];
      $User=$mysqli->query("SELECT id FROM user WHERE email='$currentUser'") or die($mysqli->error);
      $row_id = mysqli_fetch_assoc($User);
      $id=$row_id['id'];

      $result=$mysqli->query("SELECT * FROM journal WHERE user_id='$id'") or die($mysqli->error);
    
    ?>
    <header>
      <a href="journals.php?id=<?php echo $id;?>" class="logo">Online Diary</a>
      <div class="toggle" onclick="toggleMenu();"></div>
      <ul class="menu">
          <li ><?php echo '<a class="user" href="#">' . $_SESSION['firstName'] . '</a>'; ?></li>
          <li class="logout"><a href="index.html">logout</a></li>
      </ul>
    </header>
  <a class="create" href="create.php">Create a new journal</a>
  <div class="row justify-content-center">
    
    <table class="table"> 
      <thead>
        <th>Title</th>
        <th>Date</th>
        <th colspan="2">Action</th>
      </thead>
      
      <?php
        while($row=$result->fetch_assoc()):?>
      <tr>
        <td><?php echo $row['title']; ?></td>
        <td><?php echo $row['date']; ?></td>
        <td>
          <a href="create.php?edit=<?php echo $row['id'];?>"
            class="btn btn-info">Edit</a>
          <a href="journals.php?delete=<?php echo $row['id'];?>"
            class="btn btn-danger">Delete</a>
        </td>
      </tr>
      <?php endwhile; ?>

    </table>
  </div>



    <script type="text/javascript">
      
      window.addEventListener("scroll", function () {
        var header = document.querySelector("header");
        header.classList.toggle("sticky", window.scrollY > 0);
      });

      function toggleMenu() {
        var menuToggle = document.querySelector(".toggle");
        var menu = document.querySelector(".menu");
        menuToggle.classList.toggle("active");
        menu.classList.toggle("active");
      }
    </script>

  </body>
</html>
