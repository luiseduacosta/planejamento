<div class="users form">
    <?php // echo $this->Flash->render('auth'); ?>
    <?php echo $this->Form->create('Usuarioplanejamento'); ?>
    <fieldset>
        <legend>
            <?php echo __('Ingresse com seu nome de usuário e senha'); ?>
        </legend>
        <?php
        echo $this->Form->input('username', array('label' => 'Usuário'));
        echo $this->Form->input('password', array('label' => 'Senha'));
        ?>
    </fieldset>
<?php echo $this->Form->end(__('Confirma')); ?>

    <fieldset>
        <legend><?php echo __('Fazer cadastramento'); ?></legend>
<?php echo $this->Html->link('Cadastramento', '/usuarioplanejamentos/add'); ?>
    </fieldset>


</div>
