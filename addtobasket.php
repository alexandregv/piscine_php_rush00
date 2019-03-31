<?php
require_once('db.php');

if (!empty($_GET['basket_submit']) /*&& !empty($_GET['basket_id']) && !empty($_GET['basket_quantity'])*/)
{
    $array = $_GET;
    /*$res = mysqli_query($db, "SELECT * FROM `products` WHERE name='{$index}'");
    $stock = mysqli_fetch_assoc($res);*/
    if (isset($_COOKIE['basket']))
    {
        $basket = unserialize($_COOKIE['basket']);
       // echo "<pre>";
       // print_r($_GET);
       // echo "</pre>";
       echo "<pre>";
       print_r($basket);
       echo "</pre>";
        foreach ($_GET as $index => $elem)
        {
            if (intval($elem) > 0)
            {
              $basket[substr($index, 8)] = $basket[substr($index, 8)] + intval($elem);
            }
        }
        echo "<pre>", print_r($basket), "</pre>";
        //echo "<pre>";
        //print_r($basket);
        //echo "</pre>";
       setcookie('basket', serialize($basket), time() + 86400);
       header('Location: index.php');
    }
   else
    {
      $basket = array();
      foreach ($_GET as $var => $val)
      {
        echo "<pre>", print_r($val), "</pre>";
        echo "<pre>", print_r($_GET), "</pre>";
        if (substr($var, 0, 8) === 'product_')
        {
          echo "on met ", substr($var, 8), " x ", $val;
          //$basket[] = array(substr($var, 8), $val);
          $basket[intval(substr($var, 8))] = $val;
        }
      }
      echo "<pre>", print_r($basket), "</pre>";
      setcookie('basket', serialize($basket), time() + 86400);
      header('Location: index.php');
    }
    //else echo "we are very sorry but our stock of shoes is running low please try a lower amount.\n";
}
else echo "emptyvalue\n";

?>
