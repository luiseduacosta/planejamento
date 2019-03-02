<?php

// pr($optativa);  ?>

<?php
echo $this->element('submenu_optativas');
?>

<table>
    <tr>
        <td>Código</td>
        <td><?php echo $optativa['Optativa']['codigo']; ?></td>
    </tr>
    <tr>
        <td>Disciplina</td>
        <td><?php echo $optativa['Optativa']['disciplina']; ?></td>
    </tr>
    <tr>
        <td>Créditos</td>
        <td><?php echo $optativa['Optativa']['creditos']; ?></td>
    </tr>
    <tr>
        <td>Optativa</td>
        <td><?php 
        if (!$optativa['Optativa']['optativa']):
        echo 'Não '. $optativa['Optativa']['optativa'];
        else:
        echo 'Sim';    
        endif;
        ?></td>
    </tr>
    <tr>
        <td>Carga horária</td>
        <td><?php echo $optativa['Optativa']['carga_horaria']; ?></td>
    </tr>
    <tr>
        <td>Diurno</td>
        <td><?php echo $optativa['Optativa']['periodo_diurno']; ?></td>
    </tr>
    <tr>
        <td>Noturno</td>
        <td><?php echo $optativa['Optativa']['periodo_noturno']; ?></td>
    </tr>
    <tr>
        <td>Departamento</td>
        <td><?php echo $optativa['Optativa']['departamento']; ?></td>
    </tr>
    <tr>
        <td>Observações</td>
        <td><?php echo $optativa['Optativa']['observacoes']; ?></td>
    </tr>
</table>
