<?php

// pr($optativas); ?>

<?php
echo $this->element('submenu_optativas');
?>

    <table>
        <tr>
            <th><?php echo $this->Paginator->sort('codigo', 'Código'); ?></th>
            <th><?php echo $this->Paginator->sort('disciplina', 'Disciplina'); ?></th>
            <th><?php echo $this->Paginator->sort('creditos', 'Creditos'); ?></th>
            <th><?php echo $this->Paginator->sort('carga_horaria', 'Carga horária'); ?></th>
        </tr>

    <?php
    foreach ($optativas as $c_optativas) {
        // pr($c_optativas);
        ?>
        <tr>
            <td><?php echo $c_optativas['Optativa']['codigo']; ?></td>
            <td><?php echo $this->Html->link($c_optativas['Optativa']['disciplina'], '/optativas/View/' . $c_optativas['Optativa']['id']); ?></td>
            <td><?php echo $c_optativas['Optativa']['creditos']; ?></td>
            <td><?php echo $c_optativas['Optativa']['carga_horaria']; ?></td>
        </tr>
    <?php
}
?>

    </table>