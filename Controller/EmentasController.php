<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class EmentasController extends AppController {

    public $name = "Ementas";
    public $paginate = [
        'order' => ['Ementa.titulo' => 'desc']];

    public function index() {

        $semestre = $this->Session->read('semestre');
        if (empty($semestre)):
            $c_semestre = $this->Planejamento->find('first', [
                'fields' => ['configuraplanejamento_id'],
                'order' => ['configuraplanejamento_id' => 'DESC']
                ]
            );

            $semestre = $c_semestre['Planejamento']['configuraplanejamento_id'];
            $this->Session->write("semestre", $semestre);
        endif;

        $condicoes['configuraplanejamento_id'] = $semestre;
        $this->set('ementas', $this->paginate('Ementa', $condicoes));
    }

    public function edit($id = NULL) {

        $this->Ementa->id = $id;

        if (empty($this->data)) {
            $this->set('disciplinas', $disciplinas = $this->Ementa->Disciplina->find('list', [
                'fields' => ['disciplina']
            ]));
            $this->set('docentes', $docentes = $this->Ementa->Docente->find('list', [
                'fields' => ['nome'],
                'conditions' => ['or' => [
                    'motivoegresso' => "",
                    ' motivoegresso' => 'IS NULL']
                ]
            ])
                );

            $this->data = $this->Ementa->read();
        } else {
            // pr($this->data);
            if ($this->Ementa->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect(['controller' => 'ementas', 'action' => 'view', $id]);
            }
        }
    }

    public function view($id = NULL) {

        $ementa = $this->Ementa->find('first', [
            'conditions' => ['Ementa.id' => $id]
            ]
        );

        // pr($ementa);
        if ($ementa) {
            $this->set('ementa', $ementa);
        }
    }

    public function add($id = NULL) {

        if ($this->data) {
            if ($this->Ementa->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect(['controller' => 'ementas', 'action' => 'view', $this->Ementa->getLastInsertId()]);
            }
        } else {

            $disciplinas = $this->Ementa->Disciplina->find('list', [
                'fields' => ['Disciplina.disciplina'],
                'conditions' => ['Disciplina.optativa' => 1],
                'order' => ['Disciplina.disciplina']
                ]
            );

            $this->set('disciplinas', $disciplinas);

            $docentes = $this->Ementa->Docente->find('list', [
                'conditions' => ['OR' => [['motivoegresso' => ""], ['motivoegresso' => null]]],
                'fields' => ['Docente.nome'],
                'order' => ['nome']
                ]
            );

            $this->set('docentes', $docentes);
            // die();
            $semestre = $this->Session->read("semestre");
            if (empty($semestre)):
                $c_semestre = $this->Planejamento->find('first', [
                    'fields' => ['configuraplanejamento_id'],
                    'order' => ['configuraplanejamento_id' => 'DESC']
                    ]
                );
                $semestre = $c_semestre['Planejamento']['configuraplanejamento_id'];

                $this->Session->write("semestre", $semestre);
            endif;

            $this->set('semestre', $semestre);
        }
    }

    public function delete($id = NULL) {

        $this->Ementa->delete($id);
        $this->Session->setFlash("Registro excluído");
        // die("Disciplina excluída");
        $this->redirect(['controller' => 'ementas', 'action' => 'index']);
    }

}
