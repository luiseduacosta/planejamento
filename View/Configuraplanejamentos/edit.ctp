<?php

echo $this->Form->Create('Configuraplanejamento');
echo $this->Form->Input('semestre');
echo $this->Form->Input('versao');
echo $this->Form->Input('proprietario');
echo $this->Form->End('Confirma');

?>
