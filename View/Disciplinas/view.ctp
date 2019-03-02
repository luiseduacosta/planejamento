<?php

// pr($disciplina);  ?>

<?php
echo $this->element('submenu_disciplinas');
?>

<table>
    <tr>
        <td>Código</td>
        <td><?php echo $disciplina['Disciplina']['codigo']; ?></td>
    </tr>
    <tr>
        <td>Disciplina</td>
        <td><?php echo $disciplina['Disciplina']['disciplina']; ?></td>
    </tr>
    <tr>
        <td>Créditos</td>
        <td><?php echo $disciplina['Disciplina']['creditos']; ?></td>
    </tr>
    <tr>
        <td>Optativa</td>
        <td><?php 
        if (!$disciplina['Disciplina']['optativa']):
        echo 'Não '. $disciplina['Disciplina']['optativa'];
        else:
        echo 'Sim';    
        endif;
        ?></td>
    </tr>
    <tr>
        <td>Carga horária</td>
        <td><?php echo $disciplina['Disciplina']['carga_horaria']; ?></td>
    </tr>
    <tr>
        <td>Diurno</td>
        <td><?php echo $disciplina['Disciplina']['periodo_diurno']; ?></td>
    </tr>
    <tr>
        <td>Noturno</td>
        <td><?php echo $disciplina['Disciplina']['periodo_noturno']; ?></td>
    </tr>
    <tr>
        <td>Observações</td>
        <td><?php echo $disciplina['Disciplina']['observacoes']; ?></td>
    </tr>
</table>
