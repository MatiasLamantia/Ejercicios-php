
<h3>
    <?php
    $fechaActual = strtotime(date("d-m-Y",time()));
    $fechaOferta = strtotime("11-12-2022");
        
    if($fechaActual < $fechaOferta){
        print("Trae a tu amigo o amiga hasta el 11/12/2022 y consigue un 30% de descuento de por vida.");
     }
    ?>
</h3>