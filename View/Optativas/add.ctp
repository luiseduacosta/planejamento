<?php
echo $this->element('submenu_optativas');
?>

<h1>Inserir disciplina optativa</h1>

<?php

echo $this->Form->Create('Optativa');
echo $this->Form->Input('codigo');
echo $this->Form->Input('disciplina');
echo $this->Form->Input('creditos');
echo $this->Form->Input('carga_horaria');
echo $this->Form->Input('periodo_diurno');
echo $this->Form->Input('periodo_noturno');
echo $this->Form->Input('departamento', array('type'=>'select', 'label'=>'Departamento', 'options'=> array('Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos', 'Interdepartamental' => 'Interdepartamental'), 'empty' => array('0' => 'Sem departamento')));
echo $this->Form->Input('observacoes');
echo $this->Form->End('Confirma');

?>
