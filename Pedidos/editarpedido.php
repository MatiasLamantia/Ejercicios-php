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
        $listaPedidos=listaDePedidosPorCliente($pdo,$usercod);
    if (isset($_POST['producto']) && isset($_POST['unidades']) && isset($_POST['idpedido'])) {
        $producto = filter_input(INPUT_POST,'producto');
        $unidades = filter_input(INPUT_POST,'unidades',FILTER_VALIDATE_INT);
        $idpedido = filter_input(INPUT_POST,'idpedido',FILTER_VALIDATE_INT);
        if ($producto===null || $unidades===null || $idpedido===null) {
            $errors[]='Falta una data.';
        } else {
            $ret = nuevaLineaPedido($pdo, $idpedido, $producto, $unidades);
            if ($ret===false) {
                $errors[]='No se ha podido añadir el producto al pedido.';
            } else {
                $resultados[]='Se ha añadido el producto al pedido.';
            }
        }
    }
    if (isset($_POST['idlineapedido']) && isset($_POST['idpedido'])) {
        $idlineapedido = filter_input(INPUT_POST,'idlineapedido',FILTER_VALIDATE_INT);
        $idpedido = filter_input(INPUT_POST,'idpedido',FILTER_VALIDATE_INT);
        if ($idlineapedido===null || $idpedido===null) {
            $errors[]='Falta una data.';
        } else {
            $ret = borrarLineaPedido($pdo, $idlineapedido);
            if ($ret===false) {
                $errors[]='No se ha podido borrar el producto del pedido.';
            } else {
                $resultados[]='Se ha borrado el producto del pedido.';
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
<?php 
	$listaProductosPedido = obtenerLineasPedido($pdo, $_POST['idpedido']);
	if(isset($listaPedidos)): ?>
    <h3>Editando el pedido <?php echo $_POST['idpedido']; ?></h3>
    <table>
        <thead>
        	<tr>
            	<th>Cod. Producto</th>
            	<th>Descripción</th>
            	<th>Precio Unidad</th>
            	<th>Unidades</th>
            	<th>Coste</th>
        	</tr>
		</thead>
        <?php $totalPedido = 0;
			foreach($listaProductosPedido as $producto):?>
        <tr>
			<td>
                <form action="editarpedido.php" method="post">
                    <input type="hidden" name="idlineapedido" value="<?=$producto['idlineapedido']?>">
                    <input type="hidden" name="idpedido" value="<?=$_POST['idpedido']?>">
                    <input type="hidden" name="usercod" value="<?=$_POST['usercod']?>">
                    <input type="submit" value="Eliminar">
                </form>
                <?=$producto['codprod']?>
            </td>
			<td><?=$producto['descripcion']?></td>
			<td><?=$producto['precio']?></td>
			<td><?=$producto['unidades']?></td>
			<td><?=$producto['precio']*$producto['unidades']?></td>
			<?php $totalPedido += $producto['precio']*$producto['unidades']; ?>
        </tr>
        <?php endforeach; ?>
		<tfoot>
			<tr>
				<td colspan="4">Total:</td>
				<td><?=$totalPedido?></td>
			</tr>
			<tr>
				<?php $ivaPedido = $totalPedido * 0.21; ?>
				<td colspan="4">Iva:</td>
				<td>21%</td>
			</tr>
			<tr>
				<td colspan="4">Total con IVA:</td>
				<td><?=$totalPedido + $ivaPedido?></td>
            </tr>
		</tfoot>
    </table>
	<br>
	<?php $listaProductos = listaDeProductos($pdo) ?>
	<form action="editarpedido.php" method="post">
		Producto:
		<select name="producto">
			<?php foreach($listaProductos as $producto): ?>
			<option value="<?=$producto['idproducto']?>"><?=$producto['codproducto']?> - <?=$producto['descproducto']?> -- Stock: <?=$producto['stockproducto']?></option>
			<?php endforeach; ?>
		</select>
		 Unidades: <input type="number" name="unidades" min="1" max="200">
        <input type="hidden" name="usercod" value="<?=$_POST['usercod']?>">
		<input type="hidden" name="idpedido" value="<?=$_POST['idpedido']?>">
		<input type="submit" value="Añadir!">
	</form>
<?php endif; ?>
</body>
</html>
