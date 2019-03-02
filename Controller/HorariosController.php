<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class HorariosController extends AppController {

    public $name = "Horarios";

    public function index() {

        $horarios = $this->Horario->find('all');
        // pr($horarios);
        $this->set('horarios', $horarios);
    }

        public function edit($id = NULL) {

        $this->Horario->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Horario->read();
        } else {
            if ($this->Horario->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect('/Horarios/view/' . $id);
            }
        }
    }

    public function view($id = NULL) {

        $disciplina = $this->Horario->find('first', array(
            'conditions' => array('Horario.id' => $id)
        ));
        // pr($disciplina);
        // die();
        $this->set('horario', $horario);
    }

    public function add() {

        if ($this->data) {
            if ($this->Horario->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect('/Horarios/view/' . $this->Horario->getLastInsertId());
            }
        }
    }

    public function delete($id = NULL) {
        
        $this->Horario->delete($id);
        $this->Session->setFlash("Horãrio excluída");
        // die("Disciplina excluída");
        $this->redirect('/Horarios/index/');
        
    }

}
