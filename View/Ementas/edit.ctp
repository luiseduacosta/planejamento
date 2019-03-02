<?php
echo $this->element('submenu_ementas');
?>

<?php

echo $this->Form->Create('Ementa');
echo $this->Form->Input('disciplina_id', array('type' => 'select', 'options' => $disciplinas));
echo $this->Form->Input('docente_id', array('type' => 'select', 'options' => $docentes));
echo $this->Form->Input('titulo');
echo $this->Form->Input('ementa', array('type' => 'textarea'));
echo $this->Form->End('Confirma');

?>
