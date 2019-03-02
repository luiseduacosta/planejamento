<?php // pr($disciplinas);      ?>

<?php
echo $this->element('submenu_disciplinas');
?>

<?php
echo $this->Html->script("jquery", array('inline' => false));
echo $this->Html->scriptBlock('

$(document).ready(function() {

var url = location.hostname;

var base_url = window.location.pathname.split("/");

$("#DisciplinaPeriodoDiurno").change(function() {
	var periodo = $(this).val();
        // alert(periodo); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Disciplinas/index/diurno:"+periodo;
        } else {
            window.location="/Disciplinas/index/periodo:"+periodo;
        }
})

$("#DisciplinaPeriodoNoturno").change(function() {
	var periodo = $(this).val();
        // alert(periodo); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Disciplinas/index/noturno:"+periodo;
        } else {
            window.location="/Disciplinas/index/periodo:"+periodo;
        }
})

});

', array("inline" => false));
?>

<div align="center">
    <?php
    echo $this->Form->Create('Disciplina', array('inputDefaults' => array('label' => false, 'div' => false)));
    echo $this->Form->Input('periodo_diurno', array('type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8'), 'selected' => array($diurno), 'empty' => array('0' => 'Diurno')));
// echo $this->Form->End('Confirma');

    echo $this->Form->Create('Disciplina', array('inputDefaults' => array('label' => false, 'div' => false)));
    echo $this->Form->Input('periodo_noturno', array('type' => 'select', 'options' => array('1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10'), 'selected' => array($noturno), 'empty' => array('0' => 'Noturno')));
// echo $this->Form->End('Confirma');
    ?>

</div>


<div align = 'center'>

<?php echo $this->Paginator->numbers(); ?>

<br>

<?php
// Shows the next and previous links
echo $this->Paginator->prev(
        '« Anterior ', null, null, array('class' => 'disabled')
);
echo $this->Paginator->next(
        ' Posterior »', null, null, array('class' => 'disabled')
);

echo "<br>";

echo $this->Paginator->counter();

echo "</div>";
?>

<table>
    <tr>
        <th><?php echo $this->Paginator->sort('codigo', 'Código'); ?></th>
        <th><?php echo $this->Paginator->sort('disciplina', 'Disciplina'); ?></th>
        <th><?php echo $this->Paginator->sort('creditos', 'Creditos'); ?></th>
        <th><?php echo $this->Paginator->sort('optativa', 'Optativa'); ?></th>        
        <th><?php echo $this->Paginator->sort('carga_horaria', 'Carga horária'); ?></th>
        <th><?php echo $this->Paginator->sort('periodo_diurno', 'Diurno'); ?></th>
        <th><?php echo $this->Paginator->sort('periodo_noturno', 'Noturno'); ?></th>
        <th><?php echo $this->Paginator->sort('docente_id', 'Professores'); ?></th>
    </tr>

    <?php
    foreach ($disciplinas as $c_disciplinas) {
        ?>
        <tr>
            <td><?php echo $c_disciplinas['Disciplina']['codigo']; ?></td>
            <td><?php echo $this->Html->link($c_disciplinas['Disciplina']['disciplina'], '/Disciplinas/View/' . $c_disciplinas['Disciplina']['id']); ?></td>
            <td><?php echo $c_disciplinas['Disciplina']['creditos']; ?></td>
            <td><?php echo $c_disciplinas['Disciplina']['optativa']; ?></td>
            <td><?php echo $c_disciplinas['Disciplina']['carga_horaria']; ?></td>
            <td><?php echo $c_disciplinas['Disciplina']['periodo_diurno']; ?></td>
            <td><?php echo $c_disciplinas['Disciplina']['periodo_noturno']; ?></td>
            <td>
                <?php
                foreach ($c_disciplinas['Planejamento'] as $docente_disciplinas):
                    // pr($docente_disciplinas);
                    echo $this->Html->link($docente_disciplinas['docente_id'], '/Planejamentos/listar/disciplina:' .$docente_disciplinas['disciplina_id']) . ', ';
                endforeach;
                ?>
            </td>
        </tr>
    <?php
}
?>

</table>