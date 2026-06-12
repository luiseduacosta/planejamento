<?php

App::uses('BlowfishPasswordHasher', 'Controller/Component/Auth');

class Usuarioplanejamento extends AppModel {

    public $name = 'Usuarioplanejamento';

    public $useTable = "users";

    public $hasMany = ['Configuraplanejamento'];

    public $primaryKey = 'id';

    public $validate = [
        'email' => [
            'required' => [
                'rule'    => 'notBlank',
                'message' => 'O e-mail é obrigatório',
            ],
            'format' => [
                'rule'    => 'email',
                'message' => 'Informe um e-mail válido',
            ],
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
            $passwordHasher = new BlowfishPasswordHasher();
            $this->data['Usuarioplanejamento']['password'] = $passwordHasher->hash(
                $this->data['Usuarioplanejamento']['password']
            );
        }
        return true;
    }
}
