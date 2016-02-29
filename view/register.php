<?php
include_once '../server/dbconnect.php';
if(isset($_SESSION['user'])!=""){
    header("Location: ../index.php");
}
if(isset($_POST['btn-signup']))
{
    $uname=trim($_POST['uname']);
    $email=trim($_POST['email']);
    $upass=md5($_POST['pass']);
    $addedQuery=$db->prepare("INSERT INTO users(username,email,password) VALUES(:name,:email,:pass)");
    $addedQuery->execute(['name'=>$uname,'email'=>$email,'pass'=>$upass]);
    header("Location: ../index.php");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Login & Registration System</title>

<link rel="stylesheet" href="../css/bootstrap.css">
<!-- Optional theme -->
<link rel="stylesheet" href="../css/bootstrap-theme.min.css">
<link rel="stylesheet" href="../css/custom.css">

<!-- Latest compiled and minified JavaScript -->
<script src="../js/jquery-2.2.0.js"></script>
<script src="../js/bootstrap.min.js"></script>


</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <a class="navbar-brand" href="#">TODO RUBYGARAGE</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">

            <ul class="nav navbar-nav navbar-right">
                <li><a href="../view/register.php">Sign up</a></li>
                <li><a href="../index.php" >Login</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>

<div class="container">
    <div class="row">
                <div class="col-md-4 col-centered">
                    <h1 class="text-center">Login</h1>
                    <form class="form-signin" method="post">
                        <label for="inputName">Name</label>
                        <input type="text" name="uname" id="inputName" class="form-control" placeholder="Your name" required="true" autofocus="">
                        <label for="inputEmail">Email</label>
                        <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
                        <label for="inputPassword">Password</label>
                        <input type="password"  name="pass"  id="inputPassword" class="form-control" placeholder="Password" required="">

                        <button name="btn-signup" class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
                    </form>
                    <br>

                </div>
            </div>
        </div>
</body>
</html>
