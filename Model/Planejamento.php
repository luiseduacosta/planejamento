<?php

class Planejamento extends AppModel {

    public $recursive = 2;
    public $name = 'Planejamento';
    public $useTable = 'planejamentos';
    public $primaryKey = 'id';
    public $belongsTo = [
        'Dia' => [
            'className' => 'Dia',
            'foreignKey' => 'dia_id',
            'joinTable' => 'dias'
        ],
        'Horario' => [
            'className' => 'Horario',
            'foreignKey' => 'horario_id',
            'joinTable' => 'horarios'
        ],
        'Disciplina' => [
            'className' => 'Disciplina',
            'foreignKey' => 'disciplina_id',
            'joinTable' => 'disciplinas'
        ],
        'Docente' => [
            'className' => 'Docente',
            'foreignKey' => 'docente_id',
            'joinTable' => 'docentes'
        ],
        'Sala' => [
            'className' => 'Sala',
            'foreignKey' => 'sala_id',
            'joinTable' => 'sala'
        ],
        'Configuraplanejamento' => [
            'className' => 'Configuraplanejamento',
            'foreignKey' => 'configuraplanejamento_id',
            'joinTable' => 'configuraplanejamentos'
        ],
        'Ementa' => [
            'className' => 'Ementa',
            'foreignKey' => 'ementa_id',
            'joinTable' => 'ementas'
        ],
        'Optativa' => [
            'className' => 'Optativa',
            'foreignKey' => 'optativa_id',
            'joinTable' => 'optativas'
        ]
    ];

}
