<?php // pr($docente);  ?>
<?php // pr($semestre_id); ?>

<?php
echo $this->element('submenu_professores');
?>


<table>
    <tr>
        <td>Siape</td>
        <td><?php echo $docente['Docente']['siape']; ?></td>
    </tr>
    <tr>
        <td>Professor</td>
        <td><?php echo $docente['Docente']['nome']; ?></td>
    </tr>
    <tr>
        <td>Departamento</td>
        <td><?php echo $docente['Docente']['departamento']; ?></td>
    </tr>
    <tr>
        <td>Cargo</td>
        <td><?php echo $docente['Docente']['tipocargo']; ?></td>
    </tr>
    <tr>
        <td>Situação (afastamento, etc.)</td>
        <td><?php echo $docente['Docente']['situacao']; ?></td>
    </tr>
    <tr>
        <td>Egresso</td>
        <td><?php echo $docente['Docente']['motivoegresso']; ?></td>
    </tr>
</table>

<table>
    <tr>
        <td>
            Disciplinas
        </td>
        <td>
            <?php
            $i = 1;
            foreach ($docente['Planejamento'] as $c_docente):
                // pr($c_docente);
                if ($c_docente['configuraplanejamento_id'] == $semestre_id):
                    // pr($c_docente['Disciplina']);
                    echo $i . ") " . $c_docente['Disciplina']['disciplina'] . ", ";
                    $i++;
                endif;
            endforeach;
            ?>
        </td>
    </tr>
</table>
