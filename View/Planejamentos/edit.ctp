<?php

// pr($optativas);
// die();

?>

<?php
echo $this->element('submenu_planejamentos');
?>

<?php

echo $this->Form->Create('Planejamento');

echo $this->Form->Input('turno', array('type'=>'select', 'label'=>'Turno', 'options'=> array('Diurno'=>'Diurno' ,'Noturno'=>'Noturno'), 'selected'=> array($this->data['Planejamento']['turno'])));
echo $this->Form->Input('periodo', array('type'=>'select', 'label'=>'Período', 'options'=> array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10'), 'selected' => $this->data['Planejamento']['periodo']));
echo $this->Form->Input('dia_id', array('type' => 'select', 'label' => 'Dia da aula', 'options' => $dias, 'selected' => $this->data['Dia']['id']));
echo $this->Form->Input('horario_id', array('type' => 'select', 'label' => 'Horário da aula', 'options' => $horarios, 'selected' => $this->data['Horario']['id'], 'empty' => 'Selecione'));
echo $this->Form->Input('disciplina_id', array('type' => 'select', 'label' => 'Disciplina', 'options' => $disciplinas, 'selected' => $this->data['Disciplina']['id'], 'empty' => 'Selecione'));
if ($this->data['Disciplina']['optativa']):
    echo $this->Form->Input('optativa_id', array('type' => 'select', 'label' => 'Disciplina optativa', 'options' => $optativas, 'selected' => $this->data['Optativa']['id'], 'empty' => array('0' => 'Selecione')));
    echo $this->Form->Input('ementa_id', array('type' => 'select', 'label' => 'Ementa optativa', 'options' => $ementas, 'selected' => $this->data['Ementa']['id'], 'empty' => array('0' => 'Selecione')));
endif;
echo $this->Form->Input('docente_id', array('type' => 'select', 'label' => 'Professor', 'options' => $docentes, 'selected' => $this->data['Docente']['id'], 'empty' => 'Selecione'));
echo $this->Form->Input('sala_id', array('type' => 'select', 'label' => 'Sala da aula', 'options' => $salas, 'selected' => $this->data['Sala']['id'], 'empty' => 'Selecione'));

echo $this->Form->End('Confirma');

?>
