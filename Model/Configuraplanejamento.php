<?php

class Configuraplanejamento extends AppModel {

    public $name = 'Configuraplanejamento';

    public $useTable = 'configuraplanejamentos';

    public $primaryKey = 'id';

    public $hasMany = [
        'Planejamento' => [
            'className'  => 'Planejamento',
            'foreignKey' => 'configuraplanejamento_id',
        ]
    ];

    public $belongsTo = [
        'Usuarioplanejamento' => [
            'className'  => 'Usuarioplanejamento',
            'foreignKey' => 'usuarioplanejamento_id',
        ]
    ];

}
