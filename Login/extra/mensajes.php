    <?php if(isset($mensajes) && is_array($mensajes) && $mensajes): ?>
    <H3>Mensajes:</H3>    
    <UL>
    <?php array_walk($mensajes, function ($m){echo '<LI>'.$m.'</LI>'; }); ?>
    </UL>
    <?php endif; ?>