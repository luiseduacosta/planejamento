<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class PlanejamentosController extends AppController {

    public $name = "Planejamentos";
    public $paginate = array('limit' => 30,
        'order' => array('turno', 'periodo', 'dia_id', 'horario_id'));

    public function listar() {

        $parametros = $this->params['named'];
// pr($parametros);
        $semestre = isset($parametros['semestre']) ? $parametros['semestre'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;
        $turno = isset($parametros['turno']) ? $parametros['turno'] : NULL;
        $periodo = isset($parametros['periodo']) ? $parametros['periodo'] : NULL;
        $professor = isset($parametros['professor']) ? $parametros['professor'] : NULL;
        $disciplina = isset($parametros['disciplina']) ? $parametros['disciplina'] : NULL;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : NULL;

        // pr($semestre);
        // die();
        // Para fazer a caixa de seleção //
        $this->Planejamento->Configuraplanejamento->recursive = -1;
        $semestres = $this->Planejamento->Configuraplanejamento->find('all', array(
            'fields' => array('id', 'Configuraplanejamento.semestre'),
            'conditions' => array('Configuraplanejamento.versao' => 0)));
        // pr($semestres);
        foreach ($semestres as $c_semestre):
            // pr($c_semestre);
            $l_semestre[$c_semestre['Configuraplanejamento']['id']] = $c_semestre['Configuraplanejamento']['id'];
            $l_semestre[$c_semestre['Configuraplanejamento']['id']] = $c_semestre['Configuraplanejamento']['semestre'];            
        endforeach;
        // pr($l_semestre);
        // die();
        $this->set('listasemestres', $l_semestre);

        $origem = $this->referer('/', true);
// pr($origem);
// die();
        if ($origem === '/Planejamentos/index'):
            $this->Session->delete('turno');
            $this->Session->delete('periodo');
            $this->Session->delete('professor');
            $this->Session->delete('disciplina');
            $this->Session->delete("departamento");
        endif;

        $condicoes = NULL;

        // pr($semestre);
        // die();
        if ($semestre != 0):
            $this->Session->write("semestre", $semestre);
            $condicoes['Planejamento.configuraplanejamento_id'] = $semestre;
            $this->set('semestreatual', $semestre);
        else:
            $semestre = $this->Session->read("semestre");
            if ($semestre):
                $condicoes['Planejamento.configuraplanejamento_id'] = $semestre;
                $this->set('semestreatual', $semestre);
            else:
                $this->Session->setFlash('Selecione o semestre');
                $this->redirect('/configuraplanejamentos/index');
            endif;
        endif;

        $semestreporextenso = $this->semestreporextenso($semestre);
        $this->Session->write("semestreporextenso", $semestreporextenso);
        // pr($semestreporextenso);
        // die();

        if ($turno === NULL):
// echo 'Turno vazio ou zero ' . $turno . '<br>';
            $turno = $this->Session->read("turno");
            if ($turno) {
                $condicoes['Planejamento.turno'] = $turno;
            };
        else:
// echo 'Turno selecionado: ' . $turno . '<br>';
            if ($turno != '0') {
                $this->Session->write("turno", $turno);
                $condicoes['Planejamento.turno'] = $turno;
            } else {
                $this->Session->delete("turno");
            }
        endif;
// die();
        if ($periodo === NULL):
// echo 'Periodo vazio ou zero ' . $periodo . '<br>';
            $periodo = $this->Session->read("periodo");
            if ($periodo) {
                $condicoes['Planejamento.periodo'] = $periodo;
            };
        else:
// echo 'Periodo selecionado: ' . $periodo . '<br>';
            if ($periodo != '0') {
                $this->Session->write("periodo", $periodo);
                $condicoes['Planejamento.periodo'] = $periodo;
            } else {
                $this->Session->delete('periodo');
            }
        endif;

        if ($professor === NULL):
// echo "Professor vazio ou zero " . $professor . '<br>';
            $professor = $this->Session->read("professor");
            if ($professor) {
                $condicoes['Planejamento.docente_id'] = $professor;
            };
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
            };
        else:
// echo 'Professor selecionado: ' . $professor . '<br>';
            if ($disciplina != '0') {
                $this->Session->write("disciplina", $disciplina);
                $condicoes['Planejamento.disciplina_id'] = $disciplina;
            } else {
                $this->Session->delete("disciplina");
            }
        endif;

        if ($departamento === NULL):
// echo "Departamento vazia ou zero " . $departamento . '<br>';
            $departamento = $this->Session->read("departamento");
            if ($departamento) {
                $condicoes['Docente.departamento'] = $departamento;
            };
        else:
// echo 'Departamento selecionado: ' . $departamento . '<br>';
            if ($departamento != '0') {
                $this->Session->write("departamento", $departamento);
                $condicoes['Docente.departamento'] = $departamento;
            } else {
                $this->Session->delete("departamento");
            }
        endif;

        if ($condicoes) {
            // pr($condicoes);
        }
// pr($condicoes);
// die();
// echo 'Periodo = ' . $periodo . " " . ' turno = ' . $turno . " " . ' professor = ' . $professor . "<br>";

        $professores = $this->Planejamento->Docente->find('list', array('fields' => 'nome', 'order' => 'nome', 'conditions' => array('OR' => array('motivoegresso IS NULL', 'motivoegresso = ""'))));
        $disciplinas = $this->Planejamento->Disciplina->find('list', array('fields' => 'disciplina', 'order' => 'disciplina'));

        $this->Planejamento->recursive = 0;
        $planejamento = $this->Paginate($condicoes);

        // pr($planejamento);
        // die();


        $this->set('planejamento', $planejamento);

        $this->set('professores', $professores);
        $this->set('disciplinas', $disciplinas);
        $this->set('professor', $professor);
        $this->set('turno', $turno);
        $this->set('periodo', $periodo);
        $this->set('disciplina', $disciplina);
        $this->set('departamento', $departamento);
    }

    public function add() {

        if ($this->data) {
            /* Verificar antes de inserir */
            $planejamento = $this->Planejamento->find('first', array('conditions' => array(
                    'turno' => $this->data['Planejamento']['turno'],
                    'periodo' => $this->data['Planejamento']['periodo'],
                    'dia_id' => $this->data['Planejamento']['dia_id'],
                    'horario_id' => $this->data['Planejamento']['horario_id']
            )));
// $log = $this->Planejamento->getDataSource()->getLog(false, false);
// debug($log);
// pr($planejamento);
// die();

            if (empty($planejamento)):
                if ($this->Planejamento->save($this->data)) {
                    $this->Session->setFlash('Dados inseridos');
// $this->redirect('/Planejamentos/view/' . $this->Planejamento->getLastInsertId());
                    $this->redirect('/Planejamentos/listar/');
                };
            elseif ($this->Planejamento->save($this->data)):
                $this->Session->setFlash('Verifique se for OTP ou Núcleo temático! Dia e horário nesse periodo e turno ocupados');
                $this->redirect('/Planejamentos/listar/');
            endif;
        }

        $configuracao = $this->Planejamento->Configuraplanejamento->find('first', array(
            'order' => array('id' => 'DESC'))
        );
// pr($configuracao);
        $this->set('configuracao', $configuracao);
// die();
// $this->loadModel('Dia');
        $dias = $this->Planejamento->Dia->find('list', array('fields' => 'dia'));
        $this->set('dias', $dias);

// $this->loadModel('Horario');
        $horarios = $this->Planejamento->Horario->find('list', array('fields' => 'horario'));
        $this->set('horarios', $horarios);

// $this->loadModel('Disciplina');
        $disciplinas = $this->Planejamento->Disciplina->find('list', array('fields' => 'disciplina', 'order' => 'disciplina'));
        $this->set('disciplinas', $disciplinas);

// $this->loadModel('Docente');
        $professores = $this->Planejamento->Docente->find('list', array('fields' => 'nome', 'order' => 'nome', 'conditions' => array('OR' => array('motivoegresso' => "", 'motivoegresso IS NULL'))));
        $this->set('professores', $professores);

// $this->loadModel('Sala');
        $salas = $this->Planejamento->Sala->find('list', array('fields' => 'sala'));
        $this->set('salas', $salas);
    }

    public function view($id = NULL) {

        $planejamento = $this->Planejamento->find('first', array(
            'conditions' => array('Planejamento.id' => $id)
        ));
// pr($planejamento);
        $this->set('planejamento', $planejamento);
    }

    public function edit($id = NULL) {

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
            $planejamento = $this->Planejamento->find('first', array('conditions' => array(
                    'turno' => $this->data['Planejamento']['turno'],
                    'periodo' => $this->data['Planejamento']['periodo'],
                    'dia_id' => $this->data['Planejamento']['dia_id'],
                    'horario_id' => $this->data['Planejamento']['horario_id']
            )));
            if (empty($planejamento)):
                if ($this->Planejamento->save($this->data)) {
                    $this->Session->setFlash("Registro atualizado");
                    $this->redirect('/Planejamentos/view/' . $id);
                };
            else:
                if ($this->Planejamento->save($this->data)) {
                    $this->Session->setFlash('Atualizado no mesmo dia, horário, periodo e turno');
                    $this->redirect('/Planejamentos/view/' . $id);
                };
            endif;
        }
    }

    public function index() {

        // $parametros = $this->params['named'];
        // $semestre = isset($parametros['semestre']) ? $parametros['semestre'] : NULL;
        $semestre = $this->Session->read("semestre");
        if (empty($semestre or $semestre == NULL)):
            $this->Session->setFlash('Selecione um semestre');
            $this->redirect('/configuraplanejamentos/index');
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre);
        $this->Session->write("semestreporextenso", $semestreporextenso);
        // pr($semestreporextenso);
        // die();
