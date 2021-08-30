<html>
  <head>
    <title>Journal</title>
    <link rel="stylesheet" href="creation.css" />
    <meta charset="UTF-8">	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  </head>
  <body>
    <?php require_once 'config.php' ;
      
      $currentUser=$_SESSION['email'];
      $User=$mysqli->query("SELECT id FROM user WHERE email='$currentUser'") or die($mysqli->error);
      $row_id = mysqli_fetch_assoc($User);
      $Userid=$row_id['id'];
      if($update==true){
        $journal=$mysqli->query("SELECT id FROM journal WHERE title='$title' AND date='$date' AND description='$description'")or die($mysqli->error) ;
        $row_id = mysqli_fetch_assoc($journal);
        $id=$row_id['id'];
      }
      
      $image=$mysqli->query("SELECT * FROM image WHERE journal_id='$id'")or die($mysqli->error) ;
      $row_url = mysqli_fetch_assoc($image);
      $url=$row_url['image_url'];

    ?>
    <header>
      <a href="journals.php?id=<?php echo $Userid;?>" class="logo">Online Diary</a>
      <div class="toggle" onclick="toggleMenu();"></div>
      <ul class="menu">
          <li ><?php echo '<a class="user" href="#">' . $_SESSION['firstName'] . '</a>'; ?></li>
          <li class="logout"><a href="index.html">logout</a></li>
      </ul>
    </header>
    
    <div class="journal" style="background-image: url('<?php echo $url ?>'); background-position:center;background-size:cover; ">
      <form class="form" id="formId" action="config.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="form-element title-div">
          <label for="title" class="journal-label">Title</label>
          <input name="title" type="text" class="title" id="title" value="<?php echo $title ?>" placeholder="Enter title" required/>
        </div>
        <div class="form-element date-div">
          <label for="date" class="journal-label">Date</label>
          <input name="date" type="date" class="date" id="date" value="<?php echo $date ?>" required/>
        </div>
        <input type="file"  name="uploadedImage">
        <input type="submit" class="image-btn" name="upload" value="Upload">
        <div class="form-element description"> 
          <textarea name="description" type="text" id="description" placeholder="What's on your mind today? 💭"><?php echo $description ?></textarea>
        </div>
        <div class="form-element">
          <?php if($update==true): ?>
            <button name="update" class="save" type="submit" id="save" >Save</button>
          <?php else: ?>
            <button name="save" class="save" type="submit" id="save" >Save</button>
          <?php endif; ?>
        </div>
      </form>
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