<?php

class HorariosController extends AppController
{

    public $name = "Horarios";

    public function index()
    {

        $horarios = $this->Horario->find('all');
        $this->set('horarios', $horarios);
    }

    public function view($id = NULL)
    {

        $disciplina = $this->Horario->find('first', [
            'conditions' => ['Horario.id' => $id]
        ]);
        $this->set('horario', $disciplina);
    }


    public function add()
    {

        if ($this->data) {
            if ($this->Horario->save($this->data)) {
                $this->Flash->success(__('Dados inseridos.'));
                return $this->redirect(['controller' => 'Horarios', 'action' => 'view', $this->Horario->getLastInsertId()]);
            }
        }
    }

    public function edit($id = NULL)
    {

        $this->Horario->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Horario->read();
        } else {
            if ($this->Horario->save($this->data)) {
                $this->Flash->success(__('Atualizado.'));
                return $this->redirect(['controller' => 'Horarios', 'action' => 'view', $id]);
            }
        }
    }

    public function delete($id = NULL)
    {

        $this->Horario->delete($id);
        $this->Flash->success(__('Horário excluído.'));
        return $this->redirect(['controller' => 'Horarios', 'action' => 'index']);

    }

}
