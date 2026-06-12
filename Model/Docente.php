<?php

class Docente extends AppModel {

    public $name = 'Docente';
    public $useTable = 'docentes';
    public $primaryKey = 'id';
    public $hasMany = ['Planejamento'];

}
