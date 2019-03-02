<?php // pr($sala); ?>

<?php
echo $this->element('submenu_salas');
?>


<table>
    <tr>
        <td>Sala</td>
        <td><?php echo $sala['Sala']['sala']; ?></td>
    </tr>
    <tr>
        <td>Lotação</td>
        <td><?php echo $sala['Sala']['lotacao']; ?></td>
    </tr>
    <tr>
        <td>Localização</td>
        <td><?php echo $sala['Sala']['localizacao']; ?></td>
    </tr>
    <tr>
        <td>Recursos</td>
        <td><?php echo $sala['Sala']['recursos']; ?></td>
    </tr>
    <tr>    
        <td>Observações</td>
        <td><?php echo $sala['Sala']['observacoes']; ?></td>
    </tr>
</table>
