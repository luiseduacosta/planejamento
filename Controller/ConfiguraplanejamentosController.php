<?php

App::uses('AppController', 'Controller');

class ConfiguraplanejamentosController extends AppController
{
    public $name = "Configuraplanejamentos";

    // -------------------------------------------------------------------------
    // Public actions
    // -------------------------------------------------------------------------

    public function index()
    {
        $usuario = $this->Auth->user();
        if (empty($usuario)) {
            return $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        }

        $this->set('usuario', $usuario);
        $this->Configuraplanejamento->recursive = 1;
        $this->set('configuraplanejamentos', $this->Configuraplanejamento->find('all', [
            'order' => ['semestre', 'versao']
        ]));
    }

    public function view($id = null)
    {
        if (!$this->Configuraplanejamento->exists($id)) {
            throw new NotFoundException(__('Configuração não encontrada.'));
        }

        $configuraplanejamento = $this->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $id]
        ]);

        // Bug fix: was storing the semestre label (e.g. "2025-1") but every
        // other action expects the config ID in this session key.
        $this->Session->write('semestre', $id);

        $this->set('configuraplanejamento', $configuraplanejamento);
    }

    public function add()
    {
        $parametros    = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id   = isset($parametros['semestre_id'])   ? $parametros['semestre_id']   : null;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : null;
        $versao        = isset($parametros['versao'])        ? $parametros['versao']        : null;

        // Bug fix: use Auth::user() consistently; session copy may be stale
        $usuario = $this->Auth->user();
        if (empty($usuario)) {
            $this->Flash->error(__('Ingresse com seu usuário e senha.'));
            return $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        }

        $this->set('usuario', $usuario);

        // Bug fix: initialise to null so it is always defined
        $proximo_semestre = null;
        if ($semestre_data) {
            [$ano, $digito] = explode('-', $semestre_data);
            $proximo_semestre = ($digito == 1)
                ? $ano . '-2'
                : ($ano + 1) . '-1';
        }

        if ($proximo_semestre) {
            $this->Configuraplanejamento->recursive = 1;
            $configuraplanejamento = $this->Configuraplanejamento->find('first', [
                'conditions' => [
                    'Configuraplanejamento.semestre' => $proximo_semestre,
                    'Configuraplanejamento.versao'   => $versao,
                ]
            ]);
        }

        if (empty($configuraplanejamento)) {
            $this->Flash->success(__('Clonar planejamento principal.'));
        } elseif (count($configuraplanejamento['Planejamento']) > 0) {
            $this->Flash->error(__('Planejamento já existe!'));
            return $this->redirect('/planejamentos/listar/semestre:' . $configuraplanejamento['Configuraplanejamento']['id']
                . '/semestre_data:' . $proximo_semestre . '/versao:' . $versao);
        } else {
            $this->Flash->success(__('Criar configuração para planejamento.'));
            return $this->redirect('/planejamentos/novoplano/semestre_id:' . $semestre_id
                . '/semestre_data:' . $proximo_semestre . '/versao:' . ($versao + 1));
        }

        $this->set('semestre_id',   $semestre_id);
        $this->set('semestre_data', $proximo_semestre);
        $this->set('versao',        $versao);
        $this->set('configuracoes', $this->Configuraplanejamento->find('all', ['order' => ['semestre', 'versao']]));

        if ($this->data) {
            $this->data['Configuraplanejamento']['usuarioplanejamento_id'] = $usuario['id'];

            if ($this->Configuraplanejamento->save($this->data)) {
                $this->Flash->success(__('Dados inseridos.'));
                return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar a configuração.'));
        }
    }

    public function addnovaversao()
    {
        $usuario = $this->Auth->user();
        if (empty($usuario)) {
            return $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        }

        $this->set('usuario', $usuario);

        $parametros    = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id   = isset($parametros['semestre_id'])   ? $parametros['semestre_id']   : null;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : null;
        $versao        = isset($parametros['versao'])        ? $parametros['versao']        : null;

        // Bug fix: find('first') returns $result['Model']['field'], not $result[0]['field'].
        // The old code used find('first') then accessed [0]['maxversao'], always getting null.
        $maxResult = $this->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.semestre' => $semestre_data],
            'fields'     => ['MAX(Configuraplanejamento.versao) AS maxversao'],
        ]);
        $novaversao = (int) $maxResult['Configuraplanejamento']['maxversao'] + 1;

        $this->set('semestre_id',   $semestre_id);
        $this->set('semestre_data', $semestre_data);
        $this->set('versao',        $novaversao);
        $this->set('configuracoes', $this->Configuraplanejamento->find('all', [
            'conditions' => ['semestre' => $semestre_data],
            'order'      => ['semestre', 'versao'],
        ]));

        if ($this->data) {
            $this->data['Configuraplanejamento']['usuarioplanejamento_id'] = $usuario['id'];

            if ($this->Configuraplanejamento->save($this->data)) {
                $this->Flash->success(__('Dados inseridos.'));
                return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            }
            $this->Flash->error(__('Não foi possível salvar a nova versão.'));
        }
    }

    public function edit($id = null)
    {
        $usuario = $this->Auth->user();
        if (empty($usuario)) {
            return $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        }

        $config = $this->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $id]
        ]);

        if (empty($config)) {
            throw new NotFoundException(__('Configuração não encontrada.'));
        }

        // Improvement: admins can edit any config; others only their own
        $isAdmin = isset($usuario['role']) && $usuario['role'] === 'admin';
        $isOwner = $config['Configuraplanejamento']['usuarioplanejamento_id'] == $usuario['id'];

        if (!$isAdmin && !$isOwner) {
            $this->Flash->error(__('Usuário não pode modificar esta configuração.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'view', $id]);
        }

        $this->set('usuario', $usuario);
        $this->Configuraplanejamento->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Configuraplanejamento->read();
            return;
        }

        if ($this->Configuraplanejamento->save($this->data)) {
            $this->Flash->success(__('Atualizado.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'view', $id]);
        }

        $this->Flash->error(__('Não foi possível atualizar a configuração.'));
    }

    public function delete($id = null)
    {
        $usuario = $this->Auth->user();
        if (empty($usuario)) {
            return $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        }

        $config = $this->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $id]
        ]);

        if (empty($config)) {
            throw new NotFoundException(__('Configuração não encontrada.'));
        }

        // Improvement: admins can delete any config; others only their own
        $isAdmin = isset($usuario['role']) && $usuario['role'] === 'admin';
        $isOwner = $config['Configuraplanejamento']['usuarioplanejamento_id'] == $usuario['id'];

        if (!$isAdmin && !$isOwner) {
            $this->Flash->error(__('Usuário não autorizado a excluir esta configuração.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        // Bug fix: the original fell through silently when the user was not the owner.
        // Now any non-owner/non-admin hits the check above and is redirected.
        $temPlanejamento = $this->Configuraplanejamento->Planejamento->find('first', [
            'conditions' => ['Planejamento.configuraplanejamento_id' => $id]
        ]);

        if ($temPlanejamento) {
            $this->Flash->error(__('Registro não pode ser excluído porque está associado a um planejamento.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        $this->Configuraplanejamento->delete($id);
        $this->Flash->success(__('Registro excluído.'));
        return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
    }
}
