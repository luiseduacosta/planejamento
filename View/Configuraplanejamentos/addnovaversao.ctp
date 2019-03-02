<?php // pr($configuracoes);  ?>

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
if ($versao == 0):
    echo $this->Form->Input('proprietario', array('value' => 'admin'));
else:
    echo $this->Form->Input('proprietario');
endif;
echo $this->Form->End('Confirma');
?>
