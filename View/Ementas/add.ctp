<?php // pr($disciplinas); ?>

<?php 
// pr($configura); 
// die();
?>

<?php
echo $this->element('submenu_ementas');
?>

<?php

echo $this->Form->Create('Ementa');
echo $this->Form->Input('configuraplanejamento_id', array('type' => 'hidden', 'value' => $semestre));
echo $this->Form->Input('disciplina_id', array('type' => 'select', 'options' => $disciplinas));
echo $this->Form->Input('docente_id', array('label' => 'Professor(a)', 'type' => 'select', 'options' => $docentes));
echo $this->Form->Input('titulo');
echo $this->Form->Input('ementa', array('type' => 'textarea'));
echo $this->Form->End('Confirma');

?>
