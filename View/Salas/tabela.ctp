<?php

// pr($salaocupadas);
// pr($salatotal); 
// die();

echo $this->element('submenu_salas');


foreach ($salatotal as $c_sala):
    // echo $c_sala['Sala']['sala'];
    // pr($c_sala['Planejamento']);
endforeach;

echo "<br>";

$s = 0;
foreach ($salaocupadas as $c_salaocupada):
    echo "<div align='center'>";
    echo "<h1>Sala ". $salatotal[$s]['Sala']['sala'] . "</h1>";
    echo "<table style = 'width:80%; border:1px solid black;'>";
    echo "<tr>";
    echo "<th width=15%>Horários</th>";
    echo "<th width=15%>Segunda</th>";
    echo "<th width=15%>Terça</th>";
    echo "<th width=15%>Quarta</th>";
    echo "<th width=15%>Quinta</th>";
    echo "<th width=15%>Sexta</th>";    
    echo "</tr>";
    $i = 0;    
    foreach ($c_salaocupada as $d_salaocupada):
        // pr($horarios[$i]);
        echo "<tr>";
    
        if ($i == 0):
            echo '<td>' . $horarios[$i]['Horario']['horario'] . '</td>';
        elseif ($i == 1):
            echo '<td>' . $horarios[$i]['Horario']['horario'] . '</td>';
        elseif ($i == 2):
            echo '<td>' . $horarios[$i]['Horario']['horario'] . '</td>';
        elseif ($i == 3):
            echo '<td>' . $horarios[$i]['Horario']['horario'] . '</td>';
        elseif ($i == 4):
            echo '<td>' . $horarios[$i]['Horario']['horario'] . '</td>';
        elseif ($i == 5):
            echo '<td>' . $horarios[$i]['Horario']['horario'] . '</td>';
        endif;
        
        foreach ($d_salaocupada as $h_salaocupada):
                echo '<td>' . $this->Html->link($h_salaocupada["disciplina"], '/planejamentos/listar/disciplina:' . $h_salaocupada["disciplina_id"]) . '</td>';                
        endforeach;
        $i++;
        echo "</tr>";
    endforeach;
    echo "</table>";
    $s++;
    echo "</div>";
endforeach;

?>