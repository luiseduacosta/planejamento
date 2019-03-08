<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class OptativasController extends AppController {

    public $name = "Optativas";
    public $paginate = array('limit' => 20,
        'order' => array('disciplina'));
    
    public function index() {

        $optativas = $this->Optativa->find('all');
        // pr($optativas);
        // die();
        $optativas = $this->Paginate('Optativa');
        $this->set('optativas', $optativas);
        
    }

    public function edit($id = NULL) {

        $this->Optativa->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Optativa->read();
        } else {
            if ($this->Optativa->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect('/optativas/view/' . $id);
            }
        }
    }

    public function view($id = NULL) {

        $optativa = $this->Optativa->find('first', array(
            'conditions' => array('Optativa.id' => $id)
        ));
        $this->set('optativa', $optativa);    }

    public function add() {

        if ($this->data) {
            if ($this->Optativa->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect('/optativas/view/' . $this->Optativa->getLastInsertId());
            }
        }
    }

    public function delete($id = NULL) {

        $this->Optativa->delete($id);
        $this->Session->setFlash("Registro excluído");
        // die("Disciplina excluída");
        $this->redirect('/optativas/index/');
    }

}
