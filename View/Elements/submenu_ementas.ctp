<div class = "submenusuperior">
    <?php echo $this->Html->link('Listar', '/ementas/index', ['class' => 'aba']); ?>
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/ementas/edit/' . $this->params['pass']['0'], ['class' => 'aba']);
         echo $this->Html->link('Excluir', '/ementas/delete/' . $this->params['pass']['0'], ['class' => 'aba'], ['onClick' => 'return confirm ("Tem certeza?");']);
    } else {
        echo $this->Html->link('Inserir', '/ementas/add', ['class' => 'aba']);
    }
    ?>
    <?php // pr($this->params);   ?>
</div>