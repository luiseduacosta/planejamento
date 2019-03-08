<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Usuarioplanejamento extends AppModel {

    public $name = 'Usuarioplanejamento';
    
    public $useTable = "usuarioplanejamentos";
    
    public $hasMany = array('Configuraplanejamento');
    
    public $validate = array(
        'username' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'Nome do usuario é obrigatório'
            )
        ),
        'password' => array(
            'required' => array(
                'rule' => array('notBlank'),
                'message' => 'A senha é obrigatória'
            )
        ),
        'role' => array(
            'valid' => array(
                'rule' => array('inList', array('admin', 'editor')),
                'message' => 'Ingresse um papel: admin ou editor',
                'allowEmpty' => false
            )
        )
    );

    public function beforeSave($options = array()) {
        if (isset($this->data['Usuarioplanejamento']['password'])) {
            $this->data['Usuarioplanejamento']['password'] = AuthComponent::password($this->data['Usuarioplanejamento']['password']);
        }
        return true;
    }
}
