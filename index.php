<?php
require_once('db.php');
session_start();
include('basket.php');

if (isset($_GET['cancel']) && $_GET['cancel'] == 'cancel')
{
    setcookie("basket", NULL, 10);
}

if (!empty($_GET['category']))
$products = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM products WHERE category_id = {$_GET['category']}"), MYSQLI_ASSOC);
else
  $products = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM products"), MYSQLI_ASSOC);
$categories = mysqli_fetch_all(mysqli_query($db, "SELECT * FROM categories"), MYSQLI_ASSOC);

/*
$stmt = mysqli_prepare($db, "SELECT * FROM categories WHERE name = ?");

$cat = "Homme";
mysqli_stmt_bind_param($stmt, "s", $cat);
mysqli_stmt_execute($stmt);
$categories = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
echo "<pre>", print_r($categories), "</pre>";

$cat = "Femme";
mysqli_stmt_execute($stmt);
$categories = mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
echo "<pre>", print_r($categories), "</pre>";

mysqli_stmt_close($stmt);
exit;
*/
?>

<html>
    <head>
        <title>FootShop</title>
        <link rel="icon" href="ressources/chaussures-running.png" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="css/index.css">
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
 </head>
    <body>
        <nav class="navbar-nav">
            <div class="navbar-logo">
                <a href="index.php"><img src="ressources/chaussures-running.png" alt="logo"></a>
            </div>
            <div class="navbar menu">
                <div class="menu-element">
                    <form id="categories" action="index.php"><input type="hidden" name="a_recup" value="categories"/></form>
                    <a href="#" onclick='document.getElementById("categories").submit()'>Categories</a>
                </div>
                <div class="menu-element">
                    <form id="basket" action="index.php"><input type="hidden" name="a_recup" value="basket"/></form>
                    <a href="#" onclick='document.getElementById("basket").submit()'>Mon panier</a>
                </div>
                <?php if (isset($_SESSION['first_name'])) { ?>
                <div class="menu-element ml-auto">
                    <a href="my_account.php"><?= "{$_SESSION['first_name']} {$_SESSION['last_name']}" ?></a>
                </div>          
                  <div class="menu-element">
                    <a href="logout.php">Se deconnecter</a>
                </div>
                <?php if (isset($_SESSION['admin'])) { ?>
                <div class="menu-element">
                    <a href="admin.php">Panel admin</a>
                </div>   
                <?php }                    
                } else { ?>
                <div class="menu-element ml-auto">
                    <form id="login" action="index.php"><input type="hidden" name="a_recup" value="connect"/></form>
                    <a href="#" onclick='document.getElementById("login").submit()'>Se connecter</a>
                </div>  
                <?php } ?>
            </div>

        </nav>

        <div class=menuelement>
           <form id="panier" action="index.php"><input type="hidden" name="a_recup" value="basket"/></form>
           <a href="#" onclick='document.getElementById("panier").submit()'><h2 class=basket>Panier</h2></a>
           <p><?php if (isset($_COOKIE['basket']) && !(isset($_GET['cancel'])))
           {
              $basket_array = unserialize($_COOKIE['basket']);
              $i = 1;
              foreach ($basket_array as $index => $yolo)
              {
              if ($yolo > 0 && $i++ < 3)
              {
                $stmt = mysqli_prepare($db, "SELECT name FROM `products` WHERE id=?");
                mysqli_stmt_bind_param($stmt, "i", $index);
                mysqli_stmt_execute($stmt);
                $name = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
                if (empty($_GET['a_recup']) || (!empty($_GET['a_recup']) && $_GET['a_recup'] != 'basket'))
                  echo $yolo," ", $name['name'], " in basket <br/>";
              }
              else if ($yolo > 0 && $i >= 3)
                  {
                      echo "...";
                      break;
               }
           }
         }
           ?></p>
       </div>

        <div class="container">
            <!-- Login Page / Register Page-->
            <?php if (isset($_GET['a_recup']) && $_GET['a_recup'] == "connect") { ?>
            <div class="login-container">
            <div class='input'>
                <?php if (!(isset($_SESSION['id']))) {?>
                    <div class="login">
                    <?php if (isset($_GET['maman']))
                    {
                    ?>
                    <h2>YOU MUST CONNECT TO ORDER SOMETHING</h2>
                    <?php
                    }
                    else
                    {?>
                        <h2>Se connecter</h2>
                    <?php
                    }
                    ?>
                        
                        <form method="post" action="connect.php">
                            Mail<br/>
                            <input type="text" name="mail" value="" /><br/>
                            Mot de passe<br/>
                            <input type="password" name="passwd" value ="" /><br/>
                            <input class="btn" type="submit" name="submit" value="Connexion">
                        </form>
                    </div>
                    <hr width="90%">
                    <div class="register">
                        <h2>Creer un compte</h2>
                        <form method="post" action="create.php">
                            Prenom<br/>
                            <input type="text" name="firstname" value="" /><br/>
                            Nom<br/>
                            <input type="text" name="lastname" value="" /><br/>
                            Mail<br/>
                            <input type="text" name="mail" value="" /><br/>
                            Mot de passe<br/>
                            <input type="password" name="passwd" value ="" /><br/>
                            <input class="btn" type="submit" name="submit" value="Inscription">
                        </form> 
                        <form id='connection' action='index.php' method='get'>
                          <button class="btn" style="width: 90%;" type='submit' name='a_recup' value='quit'>Retourner a l'accueil</button>
                         </form>
                    </div>            
                <?php } else { ?>
                    <a href="logout.php">Se deconnecter</a>
                    <form id='connection' action='index.php' method='get'>
                      <button class="btn" type='submit' name='a_recup' value='quit'>Retourner a l'accueil</button>
                    </form>
                <?php }
            } else if (isset($_GET['a_recup']) && $_GET['a_recup'] == "basket") { ?>
            <div class='input'>
                <div class='Connection'>
                <?php 
                    print_basket();
                    ?>
                     <!-- <form id="checkout" action="checkout.php" method="get">
                          <button type='submit' name='checkout' value='send'>checkout</button>
                    </form>-->
                    <p>
                    <form id='connection' action='index.php' method='get'>
                    <button type='submit' name='a_recup' value='quit'>return to homepage</button>
                    </p>
                    </div>
                    </div>
                    <?php
            } else if (isset($_GET['a_recup']) && $_GET['a_recup'] == "categories") { ?>
                <div class='input'>
                    <form id='connection' action='index.php' method='get'>
                      <button class="btn" type='submit' name='a_recup' value='quit'>Retourner a l'accueil</button>
                    </form>
                    <?php foreach ($categories as $category) { ?>
                      <a href="index.php?category=<?= $category['id'] ?>"><?= $category['name'] ?></a><br>
                    <?php } ?> 
                </div>
                <?php 
            } else if (isset($_GET['a_recup']) && $_GET['a_recup'] == "quit" || !(isset($_GET['a_recup']))) { ?>
              <div class="products-container">
                <?php foreach ($products as $product) { ?> 
                  <div class="product">
                    <br/>
                    <form id="addtobasket_<?= $product['id'] ?>" action='addtobasket.php' method='get'>
                       <div class="actions">
                         <?= strtoupper($product['name']); ?><br/>
                         <input type="number" name="<?= "product_" . $product['id'] ?>" min="0" value="0">
                         <input class="btn" type="submit" name="basket_submit" value="Mettre dans le panier">
                       </div>
                    <img src="<?= $product['image'] ?>" alt="<?= $product['name'] ?>" class="images" />
                  </div>
                <?php } ?>
                </form>
              </div>
            <?php } ?>
        </div>
    </body>
</html>