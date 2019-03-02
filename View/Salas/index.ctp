<?php // pr($salas); ?>

<?php
echo $this->element('submenu_salas');
?>

<table>
    <tr>
        <th>Sala</th>
        <th>Lotação</th>
        <th>Localização</th>
        <th>Recursos</th>
        <th>Observações</th>        
    </tr>

    <?php
    foreach ($salas as $c_sala) {
        ?>
        <tr>
            <td><?php echo $this->Html->link($c_sala['Sala']['sala'], '/salas/view/' . $c_sala['Sala']['id']); ?></td>
            <td><?php echo $c_sala['Sala']['lotacao']; ?></td>
            <td><?php echo $c_sala['Sala']['localizacao']; ?></td>
            <td><?php echo $c_sala['Sala']['recursos']; ?></td>
            <td><?php echo $c_sala['Sala']['observacoes']; ?></td>
        </tr>
        <?php
    }
    ?>

</table>
