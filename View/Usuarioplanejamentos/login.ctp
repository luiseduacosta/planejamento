<div class="users form">
    <?php // echo $this->Flash->render('auth'); ?>
    <?php echo $this->Form->create('Usuarioplanejamento'); ?>
    <fieldset>
        <legend>
            <?php echo __('Ingresse com seu nome de usuário e senha'); ?>
        </legend>
        <?php
        echo $this->Form->input('email', array('label' => 'E-mail', 'type' => 'email'));
        echo $this->Form->input('password', array('label' => 'Senha'));
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Confirma')); ?>

    <fieldset>
        <legend><?php echo __('Fazer cadastramento'); ?></legend>
<?php echo $this->Html->link('Cadastramento', ['controller' => 'usuarioplanejamentos', 'action' => 'add']); ?>
    </fieldset>
</div>
