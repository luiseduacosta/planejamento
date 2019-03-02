<?php

echo $this->Form->Create('Disciplina');
echo $this->Form->Input('codigo');
echo $this->Form->Input('disciplina');
echo $this->Form->Input('creditos');
echo $this->Form->Input('optativa', array('label' => 'Marcar se a disciplina for optativa'));
echo $this->Form->Input('carga_horaria');
echo $this->Form->Input('periodo_diurno');
echo $this->Form->Input('periodo_noturno');
echo $this->Form->Input('observacoes');
echo $this->Form->End('Confirma');

?>
