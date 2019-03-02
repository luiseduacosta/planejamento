<?php

// pr($planejamento);   ?>

<?php
echo $this->element('submenu_planejamentos');
?>


<table>
    <tr>
        <td>Turno</td>
        <td><?php echo $planejamento['Planejamento']['turno']; ?></td>
    </tr>
    <tr>
        <td>Período</td>
        <td><?php echo $planejamento['Planejamento']['periodo']; ?></td>
    </tr>
    <tr>
        <td>Dia da disciplina</td>
        <td><?php echo $planejamento['Dia']['dia']; ?></td>
    </tr>
    <tr>
        <td>Horário da disciplina</td>
        <td><?php echo $planejamento['Horario']['horario']; ?></td>
    </tr>
    <tr>
        <td>Disciplina</td>
        <td><?php
            if (empty($planejamento['Disciplina']['disciplina'])):
                echo 's/d';
            else:
                echo $planejamento['Disciplina']['disciplina'];
            endif;
            ?></td>
    </tr>
<?php if ($planejamento['Disciplina']['optativa']): ?>
    <tr>
        <td>Disciplina optativa</td>
        <td><?php
            if (empty($planejamento['Optativa']['disciplina'])):
                echo 's/d';
            else:
                echo $this->Html->link($planejamento['Optativa']['disciplina'], '/optativas/view/' . $planejamento['Optativa']['id']);
            endif;
            ?></td>
    </tr>
    
    <tr>
        <td>Ementa</td>
        <td><?php
            if (empty($planejamento['Ementa']['titulo'])):
                echo 's/d';
            else:
                echo $this->Html->link($planejamento['Ementa']['titulo'], '/ementas/view/' . $planejamento['Ementa']['id']);
            endif;
            ?></td>
    </tr>
<?php endif; ?>
    <tr>
        <td>Professor</td>
        <td><?php
            if (empty($planejamento['Docente']['nome'])):
                echo 's/d';
            else:
                echo $planejamento['Docente']['nome'];
            endif;
            ?></td>
    </tr>    
    <tr>
        <td>Sala</td>
        <td><?php echo $planejamento['Sala']['sala']; ?></td>
    </tr>    
</table>
