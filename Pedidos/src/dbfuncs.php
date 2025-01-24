<?php

function listaDeProductos(PDO $pdo) {
    $sql='SELECT productos.id as idproducto, productos.cod as codproducto,'
        .'productos.desc as descproducto, productos.precio as precioproducto,'
        .'productos.stock as stockproducto FROM productos';
    $ret=false;
    try {
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute()) {
                $ret=$stmt->fetchAll(PDO::FETCH_ASSOC);                       
        } 
    } catch (PDOException $ex) {
        $ret=-1;
    }
    return $ret;
}

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

function nuevoPedido (PDO $pdo, $codcliente, $fechaentrega)
{
    $sql='insert into pedidos (fechaentrega,idusuario) values (:fechaentrega,(select id from usuarios where cod=:codcliente))';
    $ret=false;
    try {
        $stmt = $pdo->prepare($sql);
        $data=['fechaentrega'=>$fechaentrega,'codcliente'=>$codcliente];
        if ($stmt->execute($data)) {
                $ret=$stmt->rowCount(); 
        } 
    } catch (PDOException $ex) {
        $ret=-1;
    } 
    return $ret;        
}

function borrarLineaPedido (PDO $pdo, $idLineaPedido) {
    //1º incrementa el stock
    $sql='update productos set stock=stock+(select unidades from lineaspedido where id=:idlineapedido) where id=(select productos_id from lineaspedido where id=:idlineapedido)';
    try {
        $stmt = $pdo->prepare($sql);
        $data=['idlineapedido'=>$idLineaPedido];
        $stmt->execute($data);
    } catch (PDOException $ex) {
        return false;
    }
    //2º borra la linea de pedido
    $sql='delete from lineaspedido where id=:idlineapedido';
    try {
        $stmt = $pdo->prepare($sql);
        $data=['idlineapedido'=>$idLineaPedido];
        $stmt->execute($data);
        return true;
    } catch (PDOException $ex) {
        return false;
    }
}

function borrarPedido(PDO $pdo, $idpedido)
{
    //1º Obtener las lineas de pedido
    $sql='SELECT id from lineaspedido where pedidos_id=:idpedido';
    //2º Borra cada linea de pedido invocando borrarLineaPedido
    //3º Borra el pedido
    $sql='DELETE FROM pedidos WHERE id=:idpedido';
    return false;
}

function modificarFechaEntregaPedido(PDO $pdo, $idpedido, $nuevafechaentrega)
{
    $sql='UPDATE pedidos SET fechaentrega=:fechaentrega WHERE id=:idpedido';
    $ret=false;
    try {
        $stmt = $pdo->prepare($sql);
        $data=['fechaentrega'=>$nuevafechaentrega,'idpedido'=>$idpedido];
        if ($stmt->execute($data)) {
                $ret=$stmt->rowCount(); 
        }
    } catch (PDOException $ex) {
        $ret=-1;
    } 
    return $ret;  
}

function obtenerLineasPedido(PDO $pdo, $idpedido)
{    
    $sql='SELECT lineaspedido.id as idlineapedido, lineaspedido.codprod as codprod,
          lineaspedido.unidades as unidades, lineaspedido.precio as precio,
          productos.desc as descripcion
          from lineaspedido left join productos on lineaspedido.productos_id=productos.id where pedidos_id=:idpedido';
    $ret=false;
    try {
        $stmt = $pdo->prepare($sql);
        $data=['idpedido'=>$idpedido];
        if ($stmt->execute($data)) {
                $ret=$stmt->fetchAll(PDO::FETCH_ASSOC);                       
        } 
    } catch (PDOException $ex) {
        $ret=-1;
    } 
    return $ret;
}

function nuevaLineaPedido(PDO $pdo, $idpedido, $idproducto, $unidades)
{   
    //1º Inicia transacción
    $pdo->beginTransaction();
    //2º Actualiza el stock
    $sql='update productos set stock=stock-:unidades where id=:idproducto and stock>=:unidades';
    try {
        $stmt = $pdo->prepare($sql);
        $data=['unidades'=>$unidades,'idproducto'=>$idproducto];
        $stmt->execute($data);
        //3º Si las filas modificadas (rowCount) es 0, entonces haz un rollBack y termina.
        if ($stmt->rowCount()==0) {
            $pdo->rollBack();
            echo 'No hay stock suficiente';
            return false;
        }
        //Si las filas modificadas (rowCount) es 1, entonces continua
        //4º Inserta la línea de pedido    
        $sql='insert into lineaspedido (unidades, pedidos_id, productos_id, codprod, precio)
        select :unidades,:idpedido,id,cod,precio from productos where id=:idproducto';
        $stmt = $pdo->prepare($sql);
        $data=['unidades'=>$unidades,'idpedido'=>$idpedido,'idproducto'=>$idproducto];
        $stmt->execute($data);
        
        //5º Si la inserción fue bien (rowCount es 1), entonces haz commit, sino rollback  
        if ($stmt->rowCount()==1) {
            $pdo->commit();
            return true;
        } else {
            $pdo->rollBack();
            echo 'No se pudo insertar la línea de pedido';
            return false;
        }  
    } catch (PDOException $ex) {
        $pdo->rollBack();
        echo 'Error en la transacción';
        return false;
    }
}

