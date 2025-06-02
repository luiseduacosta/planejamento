<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Configuraplanejamento extends AppModel {
    /* @var Estagiario */
    /* @var Instituicao */

    public $name = 'Configuraplanejamento';
    public $useTable = 'configuraplanejamentos';
    public $primaryKey = 'id';
    public $hasMany = array(
        'Planejamento' => array(
            'className' => 'Planejamento',
            'foreignKey' => 'configuraplanejamento_id'
    ));
    public $belongsTo = array(
        'Usuarioplanejamento' => array(
            'className' => 'Usuarioplanejamento',
            'foreignKey' => 'usuarioplanejamento_id'
    ));

}
