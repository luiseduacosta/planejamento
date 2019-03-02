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
        $docentes = $this->Paginate($condicoes);

        // $log = $this->Docente->getDataSource()->getLog(false, false);
        // debug($log);

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
                $this->redirect('/Docentes/view/' . $id);
            }
        }
    }

    public function view($id = NULL) {

        $this->Docente->recursive = 2;
        $docente = $this->Docente->find('first', array(
            'conditions' => array('Docente.id' => $id)
        ));
        // pr($docente);
        // die();
        $this->set('docente', $docente);
    }

    public function add() {

        // pr($this->data);
        // die();
        if ($this->data) {
            if ($this->Docente->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect('/Docentes/view/' . $this->Docente->getLastInsertId());
            }
        }
    }

    public function delete($id = NULL) {

        // $this->Docente->delete($id);
        $this->Session->setFlash("Docente não excluído");
        // die("Disciplina excluída");
        $this->redirect('/Docentes/index/');
    }

}
