<?php // pr($ementa); ?>

<?php
echo $this->element('submenu_ementas');
?>

<table>
    <tr>
        <td>Disciplina</td>
        <td><?php echo $ementa['Disciplina']['disciplina']; ?></td>
    </tr>
    <tr>
        <td>Professor(a)</td>
        <td><?php echo $ementa['Docente']['nome']; ?></td>
    </tr>
    <tr>
        <td>Título</td>
        <td><?php echo $ementa['Ementa']['titulo']; ?></td>
    </tr>
    <tr>
        <td>Ementa</td>
        <td><?php echo $ementa['Ementa']['ementa']; ?></td>
    </tr>
</table>
