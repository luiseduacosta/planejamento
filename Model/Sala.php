<?php

class Sala extends AppModel {

    public $name = 'Sala';
    public $useTable = 'salas';
    public $primaryKey = 'id';

    public $hasMany = ['Planejamento'];
    
}
