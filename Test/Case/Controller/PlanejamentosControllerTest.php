<?php
/**
 * PlanejamentosControllerTest — regression tests for the five null-ID guard fixes
 * and the intentional conflict-warning (non-blocking) behaviour in add()/edit().
 *
 * CakePHP 2.x ControllerTestCase.
 *
 * Usage:
 *   php -d auto_prepend_file=Test/polyfill.php Vendor/bin/phpunit Test/Case/Controller/PlanejamentosControllerTest.php
 */
App::uses('PlanejamentosController', 'Controller');

class PlanejamentosControllerTest extends ControllerTestCase
{
    /** Fixture to load per test */
    public $fixtures = ['app.planejamento'];

    // -----------------------------------------------------------------------
    // Helpers
    // -----------------------------------------------------------------------

    /**
     * Create a mock controller with Auth returning the given user.
     *
     * Only Auth is partially mocked (just the `user()` method); Flash, Session
     * and the Planejamento model are left real so flash messages and DB calls
     * work normally.
     *
     * @param array|null $user  Assoc array for Auth::user(), e.g. ['id' => 1, 'role' => 'admin'].
     *                          Null to leave user() returning null (default mock behaviour).
     * @return Controller
     */
    protected function _mockAuth($user = null)
    {
        $mocks = ['components' => ['Auth' => ['user']]];
        $ctrl  = $this->generate('Planejamentos', $mocks);

        // By default a partially mocked Auth::user() returns null.
        // Set an explicit return value when requested.
        if ($user !== null) {
            $ctrl->Auth
                ->expects($this->any())
                ->method('user')
                ->will($this->returnCallback(function ($key = null) use ($user) {
                    if ($key === null) {
                        return $user;
                    }
                    return isset($user[$key]) ? $user[$key] : null;
                }));
        }

        return $ctrl;
    }

    /**
     * Assert a Flash message was set with the given text.
     *
     * CakePHP 2.x Flash component stores messages in Session under
     * 'Message.flash'. We read that key to verify the message.
     *
     * @param string $expected  Expected message text
     * @param string $keySuffix Suffix to append (CakePHP may store multiple)
     */
    protected function _assertFlashMessage($expected, $keySuffix = '')
    {
        $message = CakeSession::read("Message.flash{$keySuffix}");
        $this->assertNotEmpty($message, "Expected flash message '{$expected}' but none found.");
        $this->assertStringContainsString(
            $expected,
            $message['message'],
            "Flash message mismatch."
        );
    }

    /**
     * Assert that the last testAction resulted in a redirect.
     *
     * @param string|null $contains Optional substring to check in redirect URL
     */
    protected function _assertRedirect($contains = null)
    {
        $this->assertNotEmpty($this->headers, 'Expected redirect headers, none found.');
        $location = isset($this->headers['Location']) ? $this->headers['Location'] : '';
        $this->assertNotEmpty($location, 'Expected Location header but none set.');
        if ($contains !== null) {
            $this->assertStringContainsString(
                $contains,
                $location,
                "Expected redirect to contain '{$contains}', got '{$location}'."
            );
        }
    }

    // -----------------------------------------------------------------------
    // R-1 : view($id) null/missing guards
    // -----------------------------------------------------------------------

    /** @test */
    public function testViewNullId()
    {
        // view() is in Auth::allow() list, no auth mock needed
        $this->testAction('/planejamentos/view', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('listar');
    }

    /** @test */
    public function testViewNonexistentId()
    {
        $this->testAction('/planejamentos/view/99999', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('listar');
    }

    /** @test */
    public function testViewValidId()
    {
        $vars = $this->testAction('/planejamentos/view/1', ['return' => 'vars']);
        $this->assertArrayHasKey('planejamento', $vars, 'Expected $planejamento view var.');
        $this->assertEquals(1, $vars['planejamento']['Planejamento']['id']);
    }

    // -----------------------------------------------------------------------
    // R-2 : delete($id) null/missing guards
    // -----------------------------------------------------------------------

    /** @test */
    public function testDeleteNullId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/delete', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('configuraplanejamentos');
    }

    /** @test */
    public function testDeleteNonexistentId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/delete/99999', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('configuraplanejamentos');
    }

