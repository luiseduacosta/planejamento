<?php // pr($planejamento); ?>
<?php // pr($periodo); ?>
<?php // pr($turno); ?>
<?php // pr($professor); ?>

<?php
echo $this->element('submenu_planejamentos');
?>

<?php
echo $this->Html->script("jquery", array('inline' => false));
echo $this->Html->scriptBlock('

$(document).ready(function() {

var url = location.hostname;

var base_url = window.location.pathname.split("/");

$("#PlanejamentoSemestreId").change(function() {
	var semestre_id = $(this).val();
        // alert(semestre_id); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/planejamentos/listar/semestre_id:"+semestre_id;
        } else {
            window.location="/planejamentos/listar/semestre_id:"+semestre_id;
        }
})

$("#PlanejamentoTurno").change(function() {
	var turno = $(this).val();
        // alert(turno); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/listar/turno:"+turno;
        } else {
            window.location="/Planejamentos/listar/turno:"+turno;
        }
})

$("#PlanejamentoPeriodo").change(function() {
	var periodo = $(this).val();
        // alert(periodo); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/listar/periodo:"+periodo;
        } else {
            window.location="/Planejamentos/listar/periodo:"+periodo;
        }
})

$("#PlanejamentoProfessor").change(function() {
	var professor = $(this).val();
        // alert(professor); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/listar/professor:"+professor;
        } else {
            window.location="/Planejamentos/listar/professor:"+professor;
        }
})

$("#PlanejamentoDisciplina").change(function() {
	var disciplina = $(this).val();
        // alert(disciplina); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/listar/disciplina:"+disciplina;
        } else {
            window.location="/Planejamentos/listar/disciplina:"+disciplina;
        }
})

$("#PlanejamentoDepartamento").change(function() {
	var departamento = $(this).val();
        // alert(departamento); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/listar/departamento:"+departamento;
        } else {
            window.location="/Planejamentos/listar/departamento:"+departamento;
        }
})

});

', array("inline" => false));
?>

<div align="center">
<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('semestre_id', array('type'=>'select', 'options'=> array($listasemestres), 'selected'=> array($semestreatual), 'empty' => array('0' => 'Semestre')));
// echo $this->Form->End('Confirma');
?>
    
<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('turno', array('type'=>'select', 'options'=> array('Diurno'=>'Diurno', 'Noturno'=>'Noturno'), 'selected'=> array($turno), 'empty' => array('0' => 'Turno')));
// echo $this->Form->End('Confirma');
?>

<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('periodo', array('type'=>'select', 'options'=> array('1'=>'1' ,'2'=>'2', '3'=>'3', '4'=>'4', '5'=>'5', '6'=>'6', '7'=>'7', '8'=>'8', '9'=>'9', '10'=>'10'), 'selected'=> array($periodo), 'empty' => array('0' => 'Período')));
// echo $this->Form->End('Confirma');
?>

<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('professor', array('type'=>'select', 'options'=> array($professores), 'selected'=> array($professor), 'empty' => array('0' => 'Professor(a)')));
// echo $this->Form->End('Confirma');
?>

<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('disciplina', array('type'=>'select', 'options'=> array($disciplinas), 'selected'=> array($disciplina), 'empty' => array('0' => 'Disciplina')));
// echo $this->Form->End('Confirma');
?>

<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('departamento', array('type'=>'select', 'options'=> array('Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos', 'Outro' => 'Outro'), 'selected'=> array($departamento), 'empty' => array('0' => 'Departamento')));
// echo $this->Form->End('Confirma');
?>
</div>
    
<?php
echo "<div align = 'center'>";

echo $this->Paginator->numbers();

echo "<br>";

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
        <th>
            <?php echo $this->Paginator->sort('id', 'Id'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('turno', 'Turno'); ?>

        </th>
        <th>
            <?php echo $this->Paginator->sort('Planejamento.periodo', 'Periódo'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Planejamento.horario_id', 'Horário'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Planejamento.dia_id', 'Dia'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Disciplina.disciplina', 'Disciplina'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Disciplina.carga_horaria', 'CH'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Docente.nome', 'Professor(a)'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Planejamento.sala_id', 'Sala'); ?>
        </th>
        <th>
            <?php echo $this->Paginator->sort('Docente.departamento', 'Departamento'); ?>
        </th>
    </tr>

    <?php foreach ($planejamento as $c_planejamento): ?>
    <?php // pr($c_planejamento); ?>
        <tr>
            <td>
                <?php echo $this->Html->link($c_planejamento['Planejamento']['id'], '/planejamentos/view/' . $c_planejamento['Planejamento']['id']); ?>
            </td>
            <td>
                <?php echo $c_planejamento['Planejamento']['turno']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Planejamento']['periodo']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Horario']['horario']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Dia']['dia']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Disciplina']['disciplina']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Disciplina']['carga_horaria']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Docente']['nome']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Sala']['sala']; ?>
            </td>
            <td>
                <?php echo $c_planejamento['Docente']['departamento']; ?>
            </td>
        </tr>
    <?php endforeach; ?>

</table>