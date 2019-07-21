<?php
  session_start();
  require_once "pdo.php";
  // echo "php works!";

  if (isset($_POST['loginemail']) &&
      isset($_POST['loginpass']) ) {

    $useremail = $_POST['loginemail'];
    $userpass = $_POST['loginpass'];

    $userpasshash = password_hash($userpass, PASSWORD_DEFAULT);
    // echo "<br> Password: ".$userpass;
    // echo "<br> Hash: ".$userpasshash;

    $stmt_user_credentials = $pdo->prepare('SELECT id, cname, cpasshash FROM customer WHERE cemail LIKE :email');
    $stmt_user_credentials->execute(array(':email' => $useremail));

    $row_user_credentials = $stmt_user_credentials->fetch(PDO::FETCH_ASSOC);
    $username_fromdb = $row_user_credentials['cname'];
    $userid_fromdb = $row_user_credentials['id'];
    $actualpasshash = $row_user_credentials['cpasshash'];
    // echo "<br> Hello ".$username_fromdb;
    // echo "<br> Passhash from DB: ".$actualpasshash;

    if (password_verify($userpass, $actualpasshash)) {
      // echo "<br> Passwords match!";
      $_SESSION['username'] = $username_fromdb;
      $_SESSION['userid'] = $userid_fromdb;

      // echo "<br> USER ID: ".$_SESSION['userid'];

      // TODO: dicey if condition; change
      if (isset($_SESSION['return_addr'])) {
        // header('Location: '.$_SESSION['return_addr']);
        header('Location: cart.php');
        return;
      } else {
        header('Location: index.php');
        return;
      }
    } else {
      // echo "<br> Passwords DO NOT match!";
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <?php include "htmlhead.php"; ?>
    <title>Login | Shopping Cart</title>
  </head>
  <body>
    <?php include "navigation.php"; ?>
    <div class="container">
    <h1>Login</h1>

    <form class="" action="login.php" method="POST">
      <div class="form-group">
        Email Address: <input type="text" class="form-control" name="loginemail" placeholder="Enter email">
      </div>
      <div class="form-group">
        Password: <input type="text" class="form-control" name="loginpass" placeholder="Password">
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-outline-primary" name="button">Log In</button>
      </div>
    </form>

    <a href="signup.php">Sign Up</a>
    </div>
  </body>
</html>
