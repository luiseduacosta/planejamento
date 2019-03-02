<?php
echo $this->element('submenu_salas');
?>

<?php

echo $this->Form->Create('Sala');
echo $this->Form->Input('sala');
echo $this->Form->Input('lotacao', array('label' => 'Lotação'));
echo $this->Form->Input('localizacao');
echo $this->Form->Input('recursos');
echo $this->Form->Input('observacoes');
echo $this->Form->End('Confirma');

?>
