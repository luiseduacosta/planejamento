<?php
// pr($ativo);
// pr($semestre_id);
?>

<?php
echo $this->element('submenu_professores');
?>

<?php
echo $this->Html->script("jquery", array('inline' => false));
echo $this->Html->scriptBlock('

$(document).ready(function() {

var url = location.hostname;

var base_url = window.location.pathname.split("/");

$("#DocenteAtivo").change(function() {
	var ativo = $(this).val();
        // alert(ativo); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Docentes/index/ativo:"+ativo;
        } else {
            window.location="/Docentes/index/ativo:"+ativo;
        }
})

$("#DocenteDepartamento").change(function() {
	var departamento = $(this).val();
        // alert(departamento); 
        if (url === "localhost") {
            window.location="/" + base_url[1] + "/Docentes/index/departamento:"+departamento;
        } else {
            window.location="/Docentes/index/departamento:"+departamento;
        }
})

});

', array("inline" => false));
?>

<div align="center">
    <?php
    echo $this->Form->Create('Docente', array('inputDefaults' => array('label' => false, 'div' => false)));
    echo $this->Form->Input('ativo', array('type' => 'select', 'options' => array('1' => 'Ativos', '2' => 'Inativos', '0' => 'Todos'), 'selected' => array($ativo)));
// echo $this->Form->End('Confirma');

    echo $this->Form->Create('Docente', array('inputDefaults' => array('label' => false, 'div' => false)));
    echo $this->Form->Input('departamento', array('type' => 'select', 'options' => array('Fundamentos' => 'Fundamentos', 'Métodos' => 'Métodos', 'Políticas' => 'Políticas', 'Outro' => 'Outro ou s/d'), 'selected' => array($departamento), 'empty' => array('0' => 'Departamentos')));
// echo $this->Form->End('Confirma');
    ?>
</div>

<div align = 'center'>

    <?php echo $this->Paginator->numbers(); ?>

    <br>

    <?php
// Shows the next and previous links
    echo $this->Paginator->prev(
            '« Anterior ', null, null, array('class' => 'disabled')
    );
    echo $this->Paginator->next(
            ' Posterior »', null, null, array('class' => 'disabled')
    );
    ?>

    <br>

    <?php
    echo $this->Paginator->counter();
    ?>
</div>


<table>
    <tr>
        <th><?php echo $this->Paginator->sort('id', 'Id'); ?></th>
        <th><?php echo $this->Paginator->sort('nome', 'Professor(a)'); ?></th>
        <th><?php echo $this->Paginator->sort('departamento', 'Departamento'); ?></th>
        <th><?php echo $this->Paginator->sort('disciplinas', 'Disciplinas'); ?></th>        
        <th><?php echo $this->Paginator->sort('tipocargo', 'Cargo'); ?></th>        
        <th><?php echo $this->Paginator->sort('situacao', 'Situação'); ?></th>        
        <th><?php echo $this->Paginator->sort('motivoegresso', 'Egresso'); ?></th>        
    </tr>

    <?php
    foreach ($docentes as $c_docente) {
        // pr($c_docente);
        ?>
        <tr>
            <td><?php echo $c_docente['Docente']['id']; ?></td>
            <td><?php echo $this->Html->link($c_docente['Docente']['nome'], '/Docentes/view/' . $c_docente['Docente']['id']); ?></td>
            <td><?php echo $c_docente['Docente']['departamento']; ?></td>
            <td><?php
                foreach ($c_docente['Planejamento'] as $c_c_docente):
                    if (is_array($c_c_docente)):
                        // pr($c_c_docente);
                        if ($c_c_docente['configuraplanejamento_id'] == $semestre_id):
                            echo ' ', $this->Html->link($c_c_docente['disciplina_id'], '/planejamentos/listar/professor:' . $c_c_docente['docente_id']);
                        endif;
                    endif;
                endforeach;
                ?></td>
            <td><?php echo $c_docente['Docente']['tipocargo']; ?></td>
            <td><?php echo $c_docente['Docente']['situacao']; ?></td>
            <td><?php echo $c_docente['Docente']['motivoegresso']; ?></td>            
        </tr>
        <?php
    }
    ?>

</table>
