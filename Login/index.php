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


    function listaDePedidosPorCliente(PDO $pdo, $codcliente) {
        $sql='SELECT pedidos.id as idpedido, pedidos.fechapedido as fechapedido,'
             .' pedidos.fechaentrega as fechaentrega, pedidos.idusuario as idusuario,'
             .' usuarios.cod as codigousuario, usuarios.nombre as nombreusuario '
             .' FROM pedidos left join usuarios on usuarios.id=pedidos.idusuario '
             .' WHERE usuarios.cod=:codcliente';
             $ret=false;
             try {
                 $stmt = $pdo->prepare($sql);
                 $data=['codcliente'=>$codcliente];
                 if ($stmt->execute($data)) {
                         $ret=$stmt->fetchAll(PDO::FETCH_ASSOC);                       
                 } 
             } catch (PDOException $ex) {
                 $ret=-1;
             } 
             return $ret;
    }


    $usercod = $_SESSION['codusuario'];
    $pdo = new PDO('mysql:host=localhost;dbname=pedidos', "root", "");
    $listaPedidos=listaDePedidosPorCliente($pdo,$usercod);    
?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <title>Portal del cliente de WetWater S.L.</title>
        <link rel="stylesheet" href="css/styles.css">
        <link href="assets/fontawesome/css/all.min.css" rel="stylesheet"  type="text/css">
    <body>

<?php if(!isset($_SESSION['idusuario'],$_SESSION['codusuario'])):?>
    <h1>Portal del cliente del WetWater S.L.</h1>
    <p><a href="ej3.php">Consulte nuestra lista de productos</a>
    <p>No se ha autenticado en el portal del cliente de Wet Water S.L. o se ha expirado el tiempo de sesión</p>
    <p>Dirigase a la <a href="login.php">página de autenticación</a></p>
<?php else:?>

    <H1>Portal del cliente de WetWater S.L.</H1>
    <A href="ej3.php">Consulte nuestra lista de productos</A>
    <H2>Bienvenido <?=$_SESSION['nombreusuario'];?> <a href="logout.php" alt="Cerrar Sesión"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
    <a href="password.php"><i class="fa-solid fa-user-pen"></i></a>.</H2>
    <P> Haga click en <i class="fa-solid fa-arrow-right-from-bracket"></i> para cerrar sesión.</p>
    <P> Haga click en <i class="fa-solid fa-user-pen"></i> para cambiar su contraseña.</P>
    <P> <B>¡Atención!</B> La sesión expirará en 120 segundos de inactividad.</P>
    <P> A continuación puede ver el listado de sus pedidos. </P>


    <table>
        <thead>
        <tr>
            <th>Codigo de cliente</th>
            <th>Nombre de cliente</th>
            <th>ID de pedido</th>
            <th>Fecha del pedido</th>
            <th>Fecha de entrega</th>
        </tr>
</thead>
        <?php foreach($listaPedidos as $listaPedidos): ?>
        <tr>
            <td><?=$listaPedidos['codigousuario'];?></td>
            <td><?=$listaPedidos['nombreusuario'];?></td>
            <td><?=$listaPedidos['idpedido'];?></td>
            <td><?=$listaPedidos['fechapedido'];?></td>
            <td><?=$listaPedidos['fechaentrega'];?></td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>




</body>
</html>