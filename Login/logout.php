<?php
session_start();

if(!isset($_SESSION['idusuario'])){
    print("<p>No se puede cerrar la sessión, dado que no la ha iniciado</p>");
    print("<a href=\"login.php\">Volver a la página principal del portal de cliente de WetWatter S.L</a>");
}
else{
    $nombre = $_SESSION['nombreusuario'];
    session_unset();
    if(session_destroy()){
        print("<p>Hasta otra ".$nombre.". La sesión se ha cerrado </p>");
        print("<a href=\"login.php\">Volver a la página principal del portal de cliente de WetWatter S.L</a>");
    }
    

}

?>