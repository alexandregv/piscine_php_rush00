<?php
require_once('db.php');

function print_basket()
{
  include('db.php');
if (isset($_COOKIE['basket']))
{
  $basket_array = unserialize($_COOKIE['basket']);
  $bigtotal = 0;
  echo "<div class='tata first'></div>";
  foreach ($basket_array as $index => $yolo)
  {
    if ($yolo > 0)
    {
      $stmt = mysqli_prepare($db, "SELECT name FROM `products` WHERE id=?");
      mysqli_stmt_bind_param($stmt, "i", $index);
      mysqli_stmt_execute($stmt);
      $name = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
      //$test = mysqli_query($db, "SELECT name FROM `products` WHERE id={$index}");
      //$name = mysqli_fetch_array($test);

      $stmt = mysqli_prepare($db, "SELECT price FROM `products` WHERE id=?");
      mysqli_stmt_bind_param($stmt, "i", $index);
      mysqli_stmt_execute($stmt);
      $price = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
      //$test = mysqli_query($db, "SELECT price FROM `products` WHERE id={$index}");
      //$price = mysqli_fetch_array($test);

      $one_price = $price['price'];
      $price_total = $one_price * $yolo;
      $bigtotal = $price_total + $bigtotal;
      echo "<div class='tata'>", $yolo," ", $name['name'], " in basket = ", $price_total, "€ (", $one_price, "€ each)</div>";
    }
  }
  echo "<div class='tata'> TOTAL = ", $bigtotal, "€</div><div class='tata first last'><form id='cancel' action='index.php' method='get'><button type='submit' name='cancel' value='cancel'>cancel</button></form><form id='checkout' action='checkout.php' method='get'><button type='submit' name='checkout' value='send'>checkout</button></form></div>";
}
else
echo "<p> empty basket </p>";
}

function checkout()
{
  if (is_connect())
  {
    //query + update panier
    //query
  }
  else
  header('Location: error_connect_checkout.php');
}
?>
