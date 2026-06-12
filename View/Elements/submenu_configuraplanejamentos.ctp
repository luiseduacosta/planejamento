<div class = "submenusuperior">
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/configuraplanejamentos/edit/' . $this->params['pass']['0'], ['class' => 'aba']);
        echo $this->Html->link('Excluir', '/configuraplanejamentos/delete/' . $this->params['pass']['0'], ['class' => 'aba'], NULL, "Tem certeza?");
        echo $this->Html->link('Listar', '/configuraplanejamentos/index', ['class' => 'aba']);
    } else {
        echo $this->Html->link('Listar', '/configuraplanejamentos/index', ['class' => 'aba']);
        // echo $this->Html->link('Inserir', '/configuraplanejamentos/add', ['class' => 'aba']);
    }
    ?>
    <?php // pr($this->params);   ?>
</div>