<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Optativa extends AppModel {
    /* @var Estagiario */
    /* @var Instituicao */

    public $name = 'Optativa';
    public $useTable = 'optativas';
    public $primaryKey = 'id';
    
    public $hasMany = array('Planejamento');

}
