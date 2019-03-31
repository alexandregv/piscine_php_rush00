<?php
require_once('db.php');
include_once('db.php');
session_start();
date_default_timezone_set('UTC');

$basket = unserialize($_COOKIE['basket']);
$pricebyitems = 0;
$bigtotal = 0;
if (isset($_SESSION['id']))
{
    foreach ($basket as $id => $value)
    {
        if ($value > 0)
        {
            $stmt = mysqli_prepare($db, "SELECT * FROM `products` WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $check_stock = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
            //$check_stock = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM `products` WHERE id={$id}"));
            $nombre = $check_stock['stock'];
            echo "<pre>", print_r($check_stock), "\nnombre: ", $nombre, "\nvalue: ", $value, "</pre>";
            if ($value > $nombre)
            {
                header('Location: error.php?reason=stock overflow');
                exit;
            }
        }
    }
    foreach ($basket as $id => $value)
    {
        if ($value > 0)
        {
            $stmt = mysqli_prepare($db, "SELECT * FROM `products` WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            $check_stock = mysqli_fetch_array(mysqli_stmt_get_result($stmt));
            //$check_stock = mysqli_fetch_array(mysqli_query($db, "SELECT * FROM `products` WHERE id={$id}"));
            $nombre = $check_stock['stock'];
            $check_stock['stock'] =  $check_stock['stock'] - $value;
            $newstock = $check_stock['stock']; 
            $pricebyitems = $check_stock['price'] * $value;
            $bigtotal = $bigtotal + $pricebyitems;
            $stmt = mysqli_prepare($db, "UPDATE products SET stock='$newstock' WHERE id = ?");
            mysqli_stmt_bind_param($stmt, "i", $id);
            mysqli_stmt_execute($stmt);
            //$testons = mysqli_query($db, "UPDATE products SET stock='$newstock' WHERE id={$id}");
        }
    }
    $id_client = $_SESSION['id'];
    //echo $id_client, "--";
    $prix_commande = $bigtotal;
    //echo $prix_commande, '--';

    $stmt = mysqli_prepare($db, "INSERT INTO `orders` (`user_id`, `price`) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $id_client, $prix_commande);
    $res = mysqli_stmt_execute($stmt);
    //$res = mysqli_query($db, "INSERT INTO `orders` (`user_id`, `price`) VALUES ('{$id_client}', '{$prix_commande}')");
    setcookie("basket", NULL, 10);
    if ($res == true)
        header ('Location: ordersuccess.php');
    else
        header('Location: error.php?reason=default'); 
}
else
    header('Location: index.php?a_recup=connect&maman=aidemoi');

?>