    /** @test */
    public function testDeleteValidId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/delete/2', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro excluído');

        // Confirm the record is gone from the fixture-backed DB
        $Planejamento = ClassRegistry::init('Planejamento');
        $this->assertFalse(
            $Planejamento->exists(2),
            'Record 2 should have been deleted.'
        );
    }

    // -----------------------------------------------------------------------
    // R-3 : edit($id) null/missing guards
    // -----------------------------------------------------------------------

    /** @test */
    public function testEditNullId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/edit', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('listar');
    }

    /** @test */
    public function testEditNonexistentId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/edit/99999', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('listar');
    }

    /** @test */
    public function testEditValidId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $vars = $this->testAction('/planejamentos/edit/1', ['return' => 'vars']);
        $this->assertArrayHasKey('dias', $vars, 'Expected $dias view var for edit form.');
        $this->assertEquals(1, $this->controller->data['Planejamento']['id']);
    }

    // -----------------------------------------------------------------------
    // R-4 : relaciona($id) null/missing guards
    // -----------------------------------------------------------------------

    /** @test */
    public function testRelacionaNullId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/relaciona', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('listar');
    }

    /** @test */
    public function testRelacionaNonexistentId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/relaciona/99999', ['return' => 'vars']);
        $this->_assertFlashMessage('Registro não encontrado');
        $this->_assertRedirect('listar');
    }

    /** @test */
    public function testRelacionaValidId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $vars = $this->testAction('/planejamentos/relaciona/1', ['return' => 'vars']);
        $this->assertArrayHasKey('optativas', $vars, 'Expected $optativas view var.');
        $this->assertEquals(1, $this->controller->data['Planejamento']['id']);
    }

    // -----------------------------------------------------------------------
    // R-5 : excluir($id) null-ID and empty-result-set guards
    // -----------------------------------------------------------------------

    /** @test */
    public function testExcluirNullId()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $this->testAction('/planejamentos/excluir', ['return' => 'vars']);
        $this->_assertFlashMessage('Configuração não encontrada');
        $this->_assertRedirect('index');
    }

    /** @test */
    public function testExcluirNoRecords()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        // configuraplanejamento_id 99 has no planejamento records
        $this->testAction('/planejamentos/excluir/99', ['return' => 'vars']);
        $this->_assertFlashMessage('Nenhum registro encontrado para exclusão');
        $this->_assertRedirect('index');
    }

    /** @test */
    public function testExcluirWithRecords()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);
        $Planejamento = ClassRegistry::init('Planejamento');

        // configuraplanejamento_id=1 should have records 1 and 2
        $before = $Planejamento->find('count', [
            'conditions' => ['Planejamento.configuraplanejamento_id' => 1],
        ]);
        $this->assertGreaterThan(0, $before, 'Fixture must have records for config 1');

        $this->testAction('/planejamentos/excluir/1', ['return' => 'vars']);
        $this->_assertFlashMessage('Registros excluídos');

        // Confirm records were deleted
        $after = $Planejamento->find('count', [
            'conditions' => ['Planejamento.configuraplanejamento_id' => 1],
        ]);
        $this->assertEquals(0, $after, 'All records for config 1 should be deleted.');
    }

    // -----------------------------------------------------------------------
    // Phase 3 : Conflict-detection non-regression (intentional behaviour)
    // -----------------------------------------------------------------------

    /** @test */
    public function testAddSavesEvenWithConflict()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);

        // POST data that matches the slot of fixture record 1 (config 1, Diurno, periodo 1, dia 1, horario 1)
        $conflictData = [
            'Planejamento' => [
                'configuraplanejamento_id' => 1,
                'turno'                    => 'Diurno',
                'periodo'                  => 1,
                'dia_id'                   => 1,
                'horario_id'               => 1,
                'sala_id'                  => 10,
                'disciplina_id'            => 10,
                'docente_id'               => 10,
            ],
        ];

        $this->testAction('/planejamentos/add', [
            'data'   => $conflictData,
            'method' => 'POST',
            'return' => 'vars',
        ]);

        // Should go to listar after save
        $this->_assertRedirect('listar');

        // The saved data must have been persisted even though a conflict was detected
        $Planejamento = ClassRegistry::init('Planejamento');
        $saved = $Planejamento->find('first', [
            'conditions' => [
                'Planejamento.configuraplanejamento_id' => 1,
                'Planejamento.disciplina_id'            => 10,
            ],
        ]);
        $this->assertNotEmpty($saved, 'Record must be saved even when a slot conflict exists.');
    }

    /** @test */
    public function testEditSavesEvenWithConflict()
    {
        $this->_mockAuth(['id' => 1, 'role' => 'admin']);

        // Edit record 3 to use the same slot as record 5
        // Record 3: config 2, Diurno, periodo 1, dia 1, horario 1
        // Record 5: config 2, Diurno, periodo 1, dia 1, horario 1 (same slot!)
        // Editing record 3 to same values should still succeed (staying in same slot)
        // with a conflict warning about record 5
        $conflictData = [
            'Planejamento' => [
                'configuraplanejamento_id' => 2,
                'turno'                    => 'Diurno',
                'periodo'                  => 1,
                'dia_id'                   => 1,
                'horario_id'               => 1,
                'sala_id'                  => 3,
                'disciplina_id'            => 99,
                'docente_id'               => 3,
            ],
        ];

        $this->testAction('/planejamentos/edit/3', [
            'data'   => $conflictData,
            'method' => 'POST',
            'return' => 'vars',
        ]);

        // Should redirect to view after successful save
        $this->_assertRedirect('view');

        // Verify discipline was updated (proves save happened despite conflict)
        $Planejamento = ClassRegistry::init('Planejamento');
        $updated = $Planejamento->find('first', [
            'conditions' => ['Planejamento.id' => 3],
        ]);
        $this->assertEquals(
            99,
            $updated['Planejamento']['disciplina_id'],
            'Record 3 discipline should be updated to 99, proving save succeeded.'
        );
    }
}
