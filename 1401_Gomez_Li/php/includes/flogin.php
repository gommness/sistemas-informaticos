<?php

session_start();
// define variables and set to empty values
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
$username = test_input($_POST["username"]);
$password = test_input($_POST["password"]);
//comprueba que existe el usuario

try {
    $db = new PDO("pgsql:dbname=si1; host=localhost", "alumnodb", "alumnodb" );
    /*** use the database connection ***/
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
$sql = 'Select customerid from customers
  where username = \''.$username.'\' and password = \''.$password.'\'
  limit 1';
$resultado = $db->query($sql);
if($resultado->rowCount() == 1){
    echo 'yes';
    $_SESSION["username"] = $username;
    //expira en 1 dia
    setcookie("username", $username, time() + 86400, "/");
    /* TODO mirar pregunta del TOASK referente al saldo de los usuarios
    $consulta = ''
    $_SESSION["saldo"] = fgets($userfile);
    */
    $_SESSION['saldo'] = "0";
    header("Location: ../index.php");
} else {
    echo 'no';
    unset($_SESSION['username']);
    $_SESSION["loginErr"] = "contraseña incorrecta";
    header("Location: ../login.php");
}
pg_free_result($resultado);
$db = null;

/*
//CODIGO ANTIGUO
if (is_dir("../../../usuarios/$username") == FALSE) {
    $_SESSION["loginErr"] = "no existe el usuario";
    fclose($userfile);
    header("Location: ../login.php");
} else {
    $userfile = fopen("../../../usuarios/$username/datos.dat", "r");
    //nos saltamos los campos hasta llegar a password
    fgets($userfile);
    fgets($userfile);
    $hash = fgets($userfile);
    //añado \n para que coincidan passwords
    if (strcmp($hash, md5($password) . "\n") != 0) {
        $_SESSION["loginErr"] = "contraseña incorrecta";
        fclose($userfile);
        header("Location: ../login.php");
    } else {
        $_SESSION["username"] = $username;
        //expira en 1 dia
        setcookie("username", $username, time() + 86400, "/");
        //nos saltamos numero cuenta bancaria
        fgets($userfile);
        $_SESSION["saldo"] = fgets($userfile);
        fclose($userfile);
        header("Location: ../index.php");
    }
}
*/

//TODO javascript to go to another url (index but being logged)
//}

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>
