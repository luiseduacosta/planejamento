<?php

// pr($diurno);             ?>
<?php // pr($horarios);                      ?>

<?php
echo $this->element('submenu_planejamentos');
?>

<div align="center">
    <h1>Planejamento horário diurno</h1>
</div>

<?php
$periodo_tabela = 1;
foreach ($diurno as $periodo_diurno):
    // pr($periodo_diurno); // Tr
    // if ($periodo_diurno['Planejamento']['periodo'] == 1):
    ?>
<div align="center">
    <H1>Tabela diurno. Período: <?php echo $periodo_tabela; ?></H1>
    <table style="width:80%; border:1px solid black;">
        <tr>
            <th width=17%>Horários</th>
            <th width=17%>Segunda-feira</th>
            <th width=17%>Terça-feira</th>
            <th width=17%>Quarta-feira</th>
            <th width=17%>Quinta-feira</th>
            <th width=17%>Sexta-feira</th>
        </tr>

            <?php
            $tempo_de_aula = 0;
            foreach ($periodo_diurno as $dias_diurno):
                // pr($dias_diurno);
                ?>
        <tr>
            <td><?php echo $horarios[$tempo_de_aula]['Horario']['horario']; ?></td>

                    <?php
                    foreach ($dias_diurno as $horarios_diurno):
                        ?>
            <td>
                <?php
                if ($horarios_diurno):        
                    foreach ($horarios_diurno as $d_horarios_diurno):
                        foreach ($d_horarios_diurno as $c_d_horarios_diurno):
                            // pr($c_d_horarios_diurno);
                            if (is_array($c_d_horarios_diurno)):
                            // pr($c_d_horarios_diurno);
                                if ($c_d_horarios_diurno['disciplina']):
                                        // pr($horarios_diurno['Planejamento']);
                                        // die();
                                    echo $this->Html->link($c_d_horarios_diurno['disciplina'], '/Planejamentos/listar/semestre_id:' . $c_d_horarios_diurno['configuraplanejamento_id'] . '/disciplina:' . $c_d_horarios_diurno['disciplina_id']) . '<br>';
                                    echo $this->Html->link($c_d_horarios_diurno['docente'], '/Planejamentos/listar/semestre_id:' . $c_d_horarios_diurno['configuraplanejamento_id'] . '/professor:' . $c_d_horarios_diurno['docente_id']) . '<br>';
                                    echo $c_d_horarios_diurno['sala'] . '<br>';
                                else:
                                    echo $c_d_horarios_diurno['disciplina'] . '<br>';
                                    echo $c_d_horarios_diurno['docente'] . '<br>';
                                    echo $c_d_horarios_diurno['sala'] . '<br>';                                   
                                endif;
                            endif;
                        endforeach;    
                    endforeach;
                endif;        
                        
            ?>
            </td>
            
            <?php
                    endforeach;
            ?>
        </tr>

                <?php
                $tempo_de_aula++;
            endforeach;
            ?>
    </table>
        <?php
        // endif;
        $periodo_tabela++;
        ?>
</div>

    <?php
endforeach;
?>

<div align="center">
    <h1>Planejamento horário noturno</h1>
</div>

<?php
$periodo_tabela = 1;
foreach ($noturno as $periodo_noturno):
    // pr($periodo_diurno); // Tr
    // if ($periodo_diurno['Planejamento']['periodo'] == 1):
    ?>
<div align="center">
    <H1>Tabela noturno. Período: <?php echo $periodo_tabela; ?></H1>
    <table style="width:80%; border:1px solid black;">
        <tr>
            <th width=17%>Horários</th>
            <th width=17%>Segunda-feira</th>
            <th width=17%>Terça-feira</th>
            <th width=17%>Quarta-feira</th>
            <th width=17%>Quinta-feira</th>
            <th width=17%>Sexta-feira</th>
        </tr>

            <?php
            $tempo_de_aula = 4;
            foreach ($periodo_noturno as $dias_noturno):
                // pr($dias_diurno[0]);
                ?>
        <tr>
            <td><?php echo $horarios[$tempo_de_aula]['Horario']['horario']; ?></td>

                    <?php
                    foreach ($dias_noturno as $horarios_noturno):
                        ?>
            <td>

                <?php
                if ($horarios_noturno):        
                    foreach ($horarios_noturno as $d_horarios_noturno):
                        foreach ($d_horarios_noturno as $c_d_horarios_noturno):
                            // pr($c_d_horarios_diurno);
                            if (is_array($c_d_horarios_noturno)):
                            // pr($c_d_horarios_diurno);
                                if ($c_d_horarios_noturno['disciplina']):
                                        // pr($horarios_diurno['Planejamento']);
                                        // die();
                                    echo $this->Html->link($c_d_horarios_noturno['disciplina'], '/Planejamentos/listar/semestre_id:' . $c_d_horarios_noturno['configuraplanejamento_id'] . '/disciplina:' . $c_d_horarios_noturno['disciplina_id']) . '<br>';
                                    echo $this->Html->link($c_d_horarios_noturno['docente'], '/Planejamentos/listar/semestre_id:' . $c_d_horarios_noturno['configuraplanejamento_id'] . '/professor:' . $c_d_horarios_noturno['docente_id']) . '<br>';
                                    echo $c_d_horarios_noturno['sala'] . '<br>';
                                else:
                                    echo $c_d_horarios_noturno['disciplina'] . '<br>';
                                    echo $c_d_horarios_noturno['docente'] . '<br>';
                                    echo $c_d_horarios_noturno['sala'] . '<br>';                                   
                                endif;
                            endif;
                        endforeach;    
                    endforeach;
                endif;        
                        
            ?>
                
            </td>
            
                        <?php
                    endforeach;
                    ?>
        </tr>


                <?php
                $tempo_de_aula++;
            endforeach;
            ?>
    </table>
        <?php
        // endif;
        $periodo_tabela++;
        ?>
</div>

    <?php
endforeach;
?>
