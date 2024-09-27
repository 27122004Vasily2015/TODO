<?php  
session_start();      

if(isset($_SESSION["message"])){  
    $message = $_SESSION["message"];  
    echo "<script>alert('$message')</script>";  
    unset($_SESSION["message"]);  
}  
?>  

<!DOCTYPE html>  
<html lang="en">  
<head>  
    <meta charset="UTF-8">  
    <meta name="viewport" content="width=device-width, initial-scale=1.0">  
    <title>Document</title>
    <link rel="stylesheet" href="css/index.css">   
    <link rel="stylesheet" href="design/css/bootstrap.min.css">  
</head>  

<body> 
<header>  
    TODO LIST  
</header>  

<div class="search">  
    <form id="search-form" class="search_forms" method='get' action='user.php'>  
        <input type="search" name="search" id="search">  
        <img src="images/search.png" alt="" class="search_logo">  
    </form>  

    <div class="tema">  
    <button id="theme-toggle" class="btn btn-primary"> <img src="images/sun.png" alt=""></button>
    </div>  
    <div class="exit">  
        <a href="exit.php"><img src="" alt=""> выход</a>
    </div>  
</div>  

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script> 
  // Corrected the keyup event handler
  $('#search-form').on('keyup', function (e) { 
    // Assuming you want to handle the search input here
    const searchQuery = $(this).val(); // Get the current value of the input
    // You can add your search logic here
    console.log(searchQuery); // For demonstration purposes
  });    

  // Theme management
  const currentTheme = localStorage.getItem('theme') || 'light';   
  if (currentTheme === 'dark') {   
      document.body.classList.add('dark-theme');   
  }   
  
  document.getElementById('theme-toggle').addEventListener('click', () => {   
      document.body.classList.toggle('dark-theme');   
      const newTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';   
      localStorage.setItem('theme', newTheme);   
  });    
</script>



<!--  
 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
    <script> 
  $('#search-form').on('keyup', function (e)){ 
    <?php 
    $searching = isset($_GET['search']) ? $_GET['search'] : false; 
    ?> 
  }    

  const currentTheme = localStorage.getItem('theme') || 'light';   
    if (currentTheme === 'dark') {   
        document.body.classList.add('dark-theme');   
    }   
   
    document.getElementById('theme-toggle').addEventListener('click', () => {   
        document.body.classList.toggle('dark-theme');   
        const newTheme = document.body.classList.contains('dark-theme') ? 'dark' : 'light';   
        localStorage.setItem('theme', newTheme);   
    });    

</script> -->
