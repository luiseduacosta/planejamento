<?php

App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Usuarioplanejamento extends AppModel {

    public $name = 'Usuarioplanejamento';

    public $useTable = "users";

    public $hasMany = ['Configuraplanejamento'];

    public $primaryKey = 'id';
    public $validate = [
        'username' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'Nome do usuario é obrigatório'
            ]
        ],
        'password' => [
            'required' => [
                'rule' => 'notBlank',
                'message' => 'A senha é obrigatória'
            ]
        ],
        'role' => [
            'valid' => [
                'rule' => ['inList', ['admin', 'editor']],
                'message' => 'Ingresse um papel: admin ou editor',
                'allowEmpty' => false
            ]
        ]
    ];

    public function beforeSave($options = []) {
        if (isset($this->data['Usuarioplanejamento']['password'])) {
            $passwordHasher = new SimplePasswordHasher();
            $this->data['Usuarioplanejamento']['password'] = $passwordHasher->hash(
                $this->data['Usuarioplanejamento']['password']
            );
        }
        return true;
    }
}
