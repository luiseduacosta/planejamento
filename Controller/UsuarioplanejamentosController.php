<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class UsuarioplanejamentosController extends AppController {

    public function beforeFilter() {

        parent::beforeFilter();
        $this->Auth->allow('add', 'login', 'logout'); // Permitindo que os usuários se registrem
    }

    public function login() {
        // die('Entrar');
        $this->Session->delete('usuarioplanejamento');
        if ($this->Auth->login()) {
            $user = $this->Auth->user();
            $this->Session->write('usuarioplanejamento', $user);
            $this->redirect($this->Auth->redirect());
        } else {
            // $this->Flash->error(__('Ingresse com seu Usuário e/ou senha.'));
        }
    }

    public function logout() {

        // die('1 Sair');
        $usuario_sair_1 = $this->Session->read('usuarioplanejamento');
        // pr($usuario_sair_1);
        $this->Session->delete('usuarioplanejamento');
        $usuario_sair_2 = $this->Session->read('usuarioplanejamento');
        // pr($usuario_sair_2); 
        $this->Session->setFlash('Até mais!');
        $this->redirect($this->Auth->logout());
        
    }

    public function index() {

        $this->Usuarioplanejamento->recursive = 0;
        $this->set('usuarios', $this->paginate());
    }

    public function view($id = null) {

        if (!$this->Usuarioplanejamento->exists($id)) {
            throw new NotFoundException(__('Usuário não autorizado'));
        }
        $this->set('usuarios', $this->User->findById($id));
    }

    public function add() {

        if ($this->request->is('post')) {
            $this->Usuarioplanejamento->create();
            if ($this->Usuarioplanejamento->save($this->request->data)) {
                $this->Flash->success(__('Usuário registrado!'));
                $this->redirect(array('controller' => 'planejamentos', 'action' => 'index'));
            } else {
                $this->Flash->error(__('Não foi possível fazer o cadastramento. Tente novamente.'));
            }
        }
    }

    public function edit($id = null) {

        $this->Usuarioplanejamento->id = $id;

        if (!$this->Usuarioplanejamento->exists()) {
            throw new NotFoundException(__('Nome do usuário inválido'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->Usuarioplanejamento->save($this->request->data)) {
                $this->Flash->success(__('Dados atualizados'));
                $this->redirect(array('controller' => 'planejamentos', 'action' => 'index'));
            } else {
                $this->Flash->error(__('Não foi possível atualizar o cadastramento.'));
            }
        } else {
            $this->request->data = $this->Usuarioplanejamento->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        if (!$this->request->is('post')) {
            throw new MethodNotAllowedException();
        }
        $this->Usuarioplanejamento->id = $id;
        if (!$this->Usuarioplanejamento->exists()) {
            throw new NotFoundException(__('Nome inválido'));
        }
        if ($this->Usuarioplanejamento->delete()) {
            $this->Flash->success(__('Usuario excluído'));
            $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('Usuário não foi excluído'));
        $this->redirect(array('action' => 'index'));
    }

}
