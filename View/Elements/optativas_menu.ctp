<div class = "submenusuperior">
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/optativas/edit/' . $this->params['pass']['0'], ['class' => 'aba']);
         echo $this->Html->link('Excluir', '/optativas/delete/' . $this->params['pass']['0'], ['class' => 'aba'], ['onClick' => 'return confirm ("Tem certeza?");']);
    } else {
        echo $this->Html->link('Inserir', '/optativas/add', ['class' => 'aba']);
    }
    ?>
    <?php // pr($this->params);   ?>
</div>