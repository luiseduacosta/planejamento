<?php // pr($configuracoes);  ?>

<div align="center">
    <table style="width:80%; border:1px solid black;">
        <tr>
            <th>Id</th>
            <th>Semestre</th>
            <th>Versão</th>
        </tr>
        <?php foreach ($configuracoes as $c_configuracao): ?>
            <tr>
                <td>
                    <?php echo $c_configuracao['Configuraplanejamento']['id']; ?>
                </td>
                <td>
                    <?php echo $c_configuracao['Configuraplanejamento']['semestre']; ?>
                </td>
                <td>
                    <?php echo $c_configuracao['Configuraplanejamento']['versao']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php

echo $this->Form->Create('Configuraplanejamento');
echo $this->Form->Input('semestre', ['value' => $semestre_data]);
echo $this->Form->Input('versao', ['value' => $versao]);
echo $this->Form->Input('nome', ['label' => 'Identifique a versão com um nome']);
echo $this->Form->Input('usuarioplanejamento_id', ['type' => 'hidden', 'value' => $usuario['id']]);
echo $this->Form->End('Confirma');

?>
