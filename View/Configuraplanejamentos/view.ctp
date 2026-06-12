<?php // pr($configuraplanejamento); ?>

<?php
echo $this->element('submenu_configuraplanejamentos');
?>

<table>
    <tr>
        <td>Semestre</td>
        <td><?php echo $configuraplanejamento['Configuraplanejamento']['semestre']; ?></td>
    </tr>
    <tr>
        <td>Versão</td>
        <td><?php echo $configuraplanejamento['Configuraplanejamento']['versao']; ?></td>
    </tr>
    <tr>
        <td>Criado por</td>
        <td><?php echo $configuraplanejamento['Usuarioplanejamento']['email']; ?></td>
    </tr>
</table>
