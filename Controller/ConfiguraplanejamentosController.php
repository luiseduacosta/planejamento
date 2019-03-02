<?php

class ConfiguraplanejamentosController extends AppController {

    public $name = "Configuraplanejamentos";

    public function index() {

        $this->Configuraplanejamento->recursive = 1;
        $configuracoes = $this->Configuraplanejamento->find('all', array('order' => array('semestre', 'versao')));
        // $planejamento = $this->Configuraplanejamento->Planejamento->find('first', array('conditions' => array('Planejamento.configuraplanejamnto_id')));
        // pr($configuracoes);
        // die();
        $this->set('configuraplanejamentos', $configuracoes);
    }

    public function edit($id = NULL) {

        $this->Configuraplanejamento->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Configuraplanejamento->read();
        } else {
            if ($this->Configuraplanejamento->save($this->data)) {
                // print_r($this->data);
                $this->Session->setFlash("Atualizado");
                $this->redirect('/configuraplanejamentos/view/' . $id);
            }
        }
    }

    public function view($id = NULL) {

        $configuraplanejamento = $this->Configuraplanejamento->find('first', array(
            'conditions' => array('Configuraplanejamento.id' => $id)
        ));

        $this->Session->write('semestre', $configuraplanejamento['Configuraplanejamento']['id']);
        // pr($configuraplanejamento['Configuraplanejamento']['semestre']);
        // die();
        $this->set('configuraplanejamento', $configuraplanejamento);
    }

    public function add() {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;

        // pr('1 Semestre id: ' . $semestre_id);
        // pr('1 Semestre data: ' . $semestre_data);
        // pr('1 Versão: ' . $versao);
        // die();
        // Calculo o próximo semestre //
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

        // pr($proximo_semestre);
        if ($proximo_semestre):
            $this->Configuraplanejamento->recursive = 1;
            $configuraplanejamento = $this->Configuraplanejamento->find('first', array(''
                . 'conditions' => array(
                    'Configuraplanejamento.semestre' => $proximo_semestre,
                    'Configuraplanejamento.versao' => $versao)));
        endif;
        
        // pr($configuraplanejamento);
        // die();

        if (empty($configuraplanejamento)) {
            echo "1 Clonar configuracação de planejamento" . "<br>";
            $this->Session->setFlash('Clonar planejamento principal');
        } elseif (sizeof($configuraplanejamento['Planejamento']) > 0) {
            echo "Planejamento já existe" . "<br>";
            // die('Planejamento já existe');
            $this->Session->setFlash('Planejamento já existe!');
            $this->redirect('/planejamentos/listar/semestre:' . $configuraplanejamento['Configuraplanejamento']['id'] . '/semestre_data:' . $proximo_semestre . '/versao:' . $versao);
        } else {
            echo "2 Criar configuração para planejamento" . "<br>";
            $this->Session->setFlash('Criar configuração para planejamento');
            $this->redirect('/planejamentos/novoplano/semestre_id:' . $semestre_id . '/semestre_data:' . $semestre_data . '/versao:' . ($versao + 1));
        }

        // die();

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

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;

        // pr('1 Semestre id: ' . $semestre_id);
        // pr('1 Semestre data: ' . $semestre_data);
        // pr('1 Versão: ' . $versao);
        // die();

        $this->Configuraplanejamento->recursive = 0;
        $configuraplanejamento = $this->Configuraplanejamento->find('first', array(''
            . 'conditions' => array(
                'Configuraplanejamento.semestre' => $semestre_data),
            'fields' => array('MAX(Configuraplanejamento.versao) as maxversao'))
        );

        // pr($configuraplanejamento[0]['maxversao']);
        $novaversao = $configuraplanejamento[0]['maxversao'];
        // die();

        $this->set('semestre_id', $semestre_id);
        $this->set('semestre_data', $semestre_data);
        $this->set('versao', ($novaversao + 1));

        $this->set('configuracoes', $configuracoes = $this->Configuraplanejamento->find('all', array(
            'conditions' => array('semestre' => $semestre_data),
            'order' => array('semestre', 'versao'))));

        if ($this->data) {
            if ($this->Configuraplanejamento->save($this->data)) {
                $this->Session->setFlash('Dados inseridos');
                $this->redirect('/configuraplanejamentos/index');
            }
        }
    }

    public function delete($id = NULL) {

        $verifica = $this->Configuraplanejamento->Planejamento->find('first', array('conditions' => array('Planejamento.configuraplanejamento_id' => $id)));
        if ($verifica):
            $this->Session->setFlash('Registro não pode ser excluído porque está associado a uma planilha');
            $this->redirect('/planejamentos/listar/semestre:' . $id);
        else:
            $this->Configuraplanejamento->delete($id);
            $this->Session->setFlash("Registro excluído");
            $this->redirect('/configuraplanejamentos/index/');
        endif;
    }

}
