<?php
    session_start();

    $db = new PDO(
      'mysql:host=localhost;dbname=quiz2',
      'root'
    );

    if ( isset($_COOKIE["number_of_visitor"]) && isset($_COOKIE["id"]) ) {
      $data = $db->prepare("SELECT username FROM account WHERE accountID=?");
      $data->execute([$_COOKIE["number_of_visitor"]]);
      $row = $data->fetch();

      if ( $_COOKIE["id"] === hash("md5", $row["username"]) ) {
        $_SESSION["login"] = true;
      }
    }

    if ( isset($_SESSION["login"]) ) {
      header("Location: index.php");
      exit;
    }

    if ( isset($_POST['login']) ) {
      $data = $db->prepare("SELECT * FROM account WHERE username=? AND password=?");
      $data->execute([$_POST['username'], $_POST['password']]);
      $count = $data->rowCount();
      $row = $data->fetch();
      if ( $count === 1 ) {
        $_SESSION["login"] = true;

        if ( isset($_POST["remember"]) ) {
          setcookie('number_of_visitor', $row['accountID'], time()+600);
          setcookie('id', hash("md5", $row['username']), time()+600);
        }

        header("Location: index.php");
        exit;
      }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
</head>
<body>
<div class="login">
    <form method="POST" class="form-login">
      <input type="text" id="login" class="input" name="username" placeholder="username" required>
      <input type="password" class="input" id="password" name="password" placeholder="password" required>
      <div class="remember-me input">
        <input type="checkbox" name="remember" id="remember">
        <label for="remember">Remember me</label>
      </div>
      <input type="submit" class="input" name="login" value="Log In">
    </form>
</div>
</body>
</html>