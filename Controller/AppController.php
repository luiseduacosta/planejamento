<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		https://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $components = array(
        'Session',
        'Flash',
        'Auth');

    public function beforeFilter() {

        $this->Auth->authenticate = array('Form' => array('userModel' => 'Usuarioplanejamento'));
        $this->Auth->loginAction = array('controller' => 'usuarioplanejamentos', 'action' => 'login');
        $this->Auth->loginRedirect = array('controller' => 'planejamentos', 'action' => 'index');
        $this->Auth->logoutAction = array('controller' => 'usuarioplanejamentos', 'action' => 'logout');
        $this->Auth->logoutRedirect = array('controller' => 'planejamentos', 'action' => 'index');
        $this->Auth->authorize = 'Controller';
        $this->Auth->authError = 'Acesso não autorizado.';
        $this->Auth->allow('listar', 'index', 'view', 'otp', 'nucleotematico', 'optativa');
    }

    public function isAuthorized($user = NULL) {
        // Admin pode tudo
        // pr($user);
        if ($user) {
            $this->Session->write('usuarioplanejamento', $user);
        } else {
            $this->Session->setFlash(__('Usuário visitante'));
            // $this->redirect('/usuarioplanejamentos/login');
        }

        if (isset($user['role']) && $user['role'] === 'admin') {
            return TRUE;
        }
        // Os demais não
        return FALSE;
    }

}
