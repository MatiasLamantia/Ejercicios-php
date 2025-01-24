<?php
require_once __DIR__.'/etc/conf.php';
require_once __DIR__.'/src/conn.php';
require_once __DIR__.'/src/dbfuncs.php';

$idpedido=filter_input(INPUT_POST,'idpedido',FILTER_VALIDATE_INT);
$nfp=filter_input(INPUT_POST,'nuevafechapedido',FILTER_VALIDATE_REGEXP,REGEX_VALIDATE_FECHA);
$usercod=filter_input(INPUT_POST,'usercod');
$errors=[];
$resultados=[];
if ($idpedido!==null && $idpedido!==false && $nfp)
{
    list($anyo,$mes,$dia)=explode('-',$nfp);        
    $hoy=date('Y-m-d');    
    if (!checkdate($mes,$dia,$anyo))
    {
        $errors[]="La fecha indicada no es correcta.";
    }
    elseif ($hoy>=$nfp)
    {
        $errors[]="La fecha indicada es anterior al día de hoy.";
    }
    else {
        $pdo=connect();
        if ($pdo===false) die('No se puede conectar con la base de datos.');
        $res=modificarFechaEntregaPedido($pdo,$idpedido,$nfp);
        if ($res===-1 || $res===false)
        {
            $errors[]="No se ha podido realizar la operación.";
        }
        else
        {
            $resultados[]="Se ha modificado la fecha.";
        }
    }
} 
elseif ($nfp===false)
{
    $errors[]="No se ha indicado la fecha.";
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modificación de fecha</title>
</head>
<body>
<?php include __DIR__.'/extra/errors.php'; ?>
<?php include __DIR__.'/extra/resultados.php'; ?>
    <form action="pedidos.php" method="post">
        <input type="submit" value="Volver a la lista de pedidos">
        <?php if ($usercod): ?>
            <input type="hidden" name="usercod" value="<?=$usercod?>">
        <?php endif; ?>
    </form>
</body>
</html>
