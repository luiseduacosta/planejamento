<?php

class UsuarioplanejamentosController extends AppController {

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('add', 'login', 'logout'); // Permitindo que os usuários se registrem
    }

    public function login() {
        if ($this->Auth->user()) {
            return $this->redirect($this->Auth->redirectUrl());
        }

        if ($this->request->is('post')) {
            if ($this->Auth->login()) {
                $this->Flash->success(__('Bem vindo!'));
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Ingresse com seu usuário e/ou senha.'));
        }
    }

    public function logout() {

        $this->Auth->logout();
        $this->Flash->success(__('Até mais!'));
        return $this->redirect(['controller' => 'planejamentos', 'action' => 'login']);
    }

    public function index() {

        $this->Usuarioplanejamento->recursive = 0;
        $this->set('usuarios', $this->paginate());
    }

    public function view($id = null) {

        if (!$this->Usuarioplanejamento->exists($id)) {
            throw new NotFoundException(__('Usuário não autorizado'));
        }
        $this->set('usuarios', $this->Usuarioplanejamento->findById($id));
    }

    public function add() {

        if ($this->request->is('post')) {
            $this->Usuarioplanejamento->create();
            if ($this->Usuarioplanejamento->save($this->request->data)) {
                $this->Flash->success(__('Usuário registrado!'));
                return $this->redirect(['controller' => 'planejamentos', 'action' => 'view', $this->Usuarioplanejamento->id]);
            } else {
                $this->Flash->error(__('Não foi possível fazer o cadastramento. Tente novamente.'));
            }
        }
    }

    public function edit($id = null) {

        $this->Usuarioplanejamento->id = $id;

        if (!$this->Usuarioplanejamento->exists()) {
            throw new NotFoundException(__('Usuário inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Usuarioplanejamento->save($this->request->data)) {
                $this->Flash->success(__('Dados atualizados'));
                return $this->redirect(['controller' => 'planejamentos', 'action' => 'view', $id]);
            } else {
                $this->Flash->error(__('Não foi possível atualizar os dados.'));
            }
        } else {
            $this->request->data = $this->Usuarioplanejamento->findById($id);
            unset($this->request->data['Usuarioplanejamento']['password']);
        }
    }

    public function delete($id = null) {
        
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Usuarioplanejamento->id = $id;
        if (!$this->Usuarioplanejamento->exists()) {
            throw new NotFoundException(__('Usuário inválido'));
        }
        if ($this->Usuarioplanejamento->delete()) {
            $this->Flash->success(__('Usuário excluído'));
        }
        $this->Flash->error(__('Usuário não foi excluído'));
        return $this->redirect(['action' => 'index']);
    }
}
