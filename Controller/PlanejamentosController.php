<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PlanejamentosController extends AppController
{

    public $name = "Planejamentos";
    public $paginate = [
        'limit' => 30,
        'order' => ['turno', 'periodo', 'dia_id', 'horario_id']
    ];

    public function listar()
    {
        $parametros = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : null;
        $turno = isset($parametros['turno']) ? $parametros['turno'] : null;
        $periodo = isset($parametros['periodo']) ? $parametros['periodo'] : null;
        $professor = isset($parametros['professor']) ? $parametros['professor'] : null;
        $disciplina = isset($parametros['disciplina']) ? $parametros['disciplina'] : null;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : null;

        $this->Planejamento->Configuraplanejamento->recursive = -1;
        $semestres = $this->Planejamento->Configuraplanejamento->find(
            'all',
            [
                'fields' => ['Configuraplanejamento.id', 'Configuraplanejamento.semestre'],
                'conditions' => ['Configuraplanejamento.versao' => 0]
            ]
        );
        $l_semestre = [];
        foreach ($semestres as $semestre) {
            $id = $semestre['Configuraplanejamento']['id'];
            $l_semestre[$id] = $semestre['Configuraplanejamento']['semestre'];
        }

        $this->set('listasemestres', $l_semestre);

        $origem = strtolower($this->referer('/', true));

        if ($origem === '/planejamentos/index') {
            $this->Session->delete('turno');
            $this->Session->delete('periodo');
            $this->Session->delete('professor');
            $this->Session->delete('disciplina');
            $this->Session->delete('departamento');
        }

        $condicoes = [];

        if ($semestre_id !== null && $semestre_id !== '' && $semestre_id !== '0') {
            $this->Session->write('semestre', $semestre_id);
            $condicoes['Planejamento.configuraplanejamento_id'] = $semestre_id;
            $this->set('semestreatual', $semestre_id);
        } else {
            $semestre_id = $this->Session->read('semestre');
            if ($semestre_id) {
                $condicoes['Planejamento.configuraplanejamento_id'] = $semestre_id;
                $this->set('semestreatual', $semestre_id);
            } else {
                $this->Flash->set(__('Selecione o semestre'));
                return $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
            }
        }

        $semestreporextenso = $this->semestreporextenso($semestre_id);
        $this->Session->write('semestreporextenso', $semestreporextenso);

        $filtros = [
            'turno' => [
                'value' => $turno,
                'field' => 'Planejamento.turno'
            ],
            'periodo' => [
                'value' => $periodo,
                'field' => 'Planejamento.periodo'
            ],
            'professor' => [
                'value' => $professor,
                'field' => 'Planejamento.docente_id'
            ],
            'disciplina' => [
                'value' => $disciplina,
                'field' => 'Planejamento.disciplina_id'
            ],
            'departamento' => [
                'value' => $departamento,
                'field' => 'Docente.departamento'
            ]
        ];

        foreach ($filtros as $sessionKey => $filtro) {
            $valor = $filtro['value'];

            if ($valor === null) {
                $valor = $this->Session->read($sessionKey);
            } elseif ($valor === '0' || $valor === '') {
                $this->Session->delete($sessionKey);
                $filtros[$sessionKey]['value'] = $valor;
                continue;
            } else {
                $this->Session->write($sessionKey, $valor);
            }

            if ($valor !== null && $valor !== '' && $valor !== '0') {
                $condicoes[$filtro['field']] = $valor;
            }

            $filtros[$sessionKey]['value'] = $valor;
        }

        $turno = $filtros['turno']['value'];
        $periodo = $filtros['periodo']['value'];
        $professor = $filtros['professor']['value'];
        $disciplina = $filtros['disciplina']['value'];
        $departamento = $filtros['departamento']['value'];

        $professores = $this->Planejamento->Docente->find('list', array('fields' => 'nome', 'order' => 'nome', 'conditions' => array('OR' => array('motivoegresso IS NULL', 'motivoegresso = ""'))));
        $disciplinas = $this->Planejamento->Disciplina->find('list', array('fields' => 'disciplina', 'order' => 'disciplina'));

        $this->Planejamento->recursive = 0;
        $planejamento = $this->paginate('Planejamento', $condicoes);

        $this->set('planejamento', $planejamento);

        $this->set('professores', $professores);
        $this->set('disciplinas', $disciplinas);
        $this->set('professor', $professor);
        $this->set('turno', $turno);
        $this->set('periodo', $periodo);
        $this->set('disciplina', $disciplina);
        $this->set('departamento', $departamento);
    }

    public function add()
    {

        if ($this->data) {
            /* Verificar antes de inserir */
            $planejamento = $this->Planejamento->find('first', array(
                'conditions' => array(
                    'turno' => $this->data['Planejamento']['turno'],
                    'periodo' => $this->data['Planejamento']['periodo'],
                    'dia_id' => $this->data['Planejamento']['dia_id'],
                    'horario_id' => $this->data['Planejamento']['horario_id']
                )
            ));

            if (empty($planejamento)):
                if ($this->Planejamento->save($this->data)) {
                    $this->Session->setFlash('Dados inseridos');
                    // $this->redirect('/Planejamentos/view/' . $this->Planejamento->getLastInsertId());
                    $this->redirect('/Planejamentos/listar/');
                }
                ;
            elseif ($this->Planejamento->save($this->data)):
                $this->Session->setFlash('Verifique se for OTP ou Núcleo temático! Dia e horário nesse periodo e turno ocupados');
                $this->redirect('/Planejamentos/listar/');
            endif;
        }

        $configuracao = $this->Planejamento->Configuraplanejamento->find(
            'first',
            [
                'order' => ['id' => 'DESC']
            ]
        );
        $this->set('configuracao', $configuracao);
        $dias = $this->Planejamento->Dia->find('list', ['fields' => 'dia']);
        $this->set('dias', $dias);

        // $this->loadModel('Horario');
        $horarios = $this->Planejamento->Horario->find('list', ['fields' => 'horario']);
        $this->set('horarios', $horarios);

        // $this->loadModel('Disciplina');
        $disciplinas = $this->Planejamento->Disciplina->find('list', ['fields' => 'disciplina', 'order' => 'disciplina']);
        $this->set('disciplinas', $disciplinas);

        // $this->loadModel('Docente');
        $professores = $this->Planejamento->Docente->find('list', ['fields' => 'nome', 'order' => 'nome', 'conditions' => ['OR' => ['motivoegresso' => "", 'motivoegresso IS NULL']]]);
        $this->set('professores', $professores);

        // $this->loadModel('Sala');
        $salas = $this->Planejamento->Sala->find('list', ['fields' => 'sala']);
        $this->set('salas', $salas);
    }

    public function view($id = NULL)
    {

        $planejamento = $this->Planejamento->find('first', [
            'conditions' => ['Planejamento.id' => $id]
        ]);
        // pr($planejamento);
        $this->set('planejamento', $planejamento);
    }

    public function edit($id = NULL)
    {

        $this->Planejamento->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Planejamento->read();

            $this->set('dias', $this->Planejamento->Dia->find('list', array('fields' => 'dia', 'order' => 'ordem')));
            $this->set('horarios', $this->Planejamento->Horario->find('list', array('fields' => 'horario', 'order' => 'ordem')));
            $this->set('disciplinas', $this->Planejamento->Disciplina->find('list', array('fields' => 'disciplina', 'order' => 'disciplina')));
            $this->set('docentes', $this->Planejamento->Docente->find('list', array('fields' => 'nome', 'order' => 'nome', 'conditions' => array('OR' => array('motivoegresso' => "", 'motivoegresso IS NULL')))));
            $this->set('salas', $this->Planejamento->Sala->find('list', array('fields' => 'sala', 'order' => 'sala')));
            $this->set('ementas', $this->Planejamento->Ementa->find('list', array('fields' => 'titulo', 'order' => 'titulo')));
            $this->set('optativas', $this->Planejamento->Optativa->find('list', array('fields' => 'Optativa.disciplina', 'order' => 'Optativa.disciplina')));
        } else {

            /* Verifica se há um registro nesse dia e horário nesse turno e periodo */
            $planejamento = $this->Planejamento->find('first', [
                'conditions' => [
                    'turno' => $this->data['Planejamento']['turno'],
                    'periodo' => $this->data['Planejamento']['periodo'],
                    'dia_id' => $this->data['Planejamento']['dia_id'],
                    'horario_id' => $this->data['Planejamento']['horario_id']
                ]
            ]);
            if (empty($planejamento)):
                if ($this->Planejamento->save($this->data)) {
                    $this->Flash->success("Registro atualizado");
                    $this->redirect(['controller' => 'Planejamentos', 'action' => 'view', $id]);
                }
                ;
            else:
                if ($this->Planejamento->save($this->data)) {
                    $this->Flash->success('Atualizado no mesmo dia, horário, periodo e turno');
                    $this->redirect(['controller' => 'Planejamentos', 'action' => 'view', $id]);
                }
                ;
            endif;
        }
    }

    public function index()
    {
        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre']) ? $parametros['semestre'] : NULL;

        if (empty($semestre_id) or $semestre_id == NULL):
            $semestre_id = $this->Session->read("semestre");
            if (!($semestre_id)):
                $this->Session->delete("semestre");
                $this->Flash->error('Selecione um semestre para ver o planejamento');
                $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            endif;
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre_id);
        $this->Session->write("semestreporextenso", $semestreporextenso);
     
        for ($periodo = 1; $periodo <= 8; $periodo++):
            for ($x = 1; $x <= 4; $x++): // horarios
                for ($i = 1; $i <= 5; $i++): // dias

                    $diurno = $this->Planejamento->find('all', [
                        'conditions' =>
                            [
                                'Planejamento.configuraplanejamento_id' => $semestre_id,
                                'Planejamento.turno' => "Diurno",
                                'Planejamento.periodo' => $periodo,
                                'Planejamento.horario_id' => $x,
                                'Planejamento.dia_id = ' . $i
                            ]
                    ]);

                    // pr($diurno);

                    $t_diurno = NULL;
                    $otp_diurno = 0;
                    $nucleo_tematico = 0;
                    $superposicao = 0;
                    foreach ($diurno as $c_diurno) {

                        if (empty($c_diurno)) {
                            $t_diurno['Planejamento']['configuraplanejamento_id'] = NULL;
                            $t_diurno['Planejamento']['turno'] = 'Diurno';
                            $t_diurno['Planejamento']['periodo'] = $periodo;
                            $t_diurno['Planejamento']['dia_id'] = $i;
                            $t_diurno['Planejamento']['horario_id'] = $x;
                            $t_diurno['Planejamento']['sala'] = "";
                            $t_diurno['Planejamento']['disciplina'] = "";
                            $t_diurno['Planejamento']['docente'] = "";
                        } else {
                            $t_diurno['Planejamento']['turno'] = 'Diurno';
                            $t_diurno['Planejamento']['periodo'] = $periodo;
                            $t_diurno['Planejamento']['dia_id'] = $i;
                            $t_diurno['Planejamento']['horario_id'] = 1;
                            $t_diurno['Planejamento'][$superposicao]['configuraplanejamento_id'] = $c_diurno['Planejamento']['configuraplanejamento_id'];
                            $t_diurno['Planejamento'][$superposicao]['sala'] = 'Sala: ' . $c_diurno['Sala']['sala'];
                            $t_diurno['Planejamento'][$superposicao]['sala_id'] = $c_diurno['Planejamento']['sala_id'];
                            $t_diurno['Planejamento'][$superposicao]['disciplina'] = $c_diurno['Disciplina']['disciplina'];
                            $t_diurno['Planejamento'][$superposicao]['disciplina_id'] = $c_diurno['Planejamento']['disciplina_id'];
                            $t_diurno['Planejamento'][$superposicao]['docente'] = $c_diurno['Docente']['nome'];
                            $t_diurno['Planejamento'][$superposicao]['docente_id'] = $c_diurno['Planejamento']['docente_id'];
                        }

                        $superposicao++;
                    }
                    // die();
                    $diurno_horarios[$i] = $t_diurno;
                endfor;
                // pr($diurno_horarios);
                $diurno_dias[$x] = $diurno_horarios;

            endfor;
            // pr($diurno_dias);
            $diurno_periodos[$periodo] = $diurno_dias;

        endfor;
        // pr($diurno_periodos);
        $this->loadModel('Horario');
        $this->set('horarios', $this->Horario->find('all'));
        $this->set('diurno', $diurno_periodos);

        // Noturno
        for ($periodo = 1; $periodo <= 10; $periodo++): // Sao 10 periodos no noturno
            for ($x = 5; $x <= 6; $x++): // horarios 5 e 6
                for ($i = 1; $i <= 5; $i++): // dias

                    $noturno = $this->Planejamento->find('all', array(
                        'conditions' =>
                            array(
                                'Planejamento.configuraplanejamento_id' => $semestre_id,
                                'Planejamento.turno' => "Noturno",
                                'Planejamento.periodo' => $periodo,
                                'Planejamento.horario_id' => $x,
                                'Planejamento.dia_id' => $i
                            )
                    ));

                    $t_noturno = NULL;
                    $superposicao = 0;
                    foreach ($noturno as $c_noturno) {
                        // pr($c_noturno);
                        if (empty($noturno)) {
                            $t_noturno['Planejamento']['configuraplanejamento_id'] = NULL;
                            $t_noturno['Planejamento']['turno'] = 'Noturno';
                            $t_noturno['Planejamento']['periodo'] = $periodo;
                            $t_noturno['Planejamento']['dia_id'] = $i;
                            $t_noturno['Planejamento']['horario_id'] = $x;
                            $t_noturno['Planejamento']['sala'] = "";
                            $t_noturno['Planejamento']['disciplina'] = "";
                            $t_noturno['Planejamento']['docente'] = "";
                        } else {
                            $t_noturno['Planejamento']['turno'] = 'Noturno';
                            $t_noturno['Planejamento']['periodo'] = $periodo;
                            $t_noturno['Planejamento']['dia_id'] = $i;
                            $t_noturno['Planejamento']['horario_id'] = $x;
                            $t_noturno['Planejamento'][$superposicao]['configuraplanejamento_id'] = $c_noturno['Planejamento']['configuraplanejamento_id'];
                            $t_noturno['Planejamento'][$superposicao]['sala'] = 'Sala: ' . $c_noturno['Sala']['sala'];
                            $t_noturno['Planejamento'][$superposicao]['sala_id'] = $c_noturno['Planejamento']['sala_id'];
                            $t_noturno['Planejamento'][$superposicao]['disciplina'] = $c_noturno['Disciplina']['disciplina'];
                            $t_noturno['Planejamento'][$superposicao]['disciplina_id'] = $c_noturno['Planejamento']['disciplina_id'];
                            $t_noturno['Planejamento'][$superposicao]['docente'] = $c_noturno['Docente']['nome'];
                            $t_noturno['Planejamento'][$superposicao]['docente_id'] = $c_noturno['Planejamento']['docente_id'];
                        }
                        $superposicao++;
                        // echo $otp_noturno . "<br>";
                    }
                    $noturno_horarios[$i] = $t_noturno;
                endfor;
                // pr($noturno_horarios);
                $noturno_dias[$x] = $noturno_horarios;

            endfor;
            // pr($noturno_dias);
            $noturno_periodos[$periodo] = $noturno_dias;

        endfor;

        $this->loadModel('Horario');
        $this->set('horarios', $this->Horario->find('all'));
        $this->set('diurno', $diurno_periodos);
        $this->set('noturno', $noturno_periodos);
    }

    public function delete($id = NULL)
    {

        $this->Planejamento->delete($id);
        $this->Session->setFlash("Registro excluído");
        // die("Planejamento excluído");
        $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
    }

    public function otp()
    {

        $parametros = $this->params['named'];
        // pr($parametros);
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $turno = isset($parametros['turno']) ? $parametros['turno'] : NULL;
        $professor = isset($parametros['professor']) ? $parametros['professor'] : NULL;
        $disciplina = isset($parametros['disciplina']) ? $parametros['disciplina'] : NULL;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : NULL;

        $condicoes = NULL;

        $semestre_id = $this->Session->read("semestre");
        if ($semestre_id):
            $condicoes['Planejamento.configuraplanejamento_id'] = $semestre_id;
        else:
            $this->Flash->error('Selecione o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre_id);
        $this->Session->write("semestreporextenso", $semestreporextenso);
        // pr($semestreporextenso);
        // die();

        if ($turno === NULL):
            // echo 'Turno vazio ou zero ' . $turno . '<br>';
            $turno = $this->Session->read("turno");
            if ($turno) {
                $condicoes['turno'] = $turno;
            }
            ;
        else:
            // echo 'Turno selecionado: ' . $turno . '<br>';
            if ($turno != '0') {
                $this->Session->write("turno", $turno);
                $condicoes['turno'] = $turno;
            } else {
                $this->Session->delete("turno");
            }
        endif;

        if ($professor === NULL):
            // echo "Professor vazio ou zero " . $professor . '<br>';
            $professor = $this->Session->read("professor");
            if ($professor) {
                $condicoes['Planejamento.docente_id'] = $professor;
            }
            ;
        else:
            // echo 'Professor selecionado: ' . $professor . '<br>';
            if ($professor != '0') {
                $this->Session->write("professor", $professor);
                $condicoes['Planejamento.docente_id'] = $professor;
            } else {
                $this->Session->delete("professor");
            }
        endif;

        if ($disciplina === NULL):
            // echo "Disciplina vazia ou zero " . $disciplina . '<br>';
            $disciplina = $this->Session->read("disciplina");
            if ($disciplina) {
                $condicoes['Planejamento.disciplina_id'] = $disciplina;
            } else {
                $condicoes['Disciplina.codigo'] = ['SSW401', 'SSW402', 'SSW403', 'SSW405'];
            }
            ;
        else:
            // echo 'Disciplina selecionada: ' . $disciplina . '<br>';
            if ($disciplina != '0') {
                $this->Session->write("disciplina", $disciplina);
                $condicoes['Planejamento.disciplina_id'] = $disciplina;
            } else {
                $this->Session->delete("disciplina");
                $condicoes['Disciplina.codigo'] = ['SSW401', 'SSW402', 'SSW403', 'SSW405'];
            }
        endif;

        if ($departamento === NULL):
            // echo "Departamento vazia ou zero " . $departamento . '<br>';
            $departamento = $this->Session->read("departamento");
            if ($departamento) {
                $condicoes['Docente.departamento'] = $departamento;
            }
            ;
        else:
            // echo 'Departamento selecionado: ' . $departamento . '<br>';
            if ($departamento != '0') {
                $this->Session->write("departamento", $departamento);
                $condicoes['Docente.departamento'] = $departamento;
            } else {
                $this->Session->delete("departamento");
            }
        endif;

        // pr($condicoes);

        $this->set('otp', $this->Paginate($condicoes));

        if ($semestre_id):
            $professores = $this->Planejamento->find(
                'all',
                [
                    'fields' => 'DISTINCT Docente.nome, Docente.id',
                    'order' => 'Docente.nome',
                    'conditions' => [
                        'Planejamento.configuraplanejamento_id' => $semestre_id,
                        'Planejamento.disciplina_id' => ["16", "17", "18", "19"]
                    ]
                ]
            );
        else:
            $professores = $this->Planejamento->find(
                'all',
                [
                    'fields' => 'DISTINCT Docente.nome, Docente.id',
                    'order' => 'Docente.nome',
                    'conditions' => [
                        'Planejamento.disciplina_id' => ["16", "17", "18", "19"]
                    ]
                ]
            );
        endif;
        /* 'conditions' => 'motivoegresso IS NULL')); */
        $i = 0;
        $l_professores = NULL;
        foreach ($professores as $c_professores):
            $l_professores[$c_professores['Docente']['id']] = $c_professores['Docente']['id'];
            $l_professores[$c_professores['Docente']['id']] = $c_professores['Docente']['nome'];
            $i++;
        endforeach;
        // pr($l_professores);
        $disciplinas = $this->Planejamento->Disciplina->find('list', [
            'fields' => 'disciplina',
            'conditions' => ['Disciplina.codigo' => ["SSW401", "SSW402", "SSW403", "SSW405"]],
            'order' => 'disciplina'
        ]);

        $this->set('professores', $l_professores);
        $this->set('disciplinas', $disciplinas);

        $this->set('professor', $professor);
        $this->set('turno', $turno);
        $this->set('disciplina', $disciplina);
        $this->set('departamento', $departamento);
    }

    public function nucleotematico()
    {

        $semestre_id = $this->Session->read("semestre");
        if ($semestre_id):
            $conditions = [
                'Planejamento.configuraplanejamento_id' => $semestre_id,
                'Disciplina.codigo' => ["SSW409", "SSW410"]
            ];
        else:
            $this->Flash->error('Selecione o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre_id);
        $this->Session->write("semestreporextenso", $semestreporextenso);

        $this->set('nucleotematico', $this->Paginate($conditions?:[]()));
    }

    public function optativa()
    {

        $semestre_id = $this->Session->read("semestre");

        $conditions = NULL;
        if ($semestre_id):
            $conditions = [
                'Planejamento.configuraplanejamento_id' => $semestre_id,
                'Disciplina.id' => ["54", "55"]
            ];
        else:
            $this->Flash->error('Selecione o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre_id);
        $this->Session->write("semestreporextenso", $semestreporextenso);

        $this->set('optativas', $this->Paginate($conditions?:[]()));
    }

    public function relaciona($id = NULL)
    {

        $this->Planejamento->id = $id;

        if (empty($this->data)) {
            $optativas = $this->Planejamento->Ementa->find('list', ['fields' => 'titulo']);
            $this->set('planejamento_id', $id);
            $this->set('optativas', $optativas);
            $this->data = $this->Planejamento->read();
        } else {
            if ($this->Planejamento->saveField("ementa_id", $this->data['Planejamento']['ementa_id'])) {
                $this->Flash->success("Atualizado");
                $this->redirect(['controller' => 'planejamentos', 'action' => 'optativa']);
            }
        }
    }

    public function novoplano($id = NULL)
    {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;
        $opcao = isset($parametros['opcao']) ? $parametros['opcao'] : NULL;

        if ($versao == NULL) {
            $this->Flash->error('Definir versão');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'add', '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $semestre_data, 'versao' => $versao]]);
        }

        if ($versao == 0):
            $anterior_semestre = $this->semestreanterior($semestre_data);

            $this->Planejamento->Configuraplanejamento->recursive = -1;
            $anterior_planejamento = $this->Planejamento->Configuraplanejamento->find(
                'first',
                [
                    'field' => 'id',
                    'conditions' => ['Configuraplanejamento.semestre' => $anterior_semestre]
                ]
            );

            $this->Planejamento->recursive = 0;
            $planejamento = $this->Planejamento->find(
                'all',
                [
                    'conditions' => [
                        ['Planejamento.configuraplanejamento_id' => $anterior_planejamento['Configuraplanejamento']['id']],
                        [
                            'NOT' =>
                                ['Planejamento.disciplina_id' => [42, 46, 54, 55]]
                        ]
                    ]
                ]
            );
        else:

            $this->Planejamento->Configuraplanejamento->recursive = -1;
            $anterior_planejamento = $this->Planejamento->Configuraplanejamento->find(
                'first',
                [
                    'field' => 'id',
                    'conditions' => [
                        'Configuraplanejamento.semestre' => $semestre_data,
                        'Configuraplanejamento.versao' => 0
                    ]
                ]
            );

            $this->Planejamento->recursive = 0;
            $planejamento = $this->Planejamento->find(
                'all',
                [
                    'conditions' => [
                        ['Planejamento.configuraplanejamento_id' => $anterior_planejamento['Configuraplanejamento']['id']],
                        [
                            'NOT' =>
                                ['Planejamento.disciplina_id' => [42, 46, 54, 55]]
                        ]
                    ]
                ]
            );
        endif;

        if (!$planejamento):
            $this->Flash->error('Não há planejamento do semestre');
            $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
        endif;

        $valores = array();
        $i = 1;
        foreach ($planejamento as $c_planejamento):

            if ($c_planejamento['Planejamento']['turno']) {
                $turno = $c_planejamento['Planejamento']['turno'];
            } else {
                $turno = "";
            }

            if ($c_planejamento['Planejamento']['periodo']) {
                $periodo = $c_planejamento['Planejamento']['periodo'];
            } else {
                $periodo = "";
            }

            if ($c_planejamento['Planejamento']['dia_id']) {
                $dia_id = $c_planejamento['Planejamento']['dia_id'];
            } else {
                $dia_id = "";
            }

            if ($c_planejamento['Planejamento']['horario_id']) {
                $horario_id = $c_planejamento['Planejamento']['horario_id'];
            } else {
                $horario_id = "";
            }

            if ($c_planejamento['Planejamento']['sala_id']) {
                $sala_id = $c_planejamento['Planejamento']['sala_id'];
            } else {
                $sala_id = 0;
            }

            if ($c_planejamento['Planejamento']['disciplina_id']) {
                $disciplina_id = $c_planejamento['Planejamento']['disciplina_id'];
            } else {
                $disciplina_id = "";
            }

            if ($c_planejamento['Planejamento']['docente_id']) {
                $docente_id = $c_planejamento['Planejamento']['docente_id'];
            } else {
                $docente_id = 0;
            }

            $valores[$i] = [
                'Planejamento' => [
                    'configuraplanejamento_id' => $semestre_id,
                    'versao' => $versao,
                    'turno' => $turno,
                    'periodo' => $periodo,
                    'dia_id' => $dia_id,
                    'horario_id' => $horario_id,
                    'sala_id' => $sala_id,
                    'disciplina_id' => $disciplina_id,
                    'docente_id' => $docente_id
                    ]
                ];

            $i++;

        endforeach;

        $this->Planejamento->saveMany($valores);

        $this->Flash->success(__('Dados inseridos para novo planejamento'));
        $this->redirect('/configuraplanejamentos/index');
    }

    private function semestreporextenso($id = NULL)
    {
        $this->Planejamento->Configuraplanejamento->recursive = 0;
        $semestreporextenso = $this->Planejamento->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $id]
        ]);
        return $semestreporextenso['Configuraplanejamento']['semestre'] . ' v. ' . $semestreporextenso['Configuraplanejamento']['versao'];
    }

    public function excluir($id = NULL)
    {

        $planejamento = $this->Planejamento->find(
            'all',
            [
                'conditions' => ['Planejamento.configuraplanejamento_id' => $id],
                'fields' => ['Planejamento.id']
            ]
        );

        foreach ($planejamento as $c_planejamento):
            // echo " " . $c_planejamento['Planejamento']['id'];
            $this->Planejamento->delete($c_planejamento['Planejamento']['id']);
            $this->Flash->success(__('Registro excluído: ' . $c_planejamento['Planejamento']['id']));
        endforeach;
 
        $this->Flash->success(__('Registros excluídos'));
        $this->redirect(['controller' => 'Planejamentos', 'action' => 'index']);
    }

    public function semestreanterior($id = NULL)
    {
        // Obtenho o proximosemestre por extenso a partir do id//
        $semestre_data = $id;
        $divide_semestre = explode('-', $semestre_data);
        $ano_semestre = $divide_semestre[0];
        $digito_semestre = $divide_semestre[1];

        if ($digito_semestre == 1) {
            $anterior_ano = $ano_semestre - 1;
            $anterior_digito = 2;

            $anterior_semestre = $anterior_ano . "-" . $anterior_digito;
        } elseif ($digito_semestre == 2) {
            $anterior_ano = $ano_semestre;
            $anterior_digito = 1;

            $anterior_semestre = $anterior_ano . "-" . $anterior_digito;
        }

        return $anterior_semestre;
    }

    public function semestreproximo($id = NULL)
    {
        // Obtenho o proximosemestre por extenso a partir do id//
        $semestre_data = $id;

        $divide_semestre = explode('-', $semestre_data);
        $ano_semestre = $divide_semestre[0];
        $digito_semestre = $divide_semestre[1];
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
        return $proximo_semestre;
    }

    public function clonar()
    {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;

        $usuarioplanejamento = $this->Session->read('usuarioplanejamento');
        if (isset($usuarioplanejamento['role']) || ($usuarioplanejamento['role']) != 'admin'):
            $this->Flash->error('Usuário não autorizado a clonar. Pode criar uma versão');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        endif;

        $proximo_semestre = $this->semestreproximo($semestre_data);

        // Localizo se já há uma configuração e um planejamento
        $planejamento = $this->Planejamento->Configuraplanejamento->find(
            'first',
            [
                'conditions' => [
                    'Configuraplanejamento.semestre' => $proximo_semestre,
                    'Configuraplanejamento.versao' => $versao
                ]
            ]
        );

        if ($planejamento['Configuraplanejamento']):
            $this->Flash->error('Configuração já realizada para o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            if ($planejamento['Planejamento']) {
                echo "Já há um planejamento" . "<br>";
                $this->Flash->error('Já há um planejamento para o semestre');
                $this->redirect(['controller' => 'planejamentos', 'action' => 'listar', '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $proximo_semestre, 'versao' => $versao]]);
            } else {
                echo "Configurado, sem planejamento" . "<br>";
                $this->Flash->error('Configurado porém ainda sem planejamento');
                $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            }
        else:
            // die('Criar nova configuração para o próximo semestre');
            $this->Flash->error('Criar nova configuração para o semestre');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'add', '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $proximo_semestre, 'versao' => $versao]]);
        endif;
    }

    public function novaversao($id = NULL)
    {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;

        if ($versao == NULL) {
            $this->Flash->error('Definir versão');
            $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'add', '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $semestre_data, 'versao' => $versao]]);
        }

        $this->Planejamento->Configuraplanejamento->recursive = -1;
        $planejamentoconfigura = $this->Planejamento->Configuraplanejamento->find(
            'all',
            [
                'field' => 'id',
                'conditions' => array('Configuraplanejamento.id' => $semestre_id)
            ]
        );

        // Para consultar o banco de datos //
        $planejamentoconfigura_id = $planejamentoconfigura['Configuraplanejamento']['id'];

        $this->Planejamento->recursive = 0;
        $planejamento = $this->Planejamento->find(
            'all',
            [
                'conditions' => [
                    ['Planejamento.configuraplanejamento_id' => $planejamentoconfigura_id],
                    [
                        'NOT' => ['Planejamento.disciplina_id' => [42, 46, 54, 55]]
                    ]
                ]
            ]
        );

        if (!$planejamento):
            $this->Flash->error('Não há planejamento do semestre anterior');
            $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
        endif;

        $i = 1;
        foreach ($planejamento as $c_planejamento):

            if ($c_planejamento['Planejamento']['turno']) {
                $turno = $c_planejamento['Planejamento']['turno'];
            } else {
                $turno = "";
            }

            if ($c_planejamento['Planejamento']['periodo']) {
                $periodo = $c_planejamento['Planejamento']['periodo'];
            } else {
                $periodo = "";
            }

            if ($c_planejamento['Planejamento']['dia_id']) {
                $dia_id = $c_planejamento['Planejamento']['dia_id'];
            } else {
                $dia_id = "";
            }

            if ($c_planejamento['Planejamento']['horario_id']) {
                $horario_id = $c_planejamento['Planejamento']['horario_id'];
            } else {
                $horario_id = "";
            }

            if ($c_planejamento['Planejamento']['sala_id']) {
                $sala_id = $c_planejamento['Planejamento']['sala_id'];
            } else {
                $sala_id = 0;
            }

            if ($c_planejamento['Planejamento']['disciplina_id']) {
                $disciplina_id = $c_planejamento['Planejamento']['disciplina_id'];
            } else {
                $disciplina_id = "";
            }

            if ($c_planejamento['Planejamento']['docente_id']) {
                $docente_id = $c_planejamento['Planejamento']['docente_id'];
            } else {
                $docente_id = 0;
            }

            $valores[$i] = [
                'Planejamento' => [
                    'configuraplanejamento_id' => $semestre_id,
                    'versao' => $versao,
                    'turno' => $turno,
                    'periodo' => $periodo,
                    'dia_id' => $dia_id,
                    'horario_id' => $horario_id,
                    'sala_id' => $sala_id,
                    'disciplina_id' => $disciplina_id,
                    'docente_id' => $docente_id
                    ]
                ];

            $i++;

        endforeach;
        $this->Planejamento->saveMany($valores);

        $this->Flash->success('Dados inseridos para novo planejamento');
        $this->redirect(['controller'=> 'planejamentos', 'action' => 'listar', '?' => ['sesemestre_id' => $semestre_id, 'semestre_data' => $semestre_data, 'versao' => $versao]]);
    }

}
