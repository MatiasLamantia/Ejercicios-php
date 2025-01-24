<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comerciales</title>
</head>
<body>
    <h1>Nuestros Comerciales </h1>
    <?php
        require_once "etc/conf.php";
        include "amigo.php";
    ?>s


    <ul>
    <?php
    $nomostrar;
        if(!isset( $_GET['comercial'])){

            print("<li><A href=\"http://localhost/dwes01/comerciales.php?comercial=".urlencode(COMERCIAL1) ."\">" . COMERCIAL1 . "</A></li>");
            print("<li><A href=\"http://localhost/dwes01/comerciales.php?comercial=".urlencode(COMERCIAL2) ."\">" . COMERCIAL2 . "</A></li>");
            print("<li><A href=\"http://localhost/dwes01/comerciales.php?comercial=".urlencode(COMERCIAL3) ."\">" . COMERCIAL3 . "</A></li>");
            print("<li><A href=\"http://localhost/dwes01/comerciales.php?comercial=".urlencode(COMERCIAL4) ."\">" . COMERCIAL4 . "</A></li>");
        }   

        else{
            switch($_GET['comercial']){

                case COMERCIAL1 :
                    include("frag/comercial1.php");
                    break;
                
                case COMERCIAL2 :
                    include("frag/comercial2.php");
                    break;
                
                case COMERCIAL3 :
                    include("frag/comercial3.php");
                    break;
                
                case COMERCIAL4 :
                    include("frag/comercial4.php");
                    break;
                }


      
        }

    ?>
    </ul>

    <?php
        include "amigo.php";
    ?>
</body>
</html>