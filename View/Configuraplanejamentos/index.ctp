<?php // pr($configuraplanejamentos);          ?>

<?php
echo $this->element('submenu_configuraplanejamentos');
?>

<div align="center">
    <table style="width:80%; border:1px solid black;">
        <tr>
            <th>Id</th>
            <th>Semestre</th>
            <th>Versão</th>
            <th>Proprietário</th>            
            <th>Planejamento</th>
            <th>Clonar</th>
            <th>Versionar</th>            
            <th>Açao</th>        
        </tr>

        <?php
        foreach ($configuraplanejamentos as $c_configuraplanejamento) {
            // pr($c_configuraplanejamento);
            ?>
            <tr>
                <td><?php echo $c_configuraplanejamento['Configuraplanejamento']['id']; ?></td>
                <td><?php echo $this->Html->link($c_configuraplanejamento['Configuraplanejamento']['semestre'], '/configuraplanejamentos/view/' . $c_configuraplanejamento['Configuraplanejamento']['id']); ?></td>
                <td><?php echo $c_configuraplanejamento['Configuraplanejamento']['versao']; ?></td>
                <td><?php echo $c_configuraplanejamento['Configuraplanejamento']['proprietario']; ?></td>
                <td><?php
                    if ($c_configuraplanejamento['Planejamento']):
                        echo $this->Html->link("Ver planejamento", '/planejamentos/listar/semestre:' . $c_configuraplanejamento['Configuraplanejamento']['id']);
                    else:
                        echo $this->Html->link("Criar planejamento", '/planejamentos/novoplano/semestre_id:' . $c_configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $c_configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:' . $c_configuraplanejamento['Configuraplanejamento']['versao'] .'/opcao:planejar', NULL, 'Tem certeza?');
                    endif;
                    ?></td>
                <td><?php
                    if ((!empty($c_configuraplanejamento['Planejamento'])) and ($c_configuraplanejamento['Configuraplanejamento']['versao'] == 0)):
                        // echo $this->Html->link('Clonar', '/planejamentos/novoplano/semestre_id:' . $c_configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $c_configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:0' .'/opcao:configurar', NULL, 'Tem certeza?');
                        echo $this->Html->link('Clonar', '/configuraplanejamentos/add/semestre_id:' . $c_configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $c_configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:0' .'/opcao:configurar', NULL, 'Tem certeza?');
                    endif;
                    ?></td>
                <td><?php
                    if ($c_configuraplanejamento['Configuraplanejamento']['versao'] == 0):
                        echo $this->Html->link('Versionar', '/configuraplanejamentos/addnovaversao/semestre_id:' . $c_configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $c_configuraplanejamento['Configuraplanejamento']['semestre'] . '/versao:' . ($c_configuraplanejamento['Configuraplanejamento']['versao'] + 1) . '/opcao:planejar', NULL, 'Tem certeza?');
                    endif;
                    ?></td>
                <td><?php
                    if (!empty($c_configuraplanejamento['Planejamento'])):
                        if ($c_configuraplanejamento['Configuraplanejamento']['versao'] != 0):
                            echo $this->Html->link('Exluir', '/planejamentos/excluir/' . $c_configuraplanejamento['Configuraplanejamento']['id'], NULL, 'Tem certeza?');
                        endif;
                    endif;
                    ?></td>
            </tr>
            <?php
        }
        ?>

    </table>
</div>