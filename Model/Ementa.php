<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Ementa extends AppModel {
    /* @var Estagiario */
    /* @var Instituicao */

    public $name = 'Ementa';
    public $useTable = 'ementas';
    public $primaryKey = 'id';
    public $belongsTo = array(
        'Disciplina',
        'Docente');
    
    public $hasMany = array('Planejamento');

}
