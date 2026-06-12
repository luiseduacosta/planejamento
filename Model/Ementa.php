<?php

class Ementa extends AppModel {

    public $name = 'Ementa';
    public $useTable = 'ementas';
    public $primaryKey = 'id';
    public $belongsTo = [
        'Disciplina',
        'Docente'];
    
    public $hasMany = ['Planejamento'];

}
