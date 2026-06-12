<div class = "submenusuperior">
<?php echo $this->Html->link('Listar', '/Docentes', ['class' => 'aba']); ?>
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/Docentes/edit/' . $this->params['pass']['0'], ['class' => 'aba']);
        echo $this->Html->link('Excluir', '/Docentes/delete/' . $this->params['pass']['0'], ['class' => 'aba'], ['onClick' => 'return confirm ("Tem certeza?");']);
    } else {
        echo $this->Html->link('Inserir', '/Docentes/add', ['class' => 'aba']);
    }
    ?>
</div>