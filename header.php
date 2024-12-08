<?php  
session_start(); 
require_once 'database/Connect.php';     

if (isset($_SESSION["message"])) {  
    $message = $_SESSION["message"];  
    echo "<script>alert('$message')</script>";  
    unset($_SESSION["message"]);  
}  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = isset($_POST["login"]) ? $_POST["login"] : false; 
    $password = isset($_POST["password"]) ? $_POST["password"] : false; 

    if ($login && $password) { 
        $sql = "SELECT * FROM users WHERE username = '$login'"; 
        $result = mysqli_query($con, $sql); 

        if (mysqli_num_rows($result) != 0) { 
            $user = mysqli_fetch_assoc($result); 
            if (password_verify($password, $user["password_hash"])) { 
                $_SESSION["id_user"] = $user["id"];  
                $_SESSION["message"] = "Успех!"; 
                header("Location: /user.php"); 
                exit(); 
            } else { 
                $_SESSION["message"] = "Неверный пароль"; 
                header("Location: /"); 
                exit();
            } 
        } else { 
            $_SESSION["message"] = "Неверный логин"; 
            header("Location: /"); 
            exit();
        } 
    } else { 
        $_SESSION["message"] = "Заполните все поля!"; 
        header("Location: /"); 
        exit();
    }
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

<?php if (isset($_SESSION["id_user"])): ?> 
<div class="search">  
    <form id="search-form" class="search_forms" method='get' action='user.php'>  
        <input type="search" name="search" id="search">  
        <img src="images/search.png" alt="" class="search_logo">  
    </form>  

    <div class="tema">  
        <button id="theme-toggle" class="tema"> <img src="images/sun.png" alt=""></button>
    </div>  
    <div class="exit">  
        <a href="exit.php"><img src="" alt=""> выход</a>
    </div>  
</div>
<?php endif; ?> 

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> 
<script> 
  $('#search-form').on('keyup', function (e) { 
    const searchQuery = $(this).find('input[name="search"]').val(); 
    console.log(searchQuery); 
  });    

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
</body>
</html>