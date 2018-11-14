<?php 
session_start();
$_SESSION['message'] = '';
$mysqli = new mysqli('localhost', 'root', '', 'battlenet');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  //two passwords are equal to each other
  if($_POST['password'] == $_POST['confirmpassword']){

    // print_r($_FILES); die;

    $username = $mysqli->real_escape_string($_POST['username']);
    $email = $mysqli->real_escape_string($_POST['email']);
    $password = md5($_POST['password']); //HASHED PASSWORD
    $avatar_path = $mysqli->real_escape_string('images/'.$_FILES['avatar']['name']);  //ADDING OF AVATAR IMAGE

    //make sure file type is image

    if (preg_match("!image!", $_FILES['avatar']['type'])) {

      //copy image to images/ folder
      if (copy($_FILES['avatar']['tmp_name'], $avatar_path)) {

        $_SESSION['username'] = $username;
        $_SESSION['avatar'] = $avatar_path;

        $sql = "INSERT INTO users (username, email, password, avatar)
                VALUES ('$username', '$email', '$password', '$avatar_path')";

        if ($mysqli->query($sql) === true) {
          $_SESSION['message'] = "Registration Successful! Added $username to the database!";
          header("location: welcome.php");

        }
        else {
          $_SESSION['message'] = "User cannot be added to the database!"; 
        }
      }
        else {
          $_SESSION['message'] = "File upload failed!";
        }
    }
        else {
          $_SESSION['message'] = "Please only upload GIF, JPG, or PNG images";
        }   
    }
    else {
      $_SESSION['message'] = "Two passwords did not match!";
    }
}



?>


<!DOCTYPE html>
<html>
<head>
  <title></title>
</head>
<body>
<link href="//db.onlinewebfonts.com/c/a4e256ed67403c6ad5d43937ed48a77b?family=Core+Sans+N+W01+35+Light" rel="stylesheet" type="text/css"/>
<link rel="stylesheet" href="form.css" type="text/css">
<div class="body-content">
  <div class="module">
    <h1>Create an account</h1>
    <form class="form" action="form.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="alert alert-error"><?= $_SESSION['message'] ?></div>
      <input type="text" placeholder="User Name" name="username" required />
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="password" placeholder="Confirm Password" name="confirmpassword" autocomplete="new-password" required />
      <div class="avatar"><label>Select your avatar: </label><input type="file" name="avatar" accept="image/*" required /></div>
      <input type="submit" value="Register" name="register" class="btn btn-block btn-primary" />
    </form>
  </div>
</div>
</body>
</html>