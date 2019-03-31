<?php
switch ($_GET['reason']){
  case "bad_credentials":
    $errmsg = "Identifiants invalides"; break;
  case "missing_credentials":
    $errmsg = "Identifiants manquants"; break;
  case "not_connected":
    $errmsg = "Vous devez vous connecter pour valider votre commande"; break;
  case "account_suspended":
    $errmsg = "Votre compte est suspendu"; break;
  case "not_admin":
    $errmsg = "Espace réservé aux admins"; break;
  case "stock overflow":
    $errmsg = "Vous avez selectionné trop d'articles, veuillez réessayer avec un nombre moins important"; 
    setcookie("basket", NULL, 10);
    break;
  case "invalid_product":
    $errmsg = "Produit invalide !";
  default:
    $errmsg = "Une erreur s'est produite"; break;
}
?>
<html>

<Head>
  <style>
    .logo {
      display: block;
      width: 200px;
      margin-left: auto;
      margin-right: auto;
    }

    .milieu {
      display: block;
      width: 920px;
      height: 200vh;
      margin-top: 0px;
      margin-left: auto;
      margin-right: auto;
      background-color: #AFAFAF;
      padding-top: 1vh;

    }

    h1{
      color: white;
      text-align: center;
      font-size: 100px;
    }

    body {
      padding: 0px;
      background-color: #616161;
      font-family: Tahoma, Geneva, Kalimati, sans-serif;
    }

.input{
                position: absolute;
                width: 920px;
                margin-top: 50px;
                text-align: center;
                display: flex;
                align-items: center;
            }

    .connection{
                position: relative;
                display: block;
                margin: auto;
                width: 600px;
                height: 600px;
                display: inline-block;
                background-color: #D3D3D3;
            }
  </style>
</Head>

<body>
  <div class=milieu>
    <img src="ressources/chaussures-running.png" alt="logo" class="logo" />
    <div class="input">
    <div class=connection>
    <h1>
      ERROR
    </h1>
      <?PHP
    echo "<p>".$errmsg."</p>"."\n";
    ?>
      <form action="index.php">
        <input type="submit" name="BACK" value="BACK">
      </form>
    </div>
          </div>
  </div>
</body>

</html>
