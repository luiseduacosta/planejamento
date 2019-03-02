<div class = "submenusuperior">
    <?php echo $this->Html->link('Listar', '/Planejamentos/listar', array('class' => 'aba')); ?>
        <?php echo $this->Html->link('Tabela', '/Planejamentos/index', array('class' => 'aba')); ?>
    <?php
    if (!empty($this->params['pass']['0'])) {
        echo $this->Html->link('Editar', '/Planejamentos/edit/' . $this->params['pass']['0'], array('class' => 'aba'));
        echo $this->Html->link('Excluir', '/Planejamentos/delete/' . $this->params['pass']['0'], array('class' => 'aba'), array('Confirm' => 'Confirma?'));
    } else {
        echo $this->Html->link('Inserir', '/Planejamentos/add', array('class' => 'aba'));
    }
    ?>
    <?php // pr($this->params); ?>
</div>