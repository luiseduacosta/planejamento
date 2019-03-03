<?php

/* SVN FILE: $Id$ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision$
 * @modifiedby    $LastChangedBy$
 * @lastmodified  $Date$
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('ESS/UFRJ'); ?>
            <?php echo $title_for_layout; ?>
        </title>
        <?php
        echo $this->Html->meta('icon');

        echo $this->Html->css('cake.generic');
        echo $this->Html->css('meus_estilos');

        echo $this->Html->script('jquery-1.9.1.min');

        echo $scripts_for_layout;
        ?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <?php $semestreporextenso = $this->Session->read('semestreporextenso'); ?>
                <h1><?php echo $this->Html->link(__('Planejamento da grade - ESS - UFRJ: ' . $semestreporextenso, true), '/configuraplanejamentos/index'); ?>
                </h1>
            </div>

            <div id='menu'>
                <?php echo $this->Html->link("Configuração", "/configuraplanejamentos/index", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("Planejamento", "/planejamentos/", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("Horarios", "/horarios/index", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("Disciplinas", "/disciplinas/index", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("Professores", "/docentes/index/ativo:1", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("Salas", "/salas/index", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("OTP", "/planejamentos/otp", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("N. temáticos", "/planejamentos/nucleotematico", array('class' => 'aba')); ?>
                <?php echo $this->Html->link("Optativas", "/planejamentos/optativa", array('class' => 'aba')); ?>
            </div>

            <div id="submenusuperior">

            </div>

            <div id="content">

                <?php echo $this->Session->flash(); ?>

                <?php echo $content_for_layout; ?>

            </div>

            <div id="footer">
                <?php
                echo $this->Html->link(
                        $this->Html->image('cake.power.gif', array('alt' => __("CakePHP: the rapid development php framework", true), 'border' => "0")), 'http://www.cakephp.org/', array('target' => '_blank', 'escape' => false)
                );
                ?>
            </div>

        </div>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
