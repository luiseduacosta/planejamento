<?php // pr($optativas); ?>

<?php
echo $this->element('submenu_ementas');
?>

<?php

echo $this->Form->Create('Planejamento');
echo $this->Form->Input('ementa_id', ['type' => 'select', 'options' => $optativas]);
echo $this->Form->End('Confirma');

?>
