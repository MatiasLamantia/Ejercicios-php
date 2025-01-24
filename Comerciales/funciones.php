<?php
include "etc/conf.php";
function existeProducto($cod,$productos){

    return array_key_exists($cod,$productos);

}

function costLineaPedido($cod,$unidades,$productos){
    if(!existeProducto($cod,$productos)){
        $coste = -1;
    }
    else{
    $coste = $productos[$cod]["precio_unidad"] * $unidades;
    }
    return $coste;
    
}

function costePedido($pedido,$productos){

    foreach($pedido as $key => $value){
    $productoActual = $pedido[$key]['producto'];
    $coste_linea = costLineaPedido($productoActual,$pedido[$key]["unidades"],$productos);
    if($coste_linea === -1){
        $pedido[$key]['descripcion'] = "No existe";
        $pedido[$key]['coste_unidad'] = "No existe";
        $pedido[$key]['coste_linea'] = "No existe";
    }
    else{
     $productoActual = $pedido[$key]["producto"];
        $pedido[$key]['descripcion'] = $productos[$productoActual]['descripcion'];
        $pedido[$key]['coste_unidad'] = $productos[$productoActual]['precio_unidad'];
        $pedido[$key]['coste_linea'] =$coste_linea ;
    }
    }
    return $pedido;
}




$pedido[0]['producto']='A0111';
$pedido[0]['unidades']=20;
$pedido[1]['producto']='A04';
$pedido[1]['unidades']=10;




print_r(costePedido($pedido,$productos));