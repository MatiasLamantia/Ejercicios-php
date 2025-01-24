<!DOCTYPE html>
<html lang="es">
<body>

<?php
session_start();

$expirasession = 120;
if (isset($_SESSION['tiempousuario']) && (time() - $_SESSION['tiempousuario'] > $expirasession)) {
    session_unset();
    session_destroy();  
    header("Location: login.php");
    exit();
}

$_SESSION['tiempousuario'] = time();

function asignarPassword($passwordAntigua , $passwordNueva){


    $pdo = new PDO('mysql:host=localhost;dbname=pedidos', "root", "");
    $sql = 'SELECT password FROM usuarios WHERE id=:idusuario';

    $idusuario = $_SESSION["idusuario"];
    $stmt = $pdo->prepare($sql);
    $data = ['idusuario' => $idusuario];

    if ($stmt->execute($data)) {
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($ret);
    }

    $contraseñacifrada = hash('sha256',$_SESSION["codusuario"].$passwordAntigua);
    if ($contraseñacifrada == $ret[0]["password"]) {

        $nuevaContraseñaCifrada = hash('sha256', $_SESSION["codusuario"] . $passwordNueva);

        $sql = 'UPDATE usuarios SET password=:password WHERE id=:idusuario';
        $stmt = $pdo->prepare($sql);
        $data = ['password' => $nuevaContraseñaCifrada,'idusuario' => $idusuario
        ];

        if ($stmt->execute($data)) {
            return true;
        } else {
           return false;
        }


    } else {
        echo "<h3>La contraseña antigua no es correcta</h3>";
    }

}
if (count($_POST) > 0) {



    if (isset($_POST["passwdantigua"]) && isset($_POST["passwdnueva1"]) && isset($_POST["passwdnueva2"])) {

        if ($_POST["passwdnueva1"] !== $_POST["passwdnueva2"]) {
            print("<h3>Las contraseñas no coinciden</h3>");
        } else {
            if(asignarPassword($_POST["passwdantigua"],$_POST["passwdnueva1"])){
                print("<h3>La contraseña se ha cambiado correctamente</h3>");
            }
            else{
                print("<h3>Ha habido un error al cambiar la contraseña</h3>");
            }
        }
    } 
    else {
        print("<h3>Faltan Parámetros</h3>");
    }
}

if (!isset($_SESSION['idusuario'], $_SESSION['codusuario'], $_SESSION['nombreusuario'])):
    header('Location: login.php');
else:
    ?>
    <p>Bienvenido <?= $_SESSION['nombreusuario'] ?></p>
    <p>En esta página puede cambiar su contraseña, vaya a la <a href="index.php">página principal</a> para ver sus pedidos</p>

    <form action="" method="POST">
        <label for="passwdantigua">Password antiguo</label>
        <input type="password" name="passwdantigua"><br>

        <label for="passwdnueva1">Password nuevo</label>
        <input type="password" name="passwdnueva1"><br>

        <label for="passwdnueva2">Repetición password nuevo</label>
        <input type="password" name="passwdnueva2"><br>

        <input type="submit" value="enviar!">
    </form>
<?php endif; ?>
</body>
</html>