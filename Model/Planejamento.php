<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Planejamento extends AppModel {
    /* @var Estagiario */
    /* @var Instituicao */

    public $recursive = 2;
    public $name = 'Planejamento';
    public $useTable = 'planejamentos';
    public $primaryKey = 'id';
    public $belongsTo = array(
        'Dia' => array(
            'className' => 'Dia',
            'foreignKey' => 'dia_id',
            'joinTable' => 'dias'
        ),
        'Horario' => array(
            'className' => 'Horario',
            'foreignKey' => 'horario_id',
            'joinTable' => 'horarios'
        ),
        'Disciplina' => array(
            'className' => 'Disciplina',
            'foreignKey' => 'disciplina_id',
            'joinTable' => 'disciplinas'
        ),
        'Docente' => array(
            'className' => 'Docente',
            'foreignKey' => 'docente_id',
            'joinTable' => 'docentes'
        ),
        'Sala' => array(
            'className' => 'Sala',
            'foreignKey' => 'sala_id',
            'joinTable' => 'sala'
        ),
        'Configuraplanejamento' => array(
            'className' => 'Configuraplanejamento',
            'foreignKey' => 'configuraplanejamento_id',
            'joinTable' => 'configuraplanejamentos'
        ),
        'Ementa' => array(
            'className' => 'Ementa',
            'foreignKey' => 'ementa_id',
            'joinTable' => 'ementas'
        ),
        'Optativa' => array(
            'className' => 'Optativa',
            'foreignKey' => 'optativa_id',
            'joinTable' => 'optativas'
        )
    );

}
