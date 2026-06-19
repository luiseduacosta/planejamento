<?php

class DisciplinasController extends AppController {

    public $name = "Disciplinas";

    public $paginate = ['limit' => 20,
        'order' => ['disciplina']];

    public function index() {

        $parametros = $this->params['named'];
        $diurno    = isset($parametros['diurno'])    ? $parametros['diurno']    : NULL;
        $noturno   = isset($parametros['noturno'])   ? $parametros['noturno']   : NULL;
        $curriculo = isset($parametros['curriculo']) ? $parametros['curriculo'] : NULL;

        $condicoes = NULL;

        $semestre_id = $this->Session->read('semestre');
        if ($semestre_id == 0 or $semestre_id == NULL) {
            $this->Flash->error(__('Selecione o semestre.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        } else {
            $this->set('semestre_id', $semestre_id);
        }

        if ($diurno === NULL):
            $diurno = $this->Session->read("diurno");
            if ($diurno) {
                $condicoes['periodo_diurno'] = $diurno;
            };
        else:
            if ($diurno != '0') {
                $this->Session->write('diurno', $diurno);
                $condicoes['periodo_diurno'] = $diurno;
            } else {
                $this->Session->delete('diurno');
            }
        endif;

        if ($noturno === NULL):
            $noturno = $this->Session->read("noturno");
            if ($noturno) {
                $condicoes['periodo_noturno'] = $noturno;
            };
        else:
            if ($noturno != '0') {
                $this->Session->write("noturno", $noturno);
                $condicoes['periodo_noturno'] = $noturno;
            } else {
                $this->Session->delete('noturno');
            }
        endif;

        // Currículo filter (same pattern as diurno/noturno)
        if ($curriculo === NULL):
            $curriculo = $this->Session->read("curriculo");
            if ($curriculo) {
                $condicoes['curriculo'] = $curriculo;
            };
        else:
            if ($curriculo != '0') {
                $this->Session->write('curriculo', $curriculo);
                $condicoes['curriculo'] = $curriculo;
            } else {
                $this->Session->delete('curriculo');
            }
        endif;

        $disciplinas = $this->Disciplina->find('all');
        
        // Build list of distinct curriculo values for the filter dropdown
        $curriculos_raw = $this->Disciplina->find('all', [
            'fields'     => 'DISTINCT Disciplina.curriculo',
            'conditions' => ['Disciplina.curriculo !=' => ''],
            'order'      => 'Disciplina.curriculo ASC',
        ]);
        $curriculos_list = [];
        foreach ($curriculos_raw as $row) {
            $v = $row['Disciplina']['curriculo'];
            if ($v !== null && $v !== '') {
                $curriculos_list[$v] = $v;
            }
        }

        $disciplinas = $this->Paginate($condicoes);

        $this->set('disciplinas', $disciplinas);
        $this->set('diurno', $diurno);
        $this->set('noturno', $noturno);
        $this->set('curriculo', $curriculo);
        $this->set('curriculos_list', $curriculos_list);
    }

    public function edit($id = NULL) {

        $this->Disciplina->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Disciplina->read();
        } else {
            if ($this->Disciplina->save($this->data)) {
                $this->Flash->success(__('Atualizado.'));
                return $this->redirect(['controller' => 'Disciplinas', 'action' => 'view', $id]);
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
                $this->Flash->success(__('Dados inseridos.'));
                return $this->redirect(['controller' => 'Disciplinas', 'action' => 'view', $this->Disciplina->getLastInsertId()]);
            }
        }
    }

    public function delete($id = NULL) {

        $this->Disciplina->delete($id);
        $this->Flash->success(__('Registro excluído.'));
        return $this->redirect(['controller' => 'Disciplinas', 'action' => 'index']);
    }

}
