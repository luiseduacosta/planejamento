<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DocentesController extends AppController {

    public $name = "Docentes";
    public $paginate = array('limit' => 15,
        'order' => array('nome'));

    public function index() {

        $parametros = $this->params['named'];
        // pr($parametros);
        $ativo = isset($parametros['ativo']) ? $parametros['ativo'] : NULL;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : NULL;

        // pr($ativo);
        // die();

        $condicoes = NULL;

        if ($ativo == '0') {
            // echo 'Zero' . '<br>';
            $this->Session->write("ativo", $ativo);
            // $this->Session->write("ativo", $ativo);
        } elseif ($ativo == '1') {
            $this->Session->write("ativo", $ativo);
            // $condicoes['OR'] = array('motivoegresso' => "", 'motivoegresso IS NULL');
        } elseif ($ativo == '2') {
            $this->Session->write("ativo", $ativo);
            // $condicoes['NOT'] = array('motivoegresso' => 'NULL');
        } else {
            $ativo = $this->Session->read("ativo");
            if ($ativo) {
                $this->Session->write("ativo", $ativo);
            }
        }

        // pr($ativo);

        if ($ativo === '1') {
            $condicoes['OR'] = array('motivoegresso' => "", 'motivoegresso IS NULL');
        } elseif ($ativo === '2') {
            $condicoes['motivoegresso != '] = "";
        }

        if ($departamento === NULL):
            // echo "Departamento vazia ou zero " . $departamento . '<br>';
            $departamento = $this->Session->read("departamento");
            if ($departamento) {
                $condicoes['departamento'] = $departamento;
            };
        else:
            // echo 'Departamento selecionado: ' . $departamento . '<br>';
            if ($departamento != '0') {
                $this->Session->write("departamento", $departamento);
                $condicoes['departamento'] = $departamento;
            } else {
                $this->Session->delete("departamento");
            }
        endif;

        // pr($condicoes);
        $semestre_id = $this->Session->read('semestre');
        if ($semestre_id == "" or $semestre_id == NULL) {
            $this->Session->setFlash('Especifique o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        } else {
            $this->set('semestre_id', $semestre_id);
        }

        $docentes = $this->Paginate($condicoes);

        $this->set('ativo', $ativo);
        $this->set('departamento', $departamento);
        $this->set('docentes', $docentes);
    }

    public function edit($id = NULL) {

        $this->Docente->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Docente->read();
        } else {
            if ($this->Docente->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect(['controller' => 'Docentes', 'action' => 'view', $id]);
            }
        }
    }

    public function view($id = NULL) {

        $semestre_id = $this->Session->read('semestre');
        if (($semestre_id == "") or ( $semestre_id == NULL)):
            $this->Session->setFlash('Selecione o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        else:
            $this->set('semestre_id', $semestre_id);
        endif;
        
        $this->Docente->recursive = 2;
        $docente = $this->Docente->find('first', [
            'conditions' => ['Docente.id' => $id]
        ]);
        $this->set('docente', $docente);
    }

    public function add() {

        if ($this->data) {
            if ($this->Docente->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect(['controller' => 'Docentes', 'action' => 'view', $this->Docente->getLastInsertId()]);
            }
        }
    }

    public function delete($id = NULL) {

        $this->Docente->delete($id);
        $this->Session->setFlash("Docente excluído");
        $this->redirect(['controller' => 'Docentes', 'action' => 'index']);
    }

}
