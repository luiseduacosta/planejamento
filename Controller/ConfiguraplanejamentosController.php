<?php

class ConfiguraplanejamentosController extends AppController {

    public $name = "Configuraplanejamentos";

    public function index() {

        $this->Configuraplanejamento->recursive = 1;
        $configuracoes = $this->Configuraplanejamento->find('all', ['order' => ['semestre', 'versao']]);
        $this->set('configuraplanejamentos', $configuracoes);
    }

    public function edit($id = NULL) {

        $this->Configuraplanejamento->id = $id;
        $usuario = $this->Session->read('usuarioplanejamento');
        if (empty($usuario)) {
            $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        } else {
            // Verifica se o usuário é o proprietário do planejamento
            $proprietario = $this->Configuraplanejamento->find('first', ['conditions' => ['Configuraplanejamento.id' => $id]]);

            if ($proprietario['Configuraplanejamento']['usuarioplanejamento_id'] == $usuario['id']) {

                $this->set('usuario', $usuario);
                // pr($this->data);
                if (empty($this->data)) {
                    $this->data = $this->Configuraplanejamento->read();
                } else {
                    if ($this->Configuraplanejamento->save($this->data)) {
                        $this->Session->setFlash("Atualizado");
                        $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'view', $id]);
                    }
                }
            } else {
                $this->Session->setFlash('Usuário não pode modificar esta configuração');
                $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            }
        }
    }

    public function view($id = NULL) {

        $configuraplanejamento = $this->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $id]
        ]);
        $this->Session->write('semestre', $configuraplanejamento['Configuraplanejamento']['id']);
        $this->set('configuraplanejamento', $configuraplanejamento);
    }

    public function add() {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;

        $usuario = $this->Session->read('usuarioplanejamento');
        if (empty($usuario)) {
            $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        } else {
            $this->set('usuario', $usuario);
        }
        // Calculo o próximo semestre //
        if ($semestre_data):
            $divide_semestre = explode('-', $semestre_data);
            // pr($divide_semestre);
            // die();
            $ano_semestre = $divide_semestre[0];
            $digito_semestre = $divide_semestre[1];
            // echo $ano_semestre . " <-> " . $digito_semestre;
            // die();
            if ($digito_semestre == 1) {
                $proximo_ano = $ano_semestre;
                $proximo_digito = 2;
                // echo $proximo_ano . " - " . $proximo_digito;
                $proximo_semestre = $proximo_ano . "-" . $proximo_digito;
            } elseif ($digito_semestre == 2) {
                $proximo_ano = $ano_semestre + 1;
                $proximo_digito = 1;
                // echo $proximo_ano . " - " . $proximo_digito;
                $proximo_semestre = $proximo_ano . "-" . $proximo_digito;
            }

        endif;

        if ($proximo_semestre) {
            $this->Configuraplanejamento->recursive = 1;
            $configuraplanejamento = $this->Configuraplanejamento->find('first', [
                'conditions' => [
                    'Configuraplanejamento.semestre' => $proximo_semestre,
                    'Configuraplanejamento.versao' => $versao]
                ]
            );
        }

        if (empty($configuraplanejamento)) {
            echo "1 Clonar configuracação de planejamento" . "<br>";
            $this->Session->setFlash('Clonar planejamento principal');
        } elseif (sizeof($configuraplanejamento['Planejamento']) > 0) {
            echo "Planejamento já existe" . "<br>";
            $this->Session->setFlash('Planejamento já existe!');
            $this->redirect('/planejamentos/listar/semestre:' . $configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $proximo_semestre . '/versao:' . $versao);
        } else {
            echo "2 Criar configuração para planejamento" . "<br>";
            $this->Session->setFlash('Criar configuração para planejamento');
            $this->redirect('/planejamentos/novoplano/semestre_id:' . $semestre_id . '/semestre_data:' . $semestre_data . '/versao:' . ($versao + 1));
        }

        $this->set('semestre_id', $semestre_id);
        $this->set('semestre_data', $proximo_semestre);
        $this->set('versao', $versao);

        $this->set('configuracoes', $configuracoes = $this->Configuraplanejamento->find('all', array('order' => array('semestre', 'versao'))));

        if ($this->data) {
            if ($this->Configuraplanejamento->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect('/configuraplanejamentos/index');
            }
        }
    }

    public function addnovaversao() {

        $usuario = $this->Session->read('usuarioplanejamento');
        if (empty($usuario)) {
            $this->redirect('/usuarioplanejamentos/login');
        } else {
            $this->set('usuario', $usuario);
        }
        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;

        $this->Configuraplanejamento->recursive = 0;
        $configuraplanejamento = $this->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.semestre' => $semestre_data],
            'fields' => ['MAX(Configuraplanejamento.versao) as maxversao']
            ]
        );

        $novaversao = $configuraplanejamento[0]['maxversao'];

        $this->set('semestre_id', $semestre_id);
        $this->set('semestre_data', $semestre_data);
        $this->set('versao', ($novaversao + 1));

        $this->set('configuracoes', $configuracoes = $this->Configuraplanejamento->find('all', [
            'conditions' => ['semestre' => $semestre_data],
            'order' => ['semestre', 'versao']
            ]
            )
        );

        if ($this->data) {
            if ($this->Configuraplanejamento->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            }
        }
    }

    public function delete($id = NULL) {

        $usuario = $this->Session->read('usuarioplanejamento');
        if (empty($usuario)) {
            $this->redirect(['controller' => 'usuarioplanejamentos', 'action' => 'login']);
        } else {
            // Verifica se o usuário é o proprietário do registro
            $proprietario = $this->Configuraplanejamento->find('first', ['conditions' => ['Configuraplanejamento.id' => $id]]);
            // echo $proprietario['Configuraplanejamento']['usuarioplanejamento_id'] . " " . $usuario['id'];
            if ($proprietario['Configuraplanejamento']['usuarioplanejamento_id'] == $usuario['id']) {

                $verifica = $this->Configuraplanejamento->Planejamento->find('first', ['conditions' => ['Planejamento.configuraplanejamento_id' => $id]]);
                if ($verifica):
                    $this->Session->setFlash('Registro não pode ser excluído porque está associado a uma planilha');
                    $this->redirect(['controller' => 'planejamentos', 'action' => 'listar', '?' => ['semestre', $id]]);
                else:
                    $this->Configuraplanejamento->delete($id);
                    $this->Session->setFlash("Registro excluído");
                    $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
                endif;
            }
        }
    }
}