<?php // pr($professores); ?>
<?php
echo $this->element('submenu_planejamentos');
?>

<?php
echo $this->Html->script("jquery", array('inline' => false));
echo $this->Html->scriptBlock('

$(document).ready(function() {

var url = location.hostname;

var base_url = window.location.pathname.split("/");

$("#PlanejamentoTurno").change(function() {
	var turno = $(this).val();
        // alert(turno); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/otp/turno:"+turno;
        } else {
            window.location="/Planejamentos/otp/turno:"+turno;
        }
})

$("#PlanejamentoProfessor").change(function() {
	var professor = $(this).val();
        // alert(professor); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/otp/professor:"+professor;
        } else {
            window.location="/Planejamentos/otp/professor:"+professor;
        }
})

$("#PlanejamentoDisciplina").change(function() {
	var disciplina = $(this).val();
        // alert(disciplina); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/otp/disciplina:"+disciplina;
        } else {
            window.location="/Planejamentos/otp/disciplina:"+disciplina;
        }
})

$("#PlanejamentoDepartamento").change(function() {
	var departamento = $(this).val();
        // alert(departamento); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Planejamentos/otp/departamento:"+departamento;
        } else {
            window.location="/Planejamentos/otp/departamento:"+departamento;
        }
})

});

', array("inline" => false));
?>


<div align="center">
<?php
echo $this->Form->Create('Planejamento', array('inputDefaults' => array('label'=>false, 'div'=>false)));
echo $this->Form->Input('turno', array('type'=>'select', 'options'=> array('Diurno'=>'Diurno', 'Noturno'=>'Noturno'), 'selected'=> array($turno), 'empty' => array('0' => 'Turno')));
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
echo $this->Form->Input('departamento', array('type'=>'select', 'options'=> array('Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos'), 'selected'=> array($departamento), 'empty' => array('0' => 'Departamento')));
// echo $this->Form->End('Confirma');
?>
</div>

<?php // pr($otp); ?>
<table>
    <tr>
        <th><?php echo $this->Paginator->sort('Planejamento.turno', 'Turno'); ?></th>
        <th><?php echo $this->Paginator->sort('Dia.dia', 'Dia'); ?></th>
        <th><?php echo $this->Paginator->sort('Horario.horario', 'Horário'); ?></th>
        <th><?php echo $this->Paginator->sort('Disciplina.codigo', 'Código'); ?></th>
        <th><?php echo $this->Paginator->sort('Disciplina.disciplina', 'Disciplina'); ?></th>
        <th><?php echo $this->Paginator->sort('Docente.nome', 'Professor(a)'); ?></th>
        <th><?php echo $this->Paginator->sort('Docente.departamento', 'Departamento'); ?></th>
    </tr>
<?php foreach ($otp as $c_otp): ?>
<?php // pr($c_otp); ?>
    <tr>
        <td><?php echo $c_otp['Planejamento']['turno']; ?></td>
        <td><?php echo $c_otp['Dia']['dia']; ?></td>
        <td><?php echo $c_otp['Horario']['horario']; ?></td>
        <td><?php echo $c_otp['Disciplina']['codigo']; ?></td>
        <td><?php echo $c_otp['Disciplina']['disciplina']; ?></td>
        <td><?php echo $this->Html->link($c_otp['Docente']['nome'], '/planejamentos/listar/disciplina:' . $c_otp['Disciplina']['id']); ?></td>
        <td><?php echo $c_otp['Docente']['departamento']; ?></td>
    </tr>
<?php endforeach; ?>
</table>