<?php

class DocentesController extends AppController {

    public $name = "Docentes";

    public $paginate = ['limit' => 15,
        'order' => ['nome']];

    public function index() {

        $parametros = $this->params['named'];
        $ativo = isset($parametros['ativo']) ? $parametros['ativo'] : NULL;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : NULL;

        $condicoes = NULL;

        if ($ativo == '0') {
            $this->Session->write("ativo", $ativo);
        } elseif ($ativo == '1') {
            $this->Session->write("ativo", $ativo);
        } elseif ($ativo == '2') {
            $this->Session->write("ativo", $ativo);
        } else {
            $ativo = $this->Session->read("ativo");
            if ($ativo) {
                $this->Session->write("ativo", $ativo);
            }
        }

        if ($ativo === '1') {
            $condicoes['OR'] = ['motivoegresso' => "", 'motivoegresso IS NULL'];
        } elseif ($ativo === '2') {
            $condicoes['motivoegresso != '] = "";
        }

        if ($departamento === NULL):
            $departamento = $this->Session->read("departamento");
            if ($departamento) {
                $condicoes['departamento'] = $departamento;
            };
        else:
            if ($departamento != '0') {
                $this->Session->write("departamento", $departamento);
                $condicoes['departamento'] = $departamento;
            } else {
                $this->Session->delete("departamento");
            }
        endif;

        $semestre_id = $this->Session->read('semestre');
        if ($semestre_id == "" or $semestre_id == NULL) {
            $this->Flash->error(__('Especifique o semestre.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
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
                $this->Flash->success(__('Atualizado.'));
                return $this->redirect(['controller' => 'Docentes', 'action' => 'view', $id]);
            }
        }
    }

    public function view($id = NULL) {

        $semestre_id = $this->Session->read('semestre');
        if (($semestre_id == "") or ( $semestre_id == NULL)):
            $this->Flash->error(__('Selecione o semestre.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
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
                $this->Flash->success(__('Dados inseridos.'));
                return $this->redirect(['controller' => 'Docentes', 'action' => 'view', $this->Docente->getLastInsertId()]);
            }
        }
    }

    public function delete($id = NULL) {

        $this->Docente->delete($id);
        $this->Flash->success(__('Docente excluído.'));
        return $this->redirect(['controller' => 'Docentes', 'action' => 'index']);
    }

}
