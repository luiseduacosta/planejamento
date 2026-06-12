<?php // pr($configuraplanejamentos);          ?>

<?php
$this->element('submenu_configuraplanejamentos');
?>

<div align="center">
    <table style="width:80%; border:1px solid black;">
        <tr>
            <th>Id</th>
            <th>Semestre</th>
            <th>Versão</th>
            <th>Planejamento</th>
            <th>Clonar</th>
            <th>Versionar</th>            
            <th>Açao</th>        
        </tr>

        <?php
        foreach ($configuraplanejamentos as $configuraplanejamento) {
            ?>
            <tr>
                <td><?php echo $configuraplanejamento['Configuraplanejamento']['id']; ?></td>
                <td><?php echo $this->Html->link($configuraplanejamento['Configuraplanejamento']['semestre'], ['controller' => 'configuraplanejamentos', 'action' => 'view', $configuraplanejamento['Configuraplanejamento']['id']]); ?></td>
                <td><?php echo $configuraplanejamento['Configuraplanejamento']['versao']; ?></td>
                <td><?php
                    if ($configuraplanejamento['Planejamento']):
                        echo $this->Html->link("Ver planejamento", '/planejamentos/listar/semestre_id:' . $configuraplanejamento['Configuraplanejamento']['id']. '/semestre_data:' . $configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:' . $configuraplanejamento['Configuraplanejamento']['versao'] . '/usuario:' . $configuraplanejamento['Configuraplanejamento']['usuarioplanejamento_id']);
                    else:
                        echo $this->Html->link("Criar planejamento", '/planejamentos/novoplano/semestre_id:' . $configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:' . $configuraplanejamento['Configuraplanejamento']['versao'] . '/usuario:' . $configuraplanejamento['Configuraplanejamento']['usuarioplanejamento_id'], NULL, 'Tem certeza?');
                    endif;
                    ?></td>
                <td><?php
                    if ((!empty($configuraplanejamento['Planejamento'])) and ($configuraplanejamento['Configuraplanejamento']['versao'] == 0)):
                        echo $this->Html->link('Clonar', '/configuraplanejamentos/add/semestre_id:' . $configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:0', NULL, 'Tem certeza?');
                    endif;
                    ?></td>
                <td><?php
                    if ($configuraplanejamento['Configuraplanejamento']['versao'] == 0):
                        echo $this->Html->link('Versionar', '/configuraplanejamentos/addnovaversao/semestre_id:' . $configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:' . ($configuraplanejamento['Configuraplanejamento']['versao'] + 1), NULL, 'Tem certeza?');
                    endif;
                    ?></td>
                <td><?php
                    if (!empty($configuraplanejamento['Planejamento'])):
                        if ($configuraplanejamento['Configuraplanejamento']['versao'] != 0):
                            echo $this->Html->link('Excluir', '/planejamentos/excluir/' . $configuraplanejamento['Configuraplanejamento']['id'], NULL, 'Tem certeza?');
                        endif;
                    endif;
                    ?></td>
            </tr>
            <?php
        }
        ?>

    </table>
</div>