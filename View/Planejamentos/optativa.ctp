<?php

// pr($optativas); ?>

<?php
echo $this->element('submenu_optativas');
?>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Planejamento.id', 'Id'); ?></th>
        <th><?php echo $this->Paginator->sort('Planejamento.turno', 'Turno'); ?></th>
        <th><?php echo $this->Paginator->sort('Planejamento.periodo', 'Período'); ?></th>
        <th><?php echo $this->Paginator->sort('Dia.dia', 'Dia'); ?></th>
        <th><?php echo $this->Paginator->sort('Horario.horario', 'Horário'); ?></th>
        <th><?php echo $this->Paginator->sort('Optativa.codigo', 'Código'); ?></th>
        <th><?php echo $this->Paginator->sort('Disciplina.disciplina', 'Disciplina'); ?></th>
        <th><?php echo $this->Paginator->sort('Ementa.id', 'Ementa'); ?></th>
        <th><?php echo $this->Paginator->sort('Docente.nome', 'Professor(a)'); ?></th>
        <th><?php echo $this->Paginator->sort('Docente.departamento', 'Departamento'); ?></th>
        <th><?php echo $this->Paginator->sort('Sala.sala', 'Sala'); ?></th>
    </tr>
<?php foreach ($optativas as $c_optativas): ?>
<?php // pr($c_otp); ?>
    <tr>
        <td><?php echo $this->Html->link($c_optativas['Planejamento']['id'], '/planejamentos/view/' . $c_optativas['Planejamento']['id']); ?></td>
        <td><?php echo $c_optativas['Planejamento']['turno']; ?></td>
        <td><?php echo $c_optativas['Planejamento']['periodo']; ?></td>
        <td><?php echo $c_optativas['Dia']['dia']; ?></td>
        <td><?php echo $c_optativas['Horario']['horario']; ?></td>
        <td><?php echo $this->Html->link($c_optativas['Optativa']['codigo'], '/optativas/view/' . $c_optativas['Optativa']['id']); ?></td>
        <td><?php echo $this->Html->link($c_optativas['Disciplina']['disciplina'], '/planejamentos/view/' . $c_optativas['Planejamento']['id']); ?></td>
        <td><?php 
        if ($c_optativas['Planejamento']['ementa_id']):
            echo $this->Html->link($c_optativas['Planejamento']['ementa_id'], '/ementas/view/' . $c_optativas['Planejamento']['ementa_id']); 
        endif;
        ?>
        </td>
        <td><?php echo $this->Html->link($c_optativas['Docente']['nome'], '/planejamentos/listar/disciplina:' . $c_optativas['Disciplina']['id']); ?></td>
        <td><?php echo $c_optativas['Docente']['departamento']; ?></td>
                <td><?php echo $c_optativas['Sala']['sala']; ?></td>
    </tr>
<?php endforeach; ?>
</table>