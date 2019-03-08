<?php

echo $this->Form->Create('Configuraplanejamento');
echo $this->Form->Input('semestre');
echo $this->Form->Input('versao');
if ($this->data['Configuraplanejamento']['versao'] == 0):
    echo $this->Form->Input('versaonome', array('type' => 'hidden'));
else:
    echo $this->Form->Input('versaonome', array('label' => 'Nome da versão'));
endif;
echo $this->Form->Input('proprietario' , array('type' => 'hidden', 'value' => $usuario['username']));
echo $this->Form->Input('usuarioplanejamento_id', array('type' => 'hidden', 'value' => $usuario['id']));
echo $this->Form->End('Confirma');

?>
