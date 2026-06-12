<div class = "submenusuperior">
    <?php echo $this->Html->link('Listar', '/Planejamentos/nucleotematico', ['class' => 'aba']); ?>
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/Planejamentos/edit/' . $this->params['pass']['0'], ['class' => 'aba']);
        echo $this->Html->link('Excluir', '/Planejamentos/delete/' . $this->params['pass']['0'], ['class' => 'aba'], ['Confirm' => 'Confirma?']);
    } else {
        echo $this->Html->link('Inserir', '/Planejamentos/add', ['class' => 'aba']);
    }
    ?>
    <?php // pr($this->params); ?>
</div>