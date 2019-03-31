<?php
require_once('db.php');
require_once('session.php');
session_start();

if (empty($_SESSION['id']))
  header('Location: error.php?reason=not_connected');

if (!empty($_GET['action']))
{
  if ($_GET['action'] == "suspend" || $_GET['action'] == "delete" || (!empty($_GET['first_name']) && !empty($_GET['last_name']) && !empty($_GET['mail']) && !empty($_GET['password'])))
	{
    if ($_GET['action'] == 'Modifier mes infos')
    {
      $stmt = mysqli_prepare($db, "UPDATE users SET `first_name` = ?, `last_name` = ?, `mail` = ?, `password` = ? WHERE id = ?");
      mysqli_stmt_bind_param($stmt, "ssssi", $_GET['first_name'], $_GET['last_name'], $_GET['mail'], hash('whirlpool', $_GET['password'] . 'salt'), $_GET['id']);
      mysqli_stmt_execute($stmt);
      $_SESSION['first_name'] = $_GET['first_name'];
	    $_SESSION['last_name'] = $_GET['last_name'];
	    $_SESSION['mail'] = $_GET['mail'];
      //mysqli_query($db, "UPDATE users SET `first_name` = '{$_GET['first_name']}', `last_name` = '{$_GET['last_name']}', `mail` = '{$_GET['mail']}', `password` = '" . hash('whirlpool', $_GET['password'] . 'salt') . "' WHERE id = {$_GET['id']}");
    }
    elseif ($_GET['action'] == 'suspend')
    {
      session_destroy();
      setcookie('PHPSESSID', NULL, 42, '/');
      $stmt = mysqli_prepare($db, "UPDATE users SET `suspended` = '1' WHERE id = ?");
      mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
      mysqli_stmt_execute($stmt);
      //mysqli_query($db, "UPDATE users SET `suspended` = '1' WHERE id = {$_SESSION['id']}");
    }
    elseif ($_GET['action'] == 'delete')
    {
      session_destroy();
      setcookie('PHPSESSID', NULL, 42, '/');
      $stmt = mysqli_prepare($db, "DELETE FROM users WHERE id = ?");
      mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
      mysqli_stmt_execute($stmt);
      //mysqli_query($db, "DELETE FROM users WHERE id = {$_SESSION['id']}");
    }
    header('Location: my_account.php');
  }
  else header('Location: error.php?reason=missing_credentials');
}

$stmt = mysqli_prepare($db, "SELECT * FROM users where id = ?");
mysqli_stmt_bind_param($stmt, "i", $_SESSION['id']);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
//$user = mysqli_fetch_assoc(mysqli_query($db, "SELECT * FROM users where id = {$_SESSION['id']}"));
?>

<html>
	<head>
  	<title>Mon compte</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
    <style>
			body {
      	padding: 0px;
      	background-color: #616161;
         font-family: Tahoma, Geneva, Kalimati, sans-serif;
         display: inline-block;
         margin: auto;
      }
			
			.box	{
				border: 3px solid;
        background-color: #D3D3D3;
      }

		  .new-product {
        margin-top: 5px;
        background-color: #D3D3D3;
				border: 3px solid;
        clear: both;
      }
      .new-product input {
        width: 100%;
      }

      .btn {
        margin-top: 3px;
        height: 25px;
        border-radius: 15px;
      }

      .btn:hover {
        background-color: grey;
      }
		</style>
	</head>
	<body>
    <a href="index.php">Retour a l'accueil</a>
    <div class="box">
		  <form method="get" action="my_account.php">
        <label for="first_name">Prenom: </label><input type="text" name="first_name" value="<?= $user['first_name'] ?>"><br>
        <label for="first_name">Nom: </label><input type="text" name="last_name" value="<?= $user['last_name'] ?>"><br>
        <label for="first_name">Mail: </label><input type="text" name="mail" value="<?= $user['mail'] ?>"><br>
		  	<label for="first_name">Mot de passe: </label><input type="password" name="password" value=""><br>
		  	<input type="hidden" name="id" value="<?= $user['id'] ?>">
		  	<input class="btn" type="submit" name="action" value="Modifier mes infos">
      </form>
    </div>
    <button onclick="confirm_popup('suspend')">/!\ Suspendre mon compte /!\</button>
    <button onclick="confirm_popup('delete')">/!\ Supprimer mon compte /!\</button>
    <script>
      function confirm_popup(mode) {
        if (confirm("Etes-vous sur ?") == true)
        {
          if (mode == 'suspend')
            window.location.replace("my_account.php?action=suspend");
          if (mode == 'delete')
            window.location.replace("my_account.php?action=delete");
        }
      }
    </script>
  </body>
</html