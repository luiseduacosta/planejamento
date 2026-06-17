<?php

echo $this->Form->Create('Configuraplanejamento');
echo $this->Form->Input('semestre');
echo $this->Form->Input('versao');
if ($this->data['Configuraplanejamento']['versao'] == 0):
    echo $this->Form->Input('nome', ['type' => 'hidden']);
else:
    echo $this->Form->Input('nome', ['label' => 'Nome da versão']);
endif;
echo $this->Form->Input('usuarioplanejamento_id', ['type' => 'hidden', 'value' => $usuario['id']]);
echo $this->Form->End('Confirma');

?>
