<?php
echo $this->element('submenu_professores');
?>

<?php

echo $this->Form->Create('Docente');
echo $this->Form->Input('nome', array('label' => 'Docente'));
echo $this->Form->Input('departamento', array('type'=>'select', 'label'=>'Departamento', 'options'=> array('Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos', 'Outro' => 'Outro ou s/d')));
echo $this->Form->Input('tipocargo', array('type' => 'select', 'label' => 'Contrato', 'default' => array('efetivo'=>'Efetivo'), 'options' => array('efetivo'=>'Efetivo', 'substituto'=>'Substituto')));
echo $this->Form->Input('situacao', array('label' => 'Situação (afastamento, etc.)'));
echo $this->Form->End('Confirma');

?>
