<div class = "submenusuperior">
    <?php echo $this->Html->link('Listar', '/optativas/index', array('class' => 'aba')); ?>
    <?php echo $this->Html->link('Ementas', '/ementas', array('class' => 'aba')); ?>
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/optativas/edit/' . $this->params['pass']['0'], array('class' => 'aba'));
         echo $this->Html->link('Excluir', '/optativas/delete/' . $this->params['pass']['0'], array('class' => 'aba'), array('onClick' => 'return confirm ("Tem certeza?");'));
    } else {
        echo $this->Html->link('Inserir', '/optativas/add', array('class' => 'aba'));
    }
    ?>
    <?php // pr($this->params);   ?>
</div>