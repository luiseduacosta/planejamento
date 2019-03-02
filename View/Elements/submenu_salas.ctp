<div class = "submenusuperior">
    <?php echo $this->Html->link('Listar', '/Salas/index', array('class' => 'aba')); ?>
    <?php echo $this->Html->link('Tabela', '/Salas/tabela', array('class' => 'aba')); ?>    
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/Salas/edit/' . $this->params['pass']['0'], array('class' => 'aba'));
        echo $this->Html->link('Excluir', '/Salas/delete/' . $this->params['pass']['0'], array('class' => 'aba'), array('onClick' => 'return confirm ("Tem certeza?");'));
    } else {
        echo $this->Html->link('Inserir', '/Salas/add', array('class' => 'aba'));
    }
    ?>
    <?php // pr($this->params);   ?>
</div>