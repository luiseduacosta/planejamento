<?php // pr($configuracoes);  ?>
<?php pr($usuario);  ?>

<div align="center">
    <table style="width:80%; border:1px solid black;">
        <tr>
            <th>Id</th>
            <th>Semestre</th>
            <th>Versão</th>
            <th>Proprietário</th>            
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
                <td>
                    <?php echo $c_configuracao['Configuraplanejamento']['proprietario']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php

echo $this->Form->Create('Configuraplanejamento');
echo $this->Form->Input('semestre', array('value' => $semestre_data));
echo $this->Form->Input('versao', array('value' => $versao));
echo $this->Form->Input('versaonome', array('label' => 'Identifique a versão com um nome'));
echo $this->Form->Input('proprietario', array('type' => 'hidden', 'value' => $usuario['username']));
echo $this->Form->Input('usuarioplanejamento_id', array('type' => 'hidden', 'value' => $usuario['id']));
echo $this->Form->End('Confirma');

?>
