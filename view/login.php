<?php
include_once 'server/dbconnect.php';
if(isset($_SESSION['user'])!="")
{
    header("Location: ./index.php");
}
if(isset($_POST['btn-login']))
{
    $email = trim($_POST['email']);
    $upass = trim($_POST['pass']);
    $selectQuery=$db->prepare("SELECT * FROM users WHERE email=:email");
    $selectQuery->execute(['email'=>$email]);
    $items=$selectQuery->rowCount() ? $selectQuery : [];
    foreach($items as $item){
        if($item['password']==md5($upass))
        {
            $_SESSION['user'] = $item['id'];
            header("Location: ./index.php");
        }
    }
}
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-centered">
            <h1 class="text-center">Login</h1>
            <form class="form-signin" method="post">
                <label for="inputEmail">Email</label>
                <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email address" required="" autofocus="">
                <label for="inputPassword">Password</label>
                <input type="password"  name="pass"  id="inputPassword" class="form-control" placeholder="Password" required="">
                <button name="btn-login" class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
            </form>
            <br>
            <a href="view/register.php">Sign up</a>
        </div>
    </div>
</div>