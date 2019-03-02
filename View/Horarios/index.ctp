<?php // pr($horarios); ?>

<table>
    <tr>
        <th>Ordem</th>
        <th>Horário</th>
    </tr>

    <?php
    foreach ($horarios as $c_horario) {
        ?>
        <tr>
            <td><?php echo $c_horario['Horario']['ordem']; ?></td>
            <td><?php echo $c_horario['Horario']['horario']; ?></td>
        </tr>
        <?php
    }
    ?>

</table>
