<?php

class SalasController extends AppController {

    public $name = "Salas";

    public function index() {

        $salas = $this->Sala->find('all');
        $this->set('salas', $salas);
    }

    public function edit($id = NULL) {

        $this->Sala->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Sala->read();
        } else {
            if ($this->Sala->save($this->data)) {
                $this->Flash->success(__('Atualizado'));
                return $this->redirect(['controller' => 'Salas', 'action' => 'view', $id]);
            }
        }
    }

    public function view($id = NULL) {

        $sala = $this->Sala->find('first', [
            'conditions' => ['Sala.id' => $id]
        ]);
        $this->set('sala', $sala);
    }

    public function add() {

        if ($this->data) {
            if ($this->Sala->save($this->data)) {
                $this->Flash->success(__('Sala inserida.'));
                return $this->redirect(['controller' => 'Salas', 'action' => 'view', $this->Sala->getLastInsertId()]);
            }
        }
    }

    public function delete($id = NULL) {

        $this->Sala->delete($id);
        $this->Flash->success(__('Registro excluído.'));
        return $this->redirect(['controller' => 'Salas', 'action' => 'index']);
    }

    public function tabela() {

        $semestre = $this->Session->read('semestre');
        if (empty($semestre)):
            $c_semestre = $this->Planejamento->find('first', [
                'fields' => 'configuraplanejamento_id',
                'order' => ['configuraplanejamento_id' => 'DESC']
            ]);
            $semestre = $c_semestre['Planejamento']['configuraplanejamento_id'];
            $this->Session->write("semestre", $semestre);
        endif;

        $quantidade_salas = $this->Sala->find('count');

        // Envio a lista das salas para o view
        $this->set('salatotal', $this->Sala->find('all', [
                    'order' => 'Sala.id', 'Planejamento.dia_id']));

        $this->loadModel('Horario');
        $this->set('horarios', $this->Horario->find('all', ['order' => 'id']));

        for ($q_salas = 1; $q_salas < $quantidade_salas + 1; $q_salas++):
            for ($q_horarios = 1; $q_horarios < 7; $q_horarios++):
                for ($q_dias = 1; $q_dias < 6; $q_dias++):
                    $salas = $this->Sala->Planejamento->find('all', [
                        'conditions' => [
                            'Sala.sala' => $q_salas,
                            'Planejamento.configuraplanejamento_id' => $semestre,
                            'Planejamento.dia_id' => $q_dias,
                            'Planejamento.horario_id' => $q_horarios,
                        ],
                        'fields' => ['Sala.id', 'Sala.sala', 'Dia.dia', 'Dia.id', 'Horario.horario', 'Horario.id', 'Disciplina.disciplina', 'Disciplina.id']
                    ]);

                    if ($salas):
                        foreach ($salas as $sala):
                            $salaocupada[$q_dias]['sala'] = $sala['Sala']['sala'];
                            $salaocupada[$q_dias]['sala_id'] = $sala['Sala']['id'];
                            $salaocupada[$q_dias]['dia'] = $sala['Dia']['dia'];
                            $salaocupada[$q_dias]['dia_id'] = $sala['Dia']['id'];
                            $salaocupada[$q_dias]['horario'] = $sala['Horario']['horario'];
                            $salaocupada[$q_dias]['horario_id'] = $sala['Horario']['id'];
                            $salaocupada[$q_dias]['disciplina_id'] = $sala['Disciplina']['id'];
                            $salaocupada[$q_dias]['disciplina'] = $sala['Disciplina']['disciplina'];
                        endforeach;
                    else:
                        $salaocupada[$q_dias]['sala'] = $q_salas;
                        $salaocupada[$q_dias]['sala_id'] = '';
                        $salaocupada[$q_dias]['dia'] = $q_dias;
                        $salaocupada[$q_dias]['dia_id'] = '';
                        $salaocupada[$q_dias]['horario'] = $q_horarios;
                        $salaocupada[$q_dias]['horario_id'] = '';
                        $salaocupada[$q_dias]['disciplina_id'] = '';
                        $salaocupada[$q_dias]['disciplina'] = '';
                    endif;
                endfor;
                $salaocupada_dia[$q_horarios] = $salaocupada;
            endfor;
            $salaocupada_sala[$q_salas] = $salaocupada_dia;
        endfor;
        $this->set('salaocupadas', $salaocupada_sala);
    }

}