// Diurno
        for ($periodo = 1; $periodo <= 8; $periodo++):
            for ($x = 1; $x <= 4; $x++): // horarios
                for ($i = 1; $i <= 5; $i++): // dias
// echo 'Periodo: ' . $periodo . ' Horario: ' . $x . ' Dia: ' . $i . '<br>';

                    $diurno = $this->Planejamento->find('all', array('conditions' =>
                        array(
                            'Planejamento.configuraplanejamento_id' => $semestre,
                            'Planejamento.turno' => "Diurno",
                            'Planejamento.periodo' => $periodo,
                            'Planejamento.horario_id' => $x,
                            'Planejamento.dia_id = ' . $i)));

// pr($diurno);

                    $t_diurno = NULL;
                    $otp_diurno = 0;
                    $nucleo_tematico = 0;
                    $superposicao = 0;
                    foreach ($diurno as $c_diurno) {
// pr($c_diurno);
// die();

                        if (empty($c_diurno)) {
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
                            $t_diurno['Planejamento'][$superposicao]['sala'] = 'Sala: ' . $c_diurno['Sala']['sala'];
                            $t_diurno['Planejamento'][$superposicao]['sala_id'] = $c_diurno['Planejamento']['sala_id'];
                            $t_diurno['Planejamento'][$superposicao]['disciplina'] = $c_diurno['Disciplina']['disciplina'];
                            $t_diurno['Planejamento'][$superposicao]['disciplina_id'] = $c_diurno['Planejamento']['disciplina_id'];
                            $t_diurno['Planejamento'][$superposicao]['docente'] = $c_diurno['Docente']['nome'];
                            $t_diurno['Planejamento'][$superposicao]['docente_id'] = $c_diurno['Planejamento']['docente_id'];
                        }
// $nucleo_tematico++;
// $otp_diurno++;
                        $superposicao++;
// echo $otp_diurno . '<br>';
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
// echo 'Periodo: ' . $periodo . ' Horario: ' . $x . ' Dia: ' . $i . '<br>';

                    $noturno = $this->Planejamento->find('all', array('conditions' =>
                        array(
                            'Planejamento.configuraplanejamento_id' => $semestre,
                            'Planejamento.turno' => "Noturno",
                            'Planejamento.periodo' => $periodo,
                            'Planejamento.horario_id' => $x,
                            'Planejamento.dia_id' => $i)));

                    $t_noturno = NULL;
                    $superposicao = 0;
                    foreach ($noturno as $c_noturno) {
                        if (empty($noturno)) {
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

    public function delete($id = NULL) {

        $this->Planejamento->delete($id);
        $this->Session->setFlash("Registro excluído");
// die("Planejamento excluído");
        $this->redirect('/Planejamentos/index/');
    }

    public function otp() {

        $parametros = $this->params['named'];
// pr($parametros);
        $turno = isset($parametros['turno']) ? $parametros['turno'] : NULL;
        $professor = isset($parametros['professor']) ? $parametros['professor'] : NULL;
        $disciplina = isset($parametros['disciplina']) ? $parametros['disciplina'] : NULL;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : NULL;

        $semestre = $this->Session->read("semestre");
        if ($semestre):
            $condicoes['Planejamento.configuraplanejamento_id'] = $semestre;
        else:
            $this->Session->setFlash('Selecione o semestre');
            $this->redirect('/configuraplanejamentos/index');
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre);
        $this->Session->write("semestreporextenso", $semestreporextenso);
        // pr($semestreporextenso);
        // die();

        $condicoes = NULL;

        if ($turno === NULL):
// echo 'Turno vazio ou zero ' . $turno . '<br>';
            $turno = $this->Session->read("turno");
            if ($turno) {
                $condicoes['turno'] = $turno;
            };
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
            };
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
                $condicoes['Disciplina.codigo'] = array('SSW401', 'SSW402', 'SSW403', 'SSW405');
            };
        else:
// echo 'Disciplina selecionada: ' . $disciplina . '<br>';
            if ($disciplina != '0') {
                $this->Session->write("disciplina", $disciplina);
                $condicoes['Planejamento.disciplina_id'] = $disciplina;
            } else {
                $this->Session->delete("disciplina");
                $condicoes['Disciplina.codigo'] = array('SSW401', 'SSW402', 'SSW403', 'SSW405');
            }
        endif;

        if ($departamento === NULL):
// echo "Departamento vazia ou zero " . $departamento . '<br>';
            $departamento = $this->Session->read("departamento");
            if ($departamento) {
                $condicoes['Docente.departamento'] = $departamento;
            };
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
// pr($otp);
// die();
        if ($semestre):
            $professores = $this->Planejamento->find('all', array(
                'fields' => 'DISTINCT Docente.nome, Docente.id',
                'order' => 'Docente.nome',
                'conditions' => array(
                    'Planejamento.configuraplanejamento_id' => $semestre,
                    'Planejamento.disciplina_id' => array("16", "17", "18", "19"))
                    )
            );
        else:
            $professores = $this->Planejamento->find('all', array(
                'fields' => 'DISTINCT Docente.nome, Docente.id',
                'order' => 'Docente.nome',
                'conditions' => array(
                    'Planejamento.disciplina_id' => array("16", "17", "18", "19"))
                    )
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
        $disciplinas = $this->Planejamento->Disciplina->find('list', array(
            'fields' => 'disciplina',
            'conditions' => array('Disciplina.codigo' => array("SSW401", "SSW402", "SSW403", "SSW405")),
            'order' => 'disciplina'));

        $this->set('professores', $l_professores);
        $this->set('disciplinas', $disciplinas);

        $this->set('professor', $professor);
        $this->set('turno', $turno);
        $this->set('disciplina', $disciplina);
        $this->set('departamento', $departamento);
    }

    public function nucleotematico() {

        $semestre = $this->Session->read("semestre");
        if ($semestre):
            $conditions = array(
                'Planejamento.configuraplanejamento_id' => $semestre,
                'Disciplina.codigo' => array("SSW409", "SSW410")
            );
        else:
            $this->Session->setFlash('Selecione o semestre');
            $this->redirect('/configuraplanejamentos/index');
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre);
        $this->Session->write("semestreporextenso", $semestreporextenso);
        // pr($semestreporextenso);
        // die();

        $this->set('nucleotematico', $this->Paginate($conditions));
    }

    public function optativa() {

        $semestre = $this->Session->read("semestre");

        if ($semestre):
            $conditions = array(
                'Planejamento.configuraplanejamento_id' => $semestre,
                'Disciplina.id' => array("54", "55")
            );
        else:
            $this->Session->setFlash('Selecione o semestre');
            $this->redirect('/configuraplanejamentos/index');
        endif;

        // Capturo o semestre por extenso //
        $semestreporextenso = $this->semestreporextenso($semestre);
        $this->Session->write("semestreporextenso", $semestreporextenso);
        // pr($semestreporextenso);
        // die();

        $this->set('optativas', $this->Paginate($conditions));
    }

    public function relaciona($id = NULL) {

        $this->Planejamento->id = $id;

        if (empty($this->data)) {
            $optativas = $this->Planejamento->Ementa->find('list', array('fields' => 'titulo'));
            $this->set('planejamento_id', $id);
            $this->set('optativas', $optativas);
            $this->data = $this->Planejamento->read();
        } else {
            if ($this->Planejamento->saveField("ementa_id", $this->data['Planejamento']['ementa_id'])) {
                $this->Session->setFlash("Atualizado");
                $this->redirect('/planejamentos/optativa/');
            }
        }
    }

    public function novoplano($id = NULL) {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;
        $opcao = isset($parametros['opcao']) ? $parametros['opcao'] : NULL;

        // pr('2 Versão: ' . $versao);
        // pr('2 Semestre id: ' . $semestre_id);
        // pr('2 Semestre data: ' . $semestre_data);
        // pr($opcao);
        // die();

        if ($versao == NULL) {
            $this->Session->setFlash('Definir versão');
            // die();
            $this->redirect('/configuraplanejamentos/add/semestre_id:' . $semestre_id . '/semestre_data:' . $semestre_data . '/versao:' . $versao);
        }

        if ($versao == 0):
            $anterior_semestre = $this->semestreanterior($semestre_data);
            // pr($anterior_semestre);
            // die('anterior semestre');

            $this->Planejamento->Configuraplanejamento->recursive = -1;
            $anterior_planejamento = $this->Planejamento->Configuraplanejamento->find('first', array(
                'field' => 'id',
                'conditions' => array('Configuraplanejamento.semestre' => $anterior_semestre))
            );
            // pr($anterior_planejamento);
            // die();

            $this->Planejamento->recursive = 0;
            $planejamento = $this->Planejamento->find('all', array(
                'conditions' => array(
                    array('Planejamento.configuraplanejamento_id' => $anterior_planejamento['Configuraplanejamento']['id']),
                    array('NOT' =>
                        array('Planejamento.disciplina_id' => array(42, 46, 54, 55))
                    )
                )
                    )
            );
        else:

            $this->Planejamento->Configuraplanejamento->recursive = -1;
            $anterior_planejamento = $this->Planejamento->Configuraplanejamento->find('first', array(
                'field' => 'id',
                'conditions' => array('Configuraplanejamento.semestre' => $semestre_data,
                    'Configuraplanejamento.versao' => 0))
            );
            // pr($anterior_planejamento);
            // die();

            $this->Planejamento->recursive = 0;
            $planejamento = $this->Planejamento->find('all', array(
                'conditions' => array(
                    array('Planejamento.configuraplanejamento_id' => $anterior_planejamento['Configuraplanejamento']['id']),
                    array('NOT' =>
                        array('Planejamento.disciplina_id' => array(42, 46, 54, 55))
                    )
                )
                    )
            );
        endif;

        // pr($planejamento);
        // die();

        if (!$planejamento):
            $this->Session->setFlash('Não há planejamento do semestre');
            $this->redirect('/Configuraplanejamentos/index');
        endif;
        // $log = $this->Planejamento->getDataSource()->getLog(false, false);
        // debug($log);
        // pr($planejamento);
        
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

            $valores[$i] = array('Planejamento' => array(
                    'configuraplanejamento_id' => $semestre_id,
                    'versao' => $versao,
                    'turno' => $turno,
                    'periodo' => $periodo,
                    'dia_id' => $dia_id,
                    'horario_id' => $horario_id,
                    'sala_id' => $sala_id,
                    'disciplina_id' => $disciplina_id,
                    'docente_id' => $docente_id,
                    'turno' => $turno)
            );

            $i++;

        endforeach;
        // pr($valores);
        // die();
        $this->Planejamento->saveMany($valores);

        $this->Session->setFlash('Dados inseridos para novo planejamento');
        $this->redirect('/configuraplanejamentos/index');
    }

    private function semestreporextenso($id = NULL) {

        $semestreporextenso = $this->Planejamento->Configuraplanejamento->find('first', array(
            'conditions' => array('id' => $id)));
        return $semestreporextenso['Configuraplanejamento']['semestre'];
    }

    public function excluir($id = NULL) {

        // pr($id);
        $planejamento = $this->Planejamento->find('all', array(
            'conditions' => array('Planejamento.configuraplanejamento_id' => $id),
            'fields' => array('Planejamento.id'))
        );

        foreach ($planejamento as $c_planejamento):
            // echo " " . $c_planejamento['Planejamento']['id'];
            $this->Planejamento->delete($c_planejamento['Planejamento']['id']);
            $this->Session->setFlash('Registro excluído: ' . $c_planejamento['Planejamento']['id']);
        endforeach;
        // die('Excluir todo o planejamento do semestre!!');

        $this->Session->setFlash("Registros excluídos");
// die("Planejamento excluído");
        $this->redirect('/Planejamentos/index/');
    }

    public function semestreanterior($id = NULL) {
        // Obtenho o proximosemestre por extenso a partir do id//
        $semestre_data = $id;
        // pr('semestreanterior ' . $semestre_data);
        // die();
        $divide_semestre = explode('-', $semestre_data);
        // pr($divide_semestre);
        // die();
        $ano_semestre = $divide_semestre[0];
        $digito_semestre = $divide_semestre[1];
        // echo $ano_semestre . " <-> " . $digito_semestre;
        // die();

        if ($digito_semestre == 1) {
            $anterior_ano = $ano_semestre - 1;
            $anterior_digito = 2;
            // echo $proximo_ano . " - " . $anterior_digito;
            $anterior_semestre = $anterior_ano . "-" . $anterior_digito;
        } elseif ($digito_semestre == 2) {
            $anterior_ano = $ano_semestre;
            $anterior_digito = 1;
            // echo $anterior_ano . " - " . $anterior_digito;
            $anterior_semestre = $anterior_ano . "-" . $anterior_digito;
        }
        // die($anterior_semestre);

        return $anterior_semestre;
    }

    public function semestreproximo($id = NULL) {
        // Obtenho o proximosemestre por extenso a partir do id//
        $semestre_data = $id;
        // pr($semestre_data);
        // die('semestreproximo');

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
        // die('semestreproximo');
        return $proximo_semestre;
    }

    public function clonar() {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;
        $opcao = isset($parametros['opcao']) ? $parametros['opcao'] : NULL;

        // pr('Semestre id: ' . $semestre_id);
        // pr('Semestre data: ' . $semestre_data);
        // pr('Versão: ' . $versao);
        // pr('Opcao: ' . $opcao);
        // die();

        $proximo_semestre = $this->semestreproximo($semestre_data);
        // die($proximo_semestre);
        $planejamento = $this->Planejamento->Configuraplanejamento->find('first', array(''
            . 'conditions' => array(
                'Configuraplanejamento.semestre' => $semestre_data,
                'Configuraplanejamento.versao' => $versao)));

        // pr($planejamento);
        if ($planejamento['Configuraplanejamento']):
            echo 'Configuração já realizada para o semestre';
            $this->Session->setFlash('Configuração já realizada para o semestre');
            if ($planejamento['Planejamento']) {
                echo "Já há um planejamento" . "<br>";
                $this->redirect('/planejamentos/listar/semestre:' . $semestre_id . '/versao:' . $versao);
            } else {
                echo "Sem planejamento" . "<br>";
                $this->redirect('novoplano');
            };
        else:
        // die("Configuração já feita");
        endif;
        // die();
        // die('Criar nova configuração para o próximo semestre');
        $this->Session->setFlash('Criar nova configuração para o semestre');
        $this->redirect('/configuraplanejamentos/add/semestre_id:' . $semestre_id . '/semestre_data:' . $proximo_semestre . "/versao:0");
        // die();
    }

    public function novaversao($id = NULL) {

        $parametros = $this->params['named'];
        $semestre_id = isset($parametros['semestre_id']) ? $parametros['semestre_id'] : NULL;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : NULL;
        $versao = isset($parametros['versao']) ? $parametros['versao'] : NULL;
        $opcao = isset($parametros['opcao']) ? $parametros['opcao'] : NULL;

        // pr('2 Versão: ' . $versao);
        // pr('2 Semestre id: ' . $semestre_id);
        // pr('2 Semestre data: ' . $semestre_data);
        // pr($opcao);
        // die();

        if ($versao == NULL) {
            $this->Session->setFlash('Definir versão');
            // die();
            $this->redirect('/configuraplanejamentos/add/semestre_id:' . $semestre_id . '/semestre_data:' . $semestre_data . '/versao:' . $versao);
        }

        $this->Planejamento->Configuraplanejamento->recursive = -1;
        $planejamentoconfigura = $this->Planejamento->Configuraplanejamento->find('all', array(
            'field' => 'id',
            'conditions' => array('Configuraplanejamento.id' => $semestre_id))
        );

        // $log = $this->Planejamento->Configuraplanejamento->getDataSource()->getLog(false, false);
        // debug($log);
        // pr($planejamentoconfigura);
        // die();

        // Para consultar o banco de datos //
        $planejamentoconfigura_id = $planejamentoconfigura['Configuraplanejamento']['id'];
        // die($planejamentoconfigura_id);
        // die();

        $this->Planejamento->recursive = 0;
        $planejamento = $this->Planejamento->find('all', array(
            'conditions' => array(
                array('Planejamento.configuraplanejamento_id' => $planejamentoconfigura_id),
                array('NOT' =>
                    array('Planejamento.disciplina_id' => array(42, 46, 54, 55))
                )
            )
                )
        );

        // pr($planejamento);
        // die();

        if (!$planejamento):
            $this->Session->setFlash('Não há planejamento do semestre anterior');
            $this->redirect('/Configuraplanejamentos/index');
        endif;
        // $log = $this->Planejamento->getDataSource()->getLog(false, false);
        // debug($log);
        // pr($planejamento);
        
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

            $valores[$i] = array('Planejamento' => array(
                    'configuraplanejamento_id' => $semestre_id,
                    'versao' => $versao,
                    'turno' => $turno,
                    'periodo' => $periodo,
                    'dia_id' => $dia_id,
                    'horario_id' => $horario_id,
                    'sala_id' => $sala_id,
                    'disciplina_id' => $disciplina_id,
                    'docente_id' => $docente_id,
                    'turno' => $turno)
            );

            $i++;

        endforeach;
        pr($valores);
        die();
        $this->Planejamento->saveMany($valores);

        $this->Session->setFlash('Dados inseridos para novo planejamento');
        $this->redirect('/planejamentos/listar/semestre:' . $semestre_id . '/semestre_data:' . $semestre_data . '/versao:' . $versao);
    }

}
