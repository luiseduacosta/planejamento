<?php
echo $this->element('submenu_planejamentos');
?>

<?php

echo $this->Form->Create('Planejamento');
echo $this->Form->Input('configuraplanejamento_id', array('type'=>'hidden', 'value' => $configuracao['Configuraplanejamento']['id']));
echo $this->Form->Input('turno', array('type'=>'select', 'label'=>'Turno', 'options'=> array('Diurno'=>'Diurno' ,'Noturno'=>'Noturno'), 'selected'=>'diurno'));
echo $this->Form->Input('periodo', array('type'=>'select', 'label'=>'Período', 'options'=> array('1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5','6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10')));
echo $this->Form->Input('dia_id', array('type'=>'select', 'label'=>'Dia da aula', 'options'=>$dias));
echo $this->Form->Input('horario_id', array('type'=>'select', 'label'=>'Horário da aula', 'options'=>$horarios));
echo $this->Form->Input('disciplina_id', array('type'=>'select', 'label'=>'Disciplina', 'options'=>$disciplinas,'empty' => array('0' => 'Seleciona')));
echo $this->Form->Input('docente_id', array('type'=>'select', 'label'=>'Docente', 'options'=>$professores,'empty' => array('0' => 'Seleciona')));
echo $this->Form->Input('sala_id', array('type'=>'select', 'label'=>'Sala', 'options'=>$salas, 'empty' => array('0' => 'Seleciona')));
echo $this->Form->End('Confirma');

?>
