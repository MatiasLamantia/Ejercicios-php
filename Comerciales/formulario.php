<form action="">
    <fieldset>
        <legend>Datos del cliente</legend>
        <label for="numero">Número del cliente</label>
        <input type="text" name="numero">

        <label for="fecha">Fecha de entrega</label>
        <input type="text" name="fecha">
    </fieldset>

    <fieldset>

        
        <?php

        
        for($i=0 ; $i<=10;$i++){     
            
            print("<select name='producto_".$i."'>");

            foreach ($productos as $key => $value) {
                print("<option value ='".$key ."'>" . $productos[$key]["descripcion"] ."</option>");
                
            }
            
            print("</select>");
            print("<input type='text' name='cantidad_". $i ."'><br>");
        }
            
        ?>
    </fieldset>
    <input type="submit" value="Enviar Pedido!">
    <input type="reset" value="">
</form>