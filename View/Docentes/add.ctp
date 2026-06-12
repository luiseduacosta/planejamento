<?php
echo $this->element('submenu_professores');
?>

<?php

echo $this->Form->Create('Docente');
echo $this->Form->Input('nome', ['label' => 'Docente']);
echo $this->Form->Input('departamento', ['type'=>'select', 'label'=>'Departamento', 'options'=> ['Fundamentos'=>'Fundamentos', 'Políticas'=>'Políticas', 'Métodos'=>'Métodos', 'Outro' => 'Outro ou s/d']]);
echo $this->Form->Input('tipocargo', ['type' => 'select', 'label' => 'Contrato', 'default' => ['efetivo'=>'Efetivo'], 'options' => ['efetivo'=>'Efetivo', 'substituto'=>'Substituto']]);
echo $this->Form->Input('situacao', ['label' => 'Situação (afastamento, etc.)']);
echo $this->Form->End('Confirma');

?>
