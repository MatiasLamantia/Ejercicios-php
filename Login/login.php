<?php


    function validarusuario($usuario,$password){
        //Consulto todos los usuarios de la bd
        $password = $usuario.$password;
        $password = hash('sha256',$password);

        $pdo = new PDO('mysql:host=localhost;dbname=pedidos', "root", "");
        $sql = 'SELECT * FROM usuarios WHERE cod=:codusuario AND password=:passw';

        $stmt = $pdo->prepare($sql);
        $data=['codusuario'=>$usuario,'passw'=>$password];

        if ($stmt->execute($data)) {
            $ret=$stmt->fetchAll(PDO::FETCH_ASSOC);
        } 

        if(count($ret) == 0){
            $salida = false;
        }
        else{
            $salida = $ret;
        }

        return $salida;
    }



    if(isset($_POST["usuario"]) && isset($_POST["password"])){

        $usuario = $_POST["usuario"];
        $password = $_POST["password"];

        $userinfo = validarusuario($usuario,$password);


            if($userinfo != false){
                session_start();
                $_SESSION['idusuario'] = $userinfo[0]['id'];
                $_SESSION['codusuario'] = $usuario;
                $_SESSION['nombreusuario'] = $userinfo[0]['nombre'];
                $_SESSION['tiempousuario'] = time();

               print("<h3>Bienvenido ".$_SESSION['nombreusuario'] . " , valla a la <a href=\"index.php\">Página Principal</a> para ver sus pedidos");

            }
            else{
                print("El usuario no es correcto");
            }
        }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
    if(!isset($_SESSION["usuario"]) && !isset($_SESSION["password"])):?>
    <h2>Formulario de Login</h2>
    <form action="" method="POST">
        <label for="usuario">Código de usuario :</label>
        <input type="text" name="usuario" id="password"><br>

        <label for="password">Password</label>
        <input type="password" name="password" id="password"><br>

        <input type="submit" name="submit" value="Enviar!">
    </form>
    <?php endif; ?>
</body>
</html>