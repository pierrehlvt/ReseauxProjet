<?php
session_start();
require('inc/pdo.php');
require('inc/function.php');
$title = 'Connexion';

include('inc/header.php'); ?>

<h1>Connexion</h1>


<?php
$errors = array();
$success = false;


if(!empty($_POST['submited'])) {
  // fail xss
  $login = trim(strip_tags($_POST['login']));
  $password = trim(strip_tags($_POST['password']));

  if (empty($login)) {
    $errors['login'] = 'Veuillez renseigner ces champs';
  } else {
    $sql = "SELECT * FROM users WHERE pseudo = :login OR email = :login";
    $query = $pdo->prepare($sql);
    $query->bindValue('login', $login, PDO::PARAM_STR);
    $query->execute();
    $user = $query->fetch();

    if(!empty($user)) {
      debug($user);

    if(password_verify($password, $user['password'] )) {

      $_SESSION['login'] = array(
        'id'      => $user['id'],
        'pseudo'  => $user['pseudo'],
        'role'    => $user['role'],
        'ip'      => $_SERVER['REMOTE_ADDR'],
      );

      // debug($_SESSION);
      header ('Location : index.php');
      } else {
      $errors['login'] = 'pseudo or email inconnu';
    }
  }
}
}

?>

<form class="" action="login.php" method="post">
  <label for="login">Pseudo or email *</label>
  <input type="text" name="login" id="login" value="<?php if(!empty($_POST['login'])) { echo $_POST['login']; } ?>">
  <p class="error"><?php if(!empty($errors['login'])) { echo $errors['login']; } ?></p>

  <label for="login">Mot de passe *</label>
  <input type="password" name="password" id="password" value="<?php if(!empty($_POST['login'])) { echo $errors['login']; }  ?> ">

  <input type="submit" name="submited" value="Connexion">

</form>
<a href="forget_password.php">Mot de passe oublié</a>


<?php
include('inc/footer.php');