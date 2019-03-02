<?php // pr($ementas); ?>

<?php
echo $this->element('submenu_ementas');
?>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Ementa.id', 'Ementa'); ?></th>
        <th><?php echo $this->Paginator->sort('Disciplina.disciplina', 'Disciplina'); ?></th>        
        <th><?php echo $this->Paginator->sort('Docente.nome', 'Docente'); ?></th>
        <th><?php echo $this->Paginator->sort('Ementa.titulo', 'Ementa'); ?></th>
    </tr>

    <?php
    foreach ($ementas as $c_ementa) {
        ?>
        <tr>
            <td><?php echo $this->Html->link($c_ementa['Ementa']['id'], '/ementas/view/' . $c_ementa['Ementa']['id']); ?></td>
            <td><?php echo $c_ementa['Disciplina']['disciplina']; ?></td>
            <td><?php echo $c_ementa['Docente']['nome']; ?></td>
            <td><?php 
            echo "<b>" . $c_ementa['Ementa']['titulo'] . "</b>" . "<br>" .
             $c_ementa['Ementa']['ementa']; ?></td>
        </tr>
        <?php
    }
    ?>

</table>
