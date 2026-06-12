<div class = "submenusuperior">
    <?php echo $this->Html->link('Listar', '/Disciplinas', ['class' => 'aba']); ?>
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/Disciplinas/edit/' . $this->params['pass']['0'], ['class' => 'aba']);
         echo $this->Html->link('Excluir', '/Disciplinas/delete/' . $this->params['pass']['0'], ['class' => 'aba'], ['onClick' => 'return confirm ("Tem certeza?");']);
    } else {
        echo $this->Html->link('Inserir', '/Disciplinas/add', ['class' => 'aba']);
    }
    ?>
    <?php // pr($this->params);   ?>
</div>