<?php

class Optativa extends AppModel {

    public $name = 'Optativa';
    public $useTable = 'optativas';
    public $primaryKey = 'id';
    
    public $hasMany = ['Planejamento'];

}
