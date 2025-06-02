<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class SalasController extends AppController {

    public $name = "Salas";

    public function index() {

        $salas = $this->Sala->find('all');
        // pr($horarios);
        $this->set('salas', $salas);
    }

    public function edit($id = NULL) {

        $this->Sala->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Sala->read();
        } else {
            if ($this->Sala->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect(['controller' => 'Salas', 'action' => 'view', $id]);
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
                $this->Session->setFlash('Dados inseridos');
                $this->redirect(['controller' => 'Salas', 'action' => 'view', $this->Sala->getLastInsertId()]);
                // $this->redirect(['/Salas/view/' . $this->Sala->getLastInsertId()]);
            }
        }
    }

    public function delete($id = NULL) {

        $this->Sala->delete($id);
        $this->Session->setFlash("Registro excluído");
        // die("Disciplina excluída");
        $this->redirect(['controller' => 'Salas', 'action' => 'index']);
    }

    public function tabela() {

        $semestre = $this->Session->read('semestre');
        if (empty($semestre)):
            $c_semestre = $this->Planejamento->find('first', array(
                'fields' => 'configuraplanejamento_id',
                'order' => array('configuraplanejamento_id' => 'DESC')));
            $semestre = $c_semestre['Planejamento']['configuraplanejamento_id'];
            $this->Session->write("semestre", $semestre);
        endif;

        $quantidade_salas = $this->Sala->find('count');
        // pr($quantidade_salas);
        // die();
        // Envio a lista das salas para o view
        $this->set('salatotal', $this->Sala->find('all', array(
                    'order' => 'Sala.id', 'Planejamento.dia_id')));

        $this->loadModel('Horario');
        $this->set('horarios', $this->Horario->find('all', array('order' => 'id')));

        //$q_salas = 0;
        for ($q_salas = 1; $q_salas < $quantidade_salas + 1; $q_salas++):
            for ($q_horarios = 1; $q_horarios < 7; $q_horarios++):
                for ($q_dias = 1; $q_dias < 6; $q_dias++):
                    // $this->Sala->recursive = -1;
                    $salas = $this->Sala->Planejamento->find('all', array(
                        'conditions' => array(
                            'Sala.sala' => $q_salas,
                            'Planejamento.configuraplanejamento_id' => $semestre,
                            'Planejamento.dia_id' => $q_dias,
                            'Planejamento.horario_id' => $q_horarios,
                        ),
                        'fields' => array('Sala.id', 'Sala.sala', 'Dia.dia', 'Dia.id', 'Horario.horario', 'Horario.id', 'Disciplina.disciplina', 'Disciplina.id')
                            )
                    );

                    if ($salas):
                        foreach ($salas as $c_sala):
                            // pr($c_sala);
                            $salaocupada[$q_dias]['sala'] = $c_sala['Sala']['sala'];
                            $salaocupada[$q_dias]['sala_id'] = $c_sala['Sala']['id'];
                            $salaocupada[$q_dias]['dia'] = $c_sala['Dia']['dia'];
                            $salaocupada[$q_dias]['dia_id'] = $c_sala['Dia']['id'];
                            $salaocupada[$q_dias]['horario'] = $c_sala['Horario']['horario'];
                            $salaocupada[$q_dias]['horario_id'] = $c_sala['Horario']['id'];
                            $salaocupada[$q_dias]['disciplina_id'] = $c_sala['Disciplina']['id'];
                            $salaocupada[$q_dias]['disciplina'] = $c_sala['Disciplina']['disciplina'];
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
        // pr($salaocupada_sala);
        $this->set('salaocupadas', $salaocupada_sala);
    }

}
