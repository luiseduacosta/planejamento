<?php
/**
 * Planejamento fixture for PlanejamentosControllerTest.
 *
 * Uses $import to inherit the table schema from the test database automatically,
 * avoiding manual field-list maintenance.
 */
class PlanejamentoFixture extends CakeTestFixture {

    /**
     * Import schema from the live table; no need to list fields manually.
     */
    public $import = ['table' => 'planejamentos', 'connection' => 'test'];

    /**
     * Records — 5 entries covering the test scenarios:
     *
     *   ID 1   — valid record (for view / edit / delete / relaciona tests)
     *   ID 2   — valid record, same config as ID 1 (for excluir multi-record test)
     *   ID 3   — different config, different slot (for conflict tests)
     *   ID 4   — OTP discipline record (for broader scenario coverage)
     *   ID 5   — same slot as ID 3 (for edit() conflict test — different ID, same cell)
     */
    public $records = [
        [
            'id'                       => 1,
            'configuraplanejamento_id' => 1,
            'turno'                    => 'Diurno',
            'periodo'                  => 1,
            'dia_id'                   => 1,
            'horario_id'               => 1,
            'sala_id'                  => 1,
            'disciplina_id'            => 1,
            'docente_id'               => 1,
            'ementa_id'                => null,
            'optativa_id'              => null,
            'observacoes'              => 'Fixture record 1',
            'created'                  => '2025-01-01 00:00:00',
            'modified'                 => '2025-01-01 00:00:00',
        ],
        [
            'id'                       => 2,
            'configuraplanejamento_id' => 1,
            'turno'                    => 'Diurno',
            'periodo'                  => 2,
            'dia_id'                   => 2,
            'horario_id'               => 2,
            'sala_id'                  => 2,
            'disciplina_id'            => 2,
            'docente_id'               => 2,
            'ementa_id'                => null,
            'optativa_id'              => null,
            'observacoes'              => 'Fixture record 2 — same config as #1',
            'created'                  => '2025-01-01 00:00:00',
            'modified'                 => '2025-01-01 00:00:00',
        ],
        [
            'id'                       => 3,
            'configuraplanejamento_id' => 2,
            'turno'                    => 'Diurno',
            'periodo'                  => 1,
            'dia_id'                   => 1,
            'horario_id'               => 1,
            'sala_id'                  => 3,
            'disciplina_id'            => 3,
            'docente_id'               => 3,
            'ementa_id'                => null,
            'optativa_id'              => null,
            'observacoes'              => 'Fixture record 3 — different config',
            'created'                  => '2025-01-01 00:00:00',
            'modified'                 => '2025-01-01 00:00:00',
        ],
        [
            'id'                       => 4,
            'configuraplanejamento_id' => 2,
            'turno'                    => 'Noturno',
            'periodo'                  => 5,
            'dia_id'                   => 3,
            'horario_id'               => 5,
            'sala_id'                  => 4,
            'disciplina_id'            => 16,
            'docente_id'               => 4,
            'ementa_id'                => null,
            'optativa_id'              => null,
            'observacoes'              => 'Fixture record 4 — OTP',
            'created'                  => '2025-01-01 00:00:00',
            'modified'                 => '2025-01-01 00:00:00',
        ],
        [
            'id'                       => 5,
            'configuraplanejamento_id' => 2,
            'turno'                    => 'Diurno',
            'periodo'                  => 1,
            'dia_id'                   => 1,
            'horario_id'               => 1,
            'sala_id'                  => 5,
            'disciplina_id'            => 5,
            'docente_id'               => 5,
            'ementa_id'                => null,
            'optativa_id'              => null,
            'observacoes'              => 'Fixture record 5 — same slot as #3 (conflict scenario)',
            'created'                  => '2025-01-01 00:00:00',
            'modified'                 => '2025-01-01 00:00:00',
        ],
    ];
}
