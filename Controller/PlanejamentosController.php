<?php
App::uses('AppController', 'Controller');

class PlanejamentosController extends AppController
{
    public $name = "Planejamentos";

    public $paginate = [
        'limit' => 30,
        'order' => ['turno', 'periodo', 'dia_id', 'horario_id']
    ];

    // -------------------------------------------------------------------------
    // Constants — keep magic values in one place
    // -------------------------------------------------------------------------

    /** Discipline IDs excluded when cloning/creating new plan versions */
    const DISCIPLINAS_EXCLUIDAS_CLONE = [42, 46, 54, 55];

    /** IDs of OTP disciplines */
    const DISCIPLINAS_OTP_IDS = [16, 17, 18, 19];

    /** Course codes for OTP */
    const DISCIPLINAS_OTP_CODIGOS = ['SSW401', 'SSW402', 'SSW403', 'SSW405'];

    /** Course codes for Núcleo Temático */
    const DISCIPLINAS_NUCLEO_CODIGOS = ['SSW409', 'SSW410'];

    /** Discipline IDs for Optativas */
    const DISCIPLINAS_OPTATIVAS_IDS = [54, 55];

    // -------------------------------------------------------------------------
    // Public actions
    // -------------------------------------------------------------------------

    public function listar()
    {
        $parametros    = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id   = isset($parametros['semestre_id'])  ? $parametros['semestre_id']  : null;
        $turno         = isset($parametros['turno'])        ? $parametros['turno']        : null;
        $periodo       = isset($parametros['periodo'])      ? $parametros['periodo']      : null;
        $professor     = isset($parametros['professor'])    ? $parametros['professor']    : null;
        $disciplina    = isset($parametros['disciplina'])   ? $parametros['disciplina']   : null;
        $departamento  = isset($parametros['departamento']) ? $parametros['departamento'] : null;

        // Build semester dropdown (only base versions, versao = 0)
        $this->Planejamento->Configuraplanejamento->recursive = -1;
        $semestres = $this->Planejamento->Configuraplanejamento->find('all', [
            'fields'     => ['Configuraplanejamento.id', 'Configuraplanejamento.semestre'],
            'conditions' => ['Configuraplanejamento.versao' => 0]
        ]);

        $l_semestre = [];
        foreach ($semestres as $semestre) {
            $id              = $semestre['Configuraplanejamento']['id'];
            $l_semestre[$id] = $semestre['Configuraplanejamento']['semestre'];
        }
        $this->set('listasemestres', $l_semestre);

        // Clear filters when coming from the grid index
        $origem = strtolower($this->referer('/', true));
        if ($origem === '/planejamentos/index') {
            foreach (['turno', 'periodo', 'professor', 'disciplina', 'departamento'] as $key) {
                $this->Session->delete($key);
            }
        }

        $condicoes = [];

        // Resolve the active semester
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
                $this->Flash->error(__('Selecione o semestre'));
                return $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
            }
        }

        $this->Session->write('semestreporextenso', $this->semestreporextenso($semestre_id));

        // Apply optional filters, persisting them in session
        $filtros = [
            'turno'        => ['value' => $turno,        'field' => 'Planejamento.turno'],
            'periodo'      => ['value' => $periodo,      'field' => 'Planejamento.periodo'],
            'professor'    => ['value' => $professor,    'field' => 'Planejamento.docente_id'],
            'disciplina'   => ['value' => $disciplina,   'field' => 'Planejamento.disciplina_id'],
            'departamento' => ['value' => $departamento, 'field' => 'Docente.departamento'],
        ];

        foreach ($filtros as $sessionKey => $filtro) {
            $valor = $this->resolveFilter($sessionKey, $filtro['value']);
            if ($valor !== null && $valor !== '' && $valor !== '0') {
                $condicoes[$filtro['field']] = $valor;
            }
            $filtros[$sessionKey]['value'] = $valor;
        }

        $this->Planejamento->recursive = 0;
        $this->set('planejamento',  $this->paginate('Planejamento', $condicoes));
        $this->set('professores',   $this->_findProfessoresAtivos());
        $this->set('disciplinas',   $this->Planejamento->Disciplina->find('list', ['fields' => 'disciplina', 'order' => 'disciplina']));
        $this->set('professor',     $filtros['professor']['value']);
        $this->set('turno',         $filtros['turno']['value']);
        $this->set('periodo',       $filtros['periodo']['value']);
        $this->set('disciplina',    $filtros['disciplina']['value']);
        $this->set('departamento',  $filtros['departamento']['value']);
    }

    public function add()
    {
        if ($this->data) {
            $p = $this->data['Planejamento'];

            $existe = $this->Planejamento->find('first', [
                'conditions' => [
                    'Planejamento.configuraplanejamento_id' => $p['configuraplanejamento_id'],
                    'Planejamento.turno'      => $p['turno'],
                    'Planejamento.periodo'    => $p['periodo'],
                    'Planejamento.dia_id'     => $p['dia_id'],
                    'Planejamento.horario_id' => $p['horario_id'],
                ]
            ]);
            
            if ($this->Planejamento->save($this->data)) {
                if ($existe) {
                    $this->Flash->error(__('Dia e horário nesse período e turno já ocupados.'));
                } else {
                    $this->Flash->success(__('Dados inseridos.'));
                }
                return $this->redirect(['controller' => 'Planejamentos', 'action' => 'listar', 'semestre_id' => $p['configuraplanejamento_id']]);
            }

            $this->Flash->error(__('Não foi possível inserir o registro.'));
        }

        $this->set('configuracao', $this->Planejamento->Configuraplanejamento->find('first', ['order' => ['Configuraplanejamento.id' => 'DESC']]));
        $this->set('dias',         $this->Planejamento->Dia->find('list', ['fields' => 'dia']));
        $this->set('horarios',     $this->Planejamento->Horario->find('list', ['fields' => 'horario']));
        $this->set('disciplinas',  $this->Planejamento->Disciplina->find('list', ['fields' => 'disciplina', 'order' => 'disciplina']));
        $this->set('professores',  $this->_findProfessoresAtivos());
        $this->set('salas',        $this->Planejamento->Sala->find('list', ['fields' => 'sala']));
    }

    public function view($id = null)
    {
        $planejamento = $this->Planejamento->find('first', [
            'conditions' => ['Planejamento.id' => $id]
        ]);
        $this->set('planejamento', $planejamento);
    }

    public function edit($id = null)
    {
        $this->Planejamento->id = $id;

        if (empty($this->data)) {
            $this->data = $this->Planejamento->read();

            $this->set('dias',        $this->Planejamento->Dia->find('list',       ['fields' => 'dia',                'order' => 'ordem']));
            $this->set('horarios',    $this->Planejamento->Horario->find('list',   ['fields' => 'horario',            'order' => 'ordem']));
            $this->set('disciplinas', $this->Planejamento->Disciplina->find('list',['fields' => 'disciplina',         'order' => 'disciplina']));
            $this->set('docentes',    $this->_findProfessoresAtivos());
            $this->set('salas',       $this->Planejamento->Sala->find('list',      ['fields' => 'sala',               'order' => 'sala']));
            $this->set('ementas',     $this->Planejamento->Ementa->find('list',    ['fields' => 'titulo',             'order' => 'titulo']));
            $this->set('optativas',   $this->Planejamento->Optativa->find('list',  ['fields' => 'Optativa.disciplina','order' => 'Optativa.disciplina']));
        } else {
            $p = $this->data['Planejamento'];

            // Bug fix: include configuraplanejamento_id so we don't match other semesters
            $existe = $this->Planejamento->find('first', [
                'conditions' => [
                    'Planejamento.configuraplanejamento_id' => $p['configuraplanejamento_id'],
                    'Planejamento.turno'      => $p['turno'],
                    'Planejamento.periodo'    => $p['periodo'],
                    'Planejamento.dia_id'     => $p['dia_id'],
                    'Planejamento.horario_id' => $p['horario_id'],
                    'NOT' => ['Planejamento.id' => $id],
                ]
            ]);

            if ($this->Planejamento->save($this->data)) {
                if ($existe) {
                    $this->Flash->warning(__('Atualizado — atenção: outro registro ocupa o mesmo dia, horário, período e turno.'));
                } else {
                    $this->Flash->success(__('Registro atualizado.'));
                }
                return $this->redirect(['controller' => 'Planejamentos', 'action' => 'view', $id]);
            }

            $this->Flash->error(__('Não foi possível atualizar o registro.'));
        }
    }

    /**
     * Schedule grid view — Diurno (8 periods) and Noturno (10 periods).
     *
     * Improvement: replaces 260 individual queries with 2 queries (one per turno),
     * then builds the grid in PHP.
     */
    public function index()
    {
        $parametros  = $this->params['named'];
        $semestre_id = isset($parametros['semestre']) ? $parametros['semestre'] : null;

        if (empty($semestre_id)) {
            $semestre_id = $this->Session->read('semestre');
            if (!$semestre_id) {
                $this->Session->delete('semestre');
                $this->Flash->error(__('Selecione um semestre para ver o planejamento.'));
                return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
            }
        }

        $this->Session->write('semestreporextenso', $this->semestreporextenso($semestre_id));

        $this->loadModel('Horario');
        $this->set('horarios',  $this->Horario->find('all'));
        $this->set('diurno',    $this->_buildGrade($semestre_id, 'Diurno',  8,  1, 4));
        $this->set('noturno',   $this->_buildGrade($semestre_id, 'Noturno', 10, 5, 6));
    }

    public function delete($id = null)
    {
        $this->Planejamento->delete($id);
        $this->Flash->success(__('Registro excluído.'));
        return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
    }

    public function otp()
    {
        $parametros   = !empty($this->params['named']) ? $this->params['named'] : [];
        $turno        = isset($parametros['turno'])        ? $parametros['turno']        : null;
        $professor    = isset($parametros['professor'])    ? $parametros['professor']    : null;
        $disciplina   = isset($parametros['disciplina'])   ? $parametros['disciplina']   : null;
        $departamento = isset($parametros['departamento']) ? $parametros['departamento'] : null;

        $semestre_id = $this->Session->read('semestre');
        if (!$semestre_id) {
            $this->Flash->error(__('Selecione o semestre.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        $this->Session->write('semestreporextenso', $this->semestreporextenso($semestre_id));

        $condicoes = ['Planejamento.configuraplanejamento_id' => $semestre_id];

        // Resolve optional filters
        $turno       = $this->resolveFilter('turno',       $turno);
        $professor   = $this->resolveFilter('professor',   $professor);
        $disciplina  = $this->resolveFilter('disciplina',  $disciplina);
        $departamento = $this->resolveFilter('departamento', $departamento);

        if ($turno) {
            $condicoes['Planejamento.turno'] = $turno;
        }
        if ($professor) {
            $condicoes['Planejamento.docente_id'] = $professor;
        }
        if ($disciplina) {
            $condicoes['Planejamento.disciplina_id'] = $disciplina;
        } else {
            $condicoes['Disciplina.codigo'] = self::DISCIPLINAS_OTP_CODIGOS;
        }
        if ($departamento) {
            $condicoes['Docente.departamento'] = $departamento;
        }

        $this->set('otp', $this->paginate('Planejamento', $condicoes));

        // Professors who teach OTP disciplines in this semester
        $professoresRaw = $this->Planejamento->find('all', [
            'fields'     => 'DISTINCT Docente.nome, Docente.id',
            'order'      => 'Docente.nome',
            'conditions' => [
                'Planejamento.configuraplanejamento_id' => $semestre_id,
                'Planejamento.disciplina_id'            => self::DISCIPLINAS_OTP_IDS,
            ]
        ]);

        $l_professores = [];
        foreach ($professoresRaw as $row) {
            $l_professores[$row['Docente']['id']] = $row['Docente']['nome'];
        }

        $this->set('professores',  $l_professores);
        $this->set('disciplinas',  $this->Planejamento->Disciplina->find('list', [
            'fields'     => 'disciplina',
            'conditions' => ['Disciplina.codigo' => self::DISCIPLINAS_OTP_CODIGOS],
            'order'      => 'disciplina',
        ]));
        $this->set('professor',    $professor);
        $this->set('turno',        $turno);
        $this->set('disciplina',   $disciplina);
        $this->set('departamento', $departamento);
    }

    public function nucleotematico()
    {
        $semestre_id = $this->Session->read('semestre');
        if (!$semestre_id) {
            $this->Flash->error(__('Selecione o semestre.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        $this->Session->write('semestreporextenso', $this->semestreporextenso($semestre_id));

        // Bug fix: was $this->Paginate($conditions?:[]()) — invalid syntax
        $conditions = [
            'Planejamento.configuraplanejamento_id' => $semestre_id,
            'Disciplina.codigo'                     => self::DISCIPLINAS_NUCLEO_CODIGOS,
        ];

        $this->set('nucleotematico', $this->paginate('Planejamento', $conditions));
    }

    public function optativa()
    {
        $semestre_id = $this->Session->read('semestre');
        if (!$semestre_id) {
            $this->Flash->error(__('Selecione o semestre.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        $this->Session->write('semestreporextenso', $this->semestreporextenso($semestre_id));

        // Bug fix: was $this->Paginate($conditions?:[]()) — invalid syntax
        $conditions = [
            'Planejamento.configuraplanejamento_id' => $semestre_id,
            'Planejamento.disciplina_id'            => self::DISCIPLINAS_OPTATIVAS_IDS,
        ];

        $this->set('optativas', $this->paginate('Planejamento', $conditions));
    }

    public function relaciona($id = null)
    {
        $this->Planejamento->id = $id;

        if (empty($this->data)) {
            $this->set('planejamento_id', $id);
            $this->set('optativas', $this->Planejamento->Ementa->find('list', ['fields' => 'titulo']));
            $this->data = $this->Planejamento->read();
        } else {
            if ($this->Planejamento->saveField('ementa_id', $this->data['Planejamento']['ementa_id'])) {
                $this->Flash->success(__('Atualizado.'));
                return $this->redirect(['controller' => 'planejamentos', 'action' => 'optativa']);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
    }

    /**
     * Create a new plan — either copied from the previous semester (versao=0)
     * or as a new version of the current semester (versao>0).
     */
    public function novoplano($id = null)
    {
        $parametros    = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id   = isset($parametros['semestre_id'])   ? $parametros['semestre_id']   : null;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : null;
        $versao        = isset($parametros['versao'])        ? $parametros['versao']        : null;

        if ($versao === null) {
            $this->Flash->error(__('Definir versão.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'add',
                '?' => compact('semestre_id', 'semestre_data', 'versao')]);
        }

        // Locate the source planning configuration
        $this->Planejamento->Configuraplanejamento->recursive = -1;

        if ($versao == 0) {
            // Copy from the previous semester's base plan
            $semestre_origem = $this->semestreanterior($semestre_data);
            $configOrigem = $this->Planejamento->Configuraplanejamento->find('first', [
                'conditions' => ['Configuraplanejamento.semestre' => $semestre_origem]
            ]);
        } else {
            // Copy from the current semester's version 0
            $configOrigem = $this->Planejamento->Configuraplanejamento->find('first', [
                'conditions' => [
                    'Configuraplanejamento.semestre' => $semestre_data,
                    'Configuraplanejamento.versao'   => 0,
                ]
            ]);
        }

        if (empty($configOrigem['Configuraplanejamento']['id'])) {
            $this->Flash->error(__('Não há configuração de planejamento para o semestre de origem.'));
            return $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
        }

        $this->Planejamento->recursive = 0;
        $planejamento = $this->Planejamento->find('all', [
            'conditions' => [
                'Planejamento.configuraplanejamento_id' => $configOrigem['Configuraplanejamento']['id'],
                'NOT' => ['Planejamento.disciplina_id' => self::DISCIPLINAS_EXCLUIDAS_CLONE],
            ]
        ]);

        if (empty($planejamento)) {
            $this->Flash->error(__('Não há planejamento do semestre.'));
            return $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
        }

        $this->Planejamento->saveMany(
            $this->_buildValoresFromPlanejamento($planejamento, $semestre_id, $versao)
        );

        $this->Flash->success(__('Dados inseridos para novo planejamento.'));
        return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
    }

    /**
     * Create a new version of an existing plan within the same semester.
     */
    public function novaversao($id = null)
    {
        $parametros    = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id   = isset($parametros['semestre_id'])   ? $parametros['semestre_id']   : null;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : null;
        $versao        = isset($parametros['versao'])        ? $parametros['versao']        : null;

        if ($versao === null) {
            $this->Flash->error(__('Definir versão.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'add',
                '?' => compact('semestre_id', 'semestre_data', 'versao')]);
        }

        // Bug fix: was find('all') then accessed as scalar — must be find('first')
        $this->Planejamento->Configuraplanejamento->recursive = -1;
        $configOrigem = $this->Planejamento->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $semestre_id]
        ]);

        if (empty($configOrigem['Configuraplanejamento']['id'])) {
            $this->Flash->error(__('Configuração não encontrada.'));
            return $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
        }

        $this->Planejamento->recursive = 0;
        $planejamento = $this->Planejamento->find('all', [
            'conditions' => [
                'Planejamento.configuraplanejamento_id' => $configOrigem['Configuraplanejamento']['id'],
                'NOT' => ['Planejamento.disciplina_id' => self::DISCIPLINAS_EXCLUIDAS_CLONE],
            ]
        ]);

        if (empty($planejamento)) {
            $this->Flash->error(__('Não há planejamento do semestre anterior.'));
            return $this->redirect(['controller' => 'Configuraplanejamentos', 'action' => 'index']);
        }

        $this->Planejamento->saveMany(
            $this->_buildValoresFromPlanejamento($planejamento, $semestre_id, $versao)
        );

        $this->Flash->success(__('Dados inseridos para nova versão do planejamento.'));
        return $this->redirect(['controller' => 'planejamentos', 'action' => 'listar',
            '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $semestre_data, 'versao' => $versao]]);
    }

    /**
     * Delete all planning records for a given configuraplanejamento.
     */
    public function excluir($id = null)
    {
        $planejamentos = $this->Planejamento->find('all', [
            'conditions' => ['Planejamento.configuraplanejamento_id' => $id],
            'fields'     => ['Planejamento.id'],
        ]);

        foreach ($planejamentos as $c) {
            $this->Planejamento->delete($c['Planejamento']['id']);
        }

        $this->Flash->success(__('Registros excluídos.'));
        return $this->redirect(['controller' => 'Planejamentos', 'action' => 'index']);
    }

    /**
     * Clone current semester's planning to the next semester (admin only).
     *
     * Bug fix: the original auth check was inverted — it blocked everyone including admins.
     */
    public function clonar()
    {
        $parametros    = !empty($this->params['named']) ? $this->params['named'] : [];
        $semestre_id   = isset($parametros['semestre_id'])   ? $parametros['semestre_id']   : null;
        $semestre_data = isset($parametros['semestre_data']) ? $parametros['semestre_data'] : null;
        $versao        = isset($parametros['versao'])        ? $parametros['versao']        : null;

        $usuarioplanejamento = $this->Session->read('usuarioplanejamento');
        if (empty($usuarioplanejamento) || !isset($usuarioplanejamento['role']) || $usuarioplanejamento['role'] !== 'admin') {
            $this->Flash->error(__('Usuário não autorizado a clonar. Você pode criar uma versão.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        $proximo_semestre = $this->semestreproximo($semestre_data);

        $configProximo = $this->Planejamento->Configuraplanejamento->find('first', [
            'conditions' => [
                'Configuraplanejamento.semestre' => $proximo_semestre,
                'Configuraplanejamento.versao'   => $versao,
            ]
        ]);

        if (!empty($configProximo['Configuraplanejamento'])) {
            // Config already exists — check if a plan is also present
            if (!empty($configProximo['Planejamento'])) {
                $this->Flash->error(__('Já há um planejamento para o semestre.'));
                return $this->redirect(['controller' => 'planejamentos', 'action' => 'listar',
                    '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $proximo_semestre, 'versao' => $versao]]);
            }
            $this->Flash->error(__('Configuração já existe para o semestre, mas ainda sem planejamento.'));
            return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'index']);
        }

        // No config yet — redirect to create one
        $this->Flash->success(__('Criar nova configuração para o semestre.'));
        return $this->redirect(['controller' => 'configuraplanejamentos', 'action' => 'add',
            '?' => ['semestre_id' => $semestre_id, 'semestre_data' => $proximo_semestre, 'versao' => $versao]]);
    }

    // -------------------------------------------------------------------------
    // Semester helpers (kept public so views/other controllers can use them)
    // -------------------------------------------------------------------------

    public function semestreanterior($semestre_data)
    {
        [$ano, $digito] = explode('-', $semestre_data);
        return ($digito == 1)
            ? ($ano - 1) . '-2'
            : $ano . '-1';
    }

    public function semestreproximo($semestre_data)
    {
        [$ano, $digito] = explode('-', $semestre_data);
        return ($digito == 1)
            ? $ano . '-2'
            : ($ano + 1) . '-1';
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Return the semester label (e.g. "2025-1 v. 0") for a given config ID.
     */
    private function semestreporextenso($id)
    {
        $this->Planejamento->Configuraplanejamento->recursive = 0;
        $config = $this->Planejamento->Configuraplanejamento->find('first', [
            'conditions' => ['Configuraplanejamento.id' => $id]
        ]);
        return $config['Configuraplanejamento']['semestre'] . ' v. ' . $config['Configuraplanejamento']['versao'];
    }

    /**
     * Resolve a filter value against the session.
     *
     * - null param  → read from session (keep current)
     * - '0' / ''    → delete from session (clear filter), return null
     * - other value → write to session, return value
     */
    private function resolveFilter($sessionKey, $valor)
    {
        if ($valor === null) {
            return $this->Session->read($sessionKey);
        }

        if ($valor === '0' || $valor === '') {
            $this->Session->delete($sessionKey);
            return null;
        }

        $this->Session->write($sessionKey, $valor);
        return $valor;
    }

    /**
     * Build the schedule grid for one turno using a single DB query.
     *
     * Improvement: replaces the original per-cell query loop (up to 160 queries
     * for Diurno, 100 for Noturno) with one query + PHP array indexing.
     *
     * @param  int    $semestre_id
     * @param  string $turno        'Diurno' or 'Noturno'
     * @param  int    $maxPeriodos  8 for Diurno, 10 for Noturno
     * @param  int    $horarioMin   First horario_id for this turno (1 or 5)
     * @param  int    $horarioMax   Last  horario_id for this turno (4 or 6)
     * @return array  $grade[periodo][horario_id][dia_id]
     */
    private function _buildGrade($semestre_id, $turno, $maxPeriodos, $horarioMin, $horarioMax)
    {
        $todos = $this->Planejamento->find('all', [
            'conditions' => [
                'Planejamento.configuraplanejamento_id' => $semestre_id,
                'Planejamento.turno'                    => $turno,
            ]
        ]);

        // Index records by [periodo][horario_id][dia_id][]
        $mapa = [];
        foreach ($todos as $p) {
            $per = $p['Planejamento']['periodo'];
            $hor = $p['Planejamento']['horario_id'];
            $dia = $p['Planejamento']['dia_id'];
            $mapa[$per][$hor][$dia][] = $p;
        }

        $grade = [];
        for ($periodo = 1; $periodo <= $maxPeriodos; $periodo++) {
            for ($horario = $horarioMin; $horario <= $horarioMax; $horario++) {
                for ($dia = 1; $dia <= 5; $dia++) {
                    $registros = isset($mapa[$periodo][$horario][$dia])
                        ? $mapa[$periodo][$horario][$dia]
                        : [];

                    $celula = [
                        'Planejamento' => [
                            'turno'      => $turno,
                            'periodo'    => $periodo,
                            'dia_id'     => $dia,
                            'horario_id' => $horario,
                        ]
                    ];

                    foreach ($registros as $idx => $r) {
                        $celula['Planejamento'][$idx] = [
                            'configuraplanejamento_id' => $r['Planejamento']['configuraplanejamento_id'],
                            'sala'                     => 'Sala: ' . $r['Sala']['sala'],
                            'sala_id'                  => $r['Planejamento']['sala_id'],
                            'disciplina'               => $r['Disciplina']['disciplina'],
                            'disciplina_id'            => $r['Planejamento']['disciplina_id'],
                            'docente'                  => $r['Docente']['nome'],
                            'docente_id'               => $r['Planejamento']['docente_id'],
                        ];
                    }

                    $grade[$periodo][$horario][$dia] = $celula;
                }
            }
        }

        return $grade;
    }

    /**
     * Map a list of planejamento records into the array format expected by saveMany().
     * Used by both novoplano() and novaversao() — eliminates duplicated code.
     */
    private function _buildValoresFromPlanejamento(array $planejamento, $semestre_id, $versao)
    {
        $valores = [];
        foreach ($planejamento as $i => $c) {
            $p = $c['Planejamento'];
            $valores[$i + 1] = [
                'Planejamento' => [
                    'configuraplanejamento_id' => $semestre_id,
                    'versao'                   => $versao,
                    'turno'                    => $p['turno']         ?: '',
                    'periodo'                  => $p['periodo']       ?: '',
                    'dia_id'                   => $p['dia_id']        ?: '',
                    'horario_id'               => $p['horario_id']    ?: '',
                    'sala_id'                  => $p['sala_id']       ?: 0,
                    'disciplina_id'            => $p['disciplina_id'] ?: '',
                    'docente_id'               => $p['docente_id']    ?: 0,
                ]
            ];
        }
        return $valores;
    }

    /**
     * Find active professors (those without a departure reason).
     */
    private function _findProfessoresAtivos()
    {
        return $this->Planejamento->Docente->find('list', [
            'fields'     => 'nome',
            'order'      => 'nome',
            'conditions' => ['OR' => ['motivoegresso IS NULL', 'motivoegresso' => '']],
        ]);
    }
}
