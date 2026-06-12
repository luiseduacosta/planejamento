<?php

class Disciplina extends AppModel {

    public $name = 'Disciplina';
    public $useTable = 'disciplinas';
    public $primaryKey = 'id';
    
    public $hasMany = ['Planejamento'];

}
