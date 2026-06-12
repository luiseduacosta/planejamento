<?php
echo $this->element('submenu_professores');
?>

<?php

echo $this->Form->Create('Docente');
echo $this->Form->Input('siape');
echo $this->Form->Input('nome');
echo $this->Form->Input('departamento', ['type' => 'select', 'label' => 'Departamento', 'options' => ['Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos', 'Outro' => 'Outro ou s/d'], 'selected' => $this->data['Docente']['departamento']]);
echo $this->Form->Input('tipocargo', ['type' => 'select', 'label' => 'Cargo', 'options' => ['efetivo'=>'efetivo', 'substituto'=>'substituto'], 'selected' => $this->data['Docente']['tipocargo'], 'empty' => ['Selecione']]);
echo $this->Form->Input('situacao', ['label' => 'Situação (afastamento para qualificação, etc)']);
echo $this->Form->Input('motivoegresso', ['label' => 'Motivo egresso']);
echo $this->Form->End('Confirma');

?>
