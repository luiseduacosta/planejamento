<?php
echo $this->element('submenu_professores');
?>

<?php

echo $this->Form->Create('Docente');
echo $this->Form->Input('siape');
echo $this->Form->Input('nome');
echo $this->Form->Input('departamento', array('type' => 'select', 'label' => 'Departamento', 'options' => array('Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos', 'Outro' => 'Outro ou s/d'), 'selected' => $this->data['Docente']['departamento']));
echo $this->Form->Input('tipocargo', array('type' => 'select', 'label' => 'Cargo', 'options' => array('efetivo'=>'efetivo', 'substituto'=>'substituto'), 'selected' => $this->data['Docente']['tipocargo'], 'empty' => array('Selecione')));
echo $this->Form->Input('situacao', array('label' => 'Situação (afastamento para qualificação, etc)'));
echo $this->Form->Input('motivoegresso', array('label' => 'Motivo egresso'));
echo $this->Form->End('Confirma');

?>
