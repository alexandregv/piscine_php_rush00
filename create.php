<?PHP
require_once("db.php");
require_once("session.php");
session_start();

if (isset($_POST["firstname"]) && isset($_POST["lastname"]) && isset($_POST["mail"]) && isset($_POST["passwd"]))
{
    $mymail = $_POST['mail'];
    $myfname = $_POST['firstname']; 
    $mylname = $_POST['lastname'];
    $mypasswd = hash('whirlpool', $_POST['passwd'] . 'salt');
    
    $stmt = mysqli_prepare($db, "SELECT * FROM `users` WHERE mail = ?");
    mysqli_stmt_bind_param($stmt, "s", $mymail);
    mysqli_stmt_execute($stmt);
    $tab = mysqli_fetch_all(mysqli_stmt_get_result($stmt));
    //$res = mysqli_query($db, "SELECT * FROM `users` WHERE mail = '$mymail'");
    //$tab = mysqli_fetch_all($res);
    if (empty($tab))
    {
        $stmt = mysqli_prepare($db, "INSERT INTO `users` (`first_name`, `last_name`, `mail`, `password`, `admin`) VALUES (?, ?, ?, ?, '0')");
        mysqli_stmt_bind_param($stmt, "ssss", $myfname, $mylname, $mymail, $mypasswd);
        mysqli_stmt_execute($stmt);
        $res = mysqli_stmt_get_result($stmt);
        //$res = mysqli_query($db, "INSERT INTO `users` (`first_name`, `last_name`, `mail`, `password`, `admin`) VALUES ('{$myfname}', '$mylname', '$mymail', '$mypasswd', '0')");

        $stmt = mysqli_prepare($db, "SELECT id, admin FROM `users` WHERE mail = ?");
        mysqli_stmt_bind_param($stmt, "s", $mymail);
        mysqli_stmt_execute($stmt);
        $tab = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
        //$res = mysqli_query($db, "SELECT id, admin FROM `users` WHERE mail='{$mymail}'");
        //$tab = mysqli_fetch_assoc($res);
        var_dump($tab);
        session_log_user($tab['id'], $myfname, $mylname, $mymail, $tab['admin']);
        header('Location: index.php');
    }
    else
    header('Location: error.php');
}
else
header('Location: error.php');

?>