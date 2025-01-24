<?php
require_once __DIR__.'/etc/conf.php';
require_once __DIR__.'/src/conn.php';
require_once __DIR__.'/src/dbfuncs.php';

$usercod=filter_input(INPUT_POST,'usercod');
$fechaentrega=filter_input(INPUT_POST,'fechaentrega',FILTER_VALIDATE_REGEXP,REGEX_VALIDATE_FECHA);
$errors=[];
$resultados=[];
if ($usercod!==null)
{    
    $pdo=connect();
    if ($pdo===false) die('No se puede conectar con la base de datos.');
    if ($fechaentrega!==null && $fechaentrega!==false)
    {        
        list($anyo,$mes,$dia)=explode('-',$fechaentrega);        
        $hoy=date('Y-m-d');
        if (!checkdate($mes,$dia,$anyo))
        {
            $errors[]="La fecha indicada no es válida.";
        }
        elseif($hoy>=$fechaentrega)
        {
            $errors[]="La fecha indicada es del pasado.";
        }
        else {
            $res=nuevoPedido($pdo, $usercod, $fechaentrega);    
            if ($res===0 || $res===false || $res===-1)
            {
                $errors[]="No se ha podido crear el pedido.";
            }
            else 
            {
                $resultados[]="Se ha creado el pedido.";
            }
        }

    }
    $listaPedidos=listaDePedidosPorCliente($pdo,$usercod);    
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de pedidos por cliente</title>
    <style>
table {font-family: helvetica; border-spacing:0px;width:100%}
td, th {border:  1px solid;
      padding: 10px;      
      background: white;
      box-sizing: border-box;
      text-align: left;
}

thead th {
  background: hsl(20, 50%, 70%);
}

tfoot {
  bottom: 0;
  z-index: 2;
}

tfoot td {
  background: hsl(20, 50%, 70%);
}

</style>
</head>
<body>
    <form action="" method="post">
        <input type="hidden" name="op" value="selectuser">
        <label for="usercod">Indique el cliente: <input type="text" name="usercod" id="usercod"></label>
        <input type="submit" value="Buscar pedidos del cliente">
    </form>
<?php include __DIR__.'/extra/errors.php'; ?>
<?php include __DIR__.'/extra/resultados.php'; ?>
<?php if(isset($listaPedidos)): ?>
    <h3>Lista de pedidos del cliente <?=$usercod?></h3>
    <table>
        <thead>
        <tr>
            <th>Codigo de cliente</th>
            <th>Nombre de cliente</th>
            <th>ID de pedido</th>
            <th>Fecha del pedido</th>
            <th>Fecha de entrega</th>
            <th>Cambiar fecha de entrega</th>
            <th>Editar Pedido</th>
            <th>Borrar Pedido</th>
        </tr>
</thead>
        <?php foreach($listaPedidos as $pedido): ?>
        <tr>
            <td><?=$pedido['codigousuario'];?></td>
            <td><?=$pedido['nombreusuario'];?></td>
            <td><?=$pedido['idpedido'];?></td>
            <td><?=$pedido['fechapedido'];?></td>
            <td><?=$pedido['fechaentrega'];?></td>
            <td><form action="modificarpedido.php" method="post">
                <input type="text" name="nuevafechapedido">
                <input type="hidden" name="idpedido" value="<?=$pedido['idpedido'];?>">
                <input type="hidden" name="usercod" value="<?=$pedido['codigousuario'];?>">
                <input type="submit" value="Modificar fecha">
            </form></td>
            <td><form action="editarpedido.php" method="post">
                <input type="hidden" name="idpedido" value="<?=$pedido['idpedido'];?>">
                <input type="hidden" name="usercod" value="<?=$pedido['codigousuario'];?>">
                <input type="submit" value="Editar">
            </form></td>
            <td><form action="borrarpedido.php" method="post">
                <input type="hidden" name="idpedido" value="<?=$pedido['idpedido'];?>">
                <input type="hidden" name="usercod" value="<?=$pedido['codigousuario'];?>">
                <input type="submit" value="Eliminar!">
            </form></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <H2>Nuevo pedido</H2>
    <form action="" method="post">
        <label for="fechaentrega">Fecha de entrega:<input type="text" name="fechaentrega" id="fechaentrega">(formato YYYY-MM-DD)</label>        
        <input type="submit" value="Crear pedido" ?>
        <input type="hidden" name="usercod" value="<?=$usercod?>">
    </form>
<?php endif; ?>
</body>
</html>