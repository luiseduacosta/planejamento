<?php // pr($nucleotematico); ?>

<?php
echo $this->element('submenu_nucleotematicos');
?>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Planejamento.id', 'Editar'); ?></th>
        <th><?php echo $this->Paginator->sort('Planejamento.turno', 'Turno'); ?></th>
        <th><?php echo $this->Paginator->sort('Planejamento.periodo', 'Período'); ?></th>
        <th><?php echo $this->Paginator->sort('Dia.dia', 'Dia'); ?></th>
        <th><?php echo $this->Paginator->sort('Horario.horario', 'Horário'); ?></th>
        <th><?php echo $this->Paginator->sort('Disciplina.codigo', 'Código'); ?></th>
        <th><?php echo $this->Paginator->sort('Disciplina.disciplina', 'Disciplina'); ?></th>
        <th><?php echo $this->Paginator->sort('Planejamento.ementa_id', 'Ementa'); ?></th>        
        <th><?php echo $this->Paginator->sort('Docente.nome', 'Professor(a)'); ?></th>
        <th><?php echo $this->Paginator->sort('Docente.departamento', 'Departamento'); ?></th>
    </tr>
<?php foreach ($nucleotematico as $c_nucleotematico): ?>
<?php // pr($c_otp); ?>
    <tr>
        <td><?php echo $this->Html->link($c_nucleotematico['Planejamento']['id'], '/planejamentos/view/' . $c_nucleotematico['Planejamento']['id']); ?></td>
        <td><?php echo $c_nucleotematico['Planejamento']['turno']; ?></td>
        <td><?php echo $c_nucleotematico['Planejamento']['periodo']; ?></td>
        <td><?php echo $c_nucleotematico['Dia']['dia']; ?></td>
        <td><?php echo $c_nucleotematico['Horario']['horario']; ?></td>
        <td><?php echo $c_nucleotematico['Disciplina']['codigo']; ?></td>
        <td><?php echo $this->Html->link($c_nucleotematico['Disciplina']['disciplina'], '/planejamentos/view/' . $c_nucleotematico['Planejamento']['id']); ?></td>
        <td>
            <?php 
            if ($c_nucleotematico['Planejamento']['ementa_id']):
                echo $this->Html->link($c_nucleotematico['Planejamento']['ementa_id'], '/ementas/view/' . $c_nucleotematico['Planejamento']['ementa_id']); 
            endif;
            ?>
        </td>
        <td><?php echo $this->Html->link($c_nucleotematico['Docente']['nome'], '/planejamentos/listar/disciplina:' . $c_nucleotematico['Disciplina']['id']); ?></td>
        <td><?php echo $c_nucleotematico['Docente']['departamento']; ?></td>
    </tr>
<?php endforeach; ?>
</table>