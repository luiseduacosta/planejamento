<div class="users form">
    <?php echo $this->Form->create('Usuarioplanejamento'); ?>
    <fieldset>
        <legend><?php echo __('Adicionar usuário'); ?></legend>
        <?php
        echo $this->Form->input('email', array('label' => 'E-mail', 'type' => 'email'));
        echo $this->Form->input('password', array('label' => 'Senha'));
        echo $this->Form->input('role', array('label' => 'Papel',
            'options' => array('editor' => 'Editor', 'admin' => 'Admin')
        ));
        ?>
<?php echo $this->Form->end(__('Confirma')); ?>
    </fieldset>
</div>
