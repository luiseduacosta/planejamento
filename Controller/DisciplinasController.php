<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DisciplinasController extends AppController {

    public $name = "Disciplinas";
    public $paginate = array('limit' => 20,
        'order' => ['disciplina']);

    public function index() {

        $parametros = $this->params['named'];
        $diurno = isset($parametros['diurno']) ? $parametros['diurno'] : NULL;
        $noturno = isset($parametros['noturno']) ? $parametros['noturno'] : NULL;

        $condicoes = NULL;

        $semestre_id = $this->Session->read('semestre');
        if ($semestre_id == 0 or $semestre_id == NULL) {
            $this->Session->setFlash('Selecione semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        } else {
            // $condicoes['configuraplanejamento_id'] = $semestre_id;
            $this->set('semestre_id', $semestre_id);
        }

        if ($diurno === NULL):
            // echo 'Periodo vazio ou zero ' . $periodo . '<br>';
            $diurno = $this->Session->read("diurno");
            if ($diurno) {
                $condicoes['periodo_diurno'] = $diurno;
            };
        else:
            // echo 'Periodo selecionado: ' . $periodo . '<br>';
            if ($diurno != '0') {
                $this->Session->write('diurno', $diurno);
                $condicoes['periodo_diurno'] = $diurno;
            } else {
                $this->Session->delete('diurno');
            }
        endif;

        if ($noturno === NULL):
            // echo 'Periodo vazio ou zero ' . $periodo . '<br>';
            $noturno = $this->Session->read("noturno");
            if ($noturno) {
                $condicoes['periodo_noturno'] = $noturno;
            };
        else:
            // echo 'Periodo selecionado: ' . $periodo . '<br>';
            if ($noturno != '0') {
                $this->Session->write("noturno", $noturno);
                $condicoes['periodo_noturno'] = $noturno;
            } else {
                $this->Session->delete('noturno');
            }
        endif;

        // pr($condicoes);

        $disciplinas = $this->Disciplina->find('all');
        // pr($disciplinas);
        // die();
        
        $disciplinas = $this->Paginate($condicoes);
        // pr($disciplinas);
        // die();

        $this->set('disciplinas', $disciplinas);
        $this->set('diurno', $diurno);
        $this->set('noturno', $noturno);
    }

    public function edit($id = NULL) {

        $this->Disciplina->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Disciplina->read();
        } else {
            if ($this->Disciplina->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect(['controller' => 'Disciplinas', 'action' => 'view', $id]);
            }
        }
    }

    public function view($id = NULL) {

        $disciplina = $this->Disciplina->find('first', [
            'conditions' => ['Disciplina.id' => $id]
        ]);
        $this->set('disciplina', $disciplina);
    }

    public function add() {

        if ($this->data) {
            if ($this->Disciplina->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect(['controller' => 'Disciplinas', 'action' => 'view', $this->Disciplina->getLastInsertId()]);
            }
        }
    }

    public function delete($id = NULL) {

        $this->Disciplina->delete($id);
        $this->Session->setFlash("Registro excluído");
        // die("Disciplina excluída");
        $this->redirect(['controller' => 'Disciplinas', 'action' => 'index']);
    }

}
