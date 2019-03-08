<table>
    <tr>
        <td>Usuário</td>
        <td>Papel</td>        
    </tr>
    <?php 
    foreach ($usuarios as $c_usuario):
    echo "<tr>";
    echo "<td>" . $c_usuario['Usuarioplanejamento']['username'] . "</td>";
    echo "<td>" . $c_usuario['Usuarioplanejamento']['role'] . "</td>";
    echo "</tr>";    
    endforeach;
    ?>
</table>
