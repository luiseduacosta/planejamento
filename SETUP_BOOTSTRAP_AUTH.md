# CakePHP 5 Setup Guide: Bootstrap 5 + Authorization Policies
## Projeto: Planejamento5

---

## ✅ Completed Steps

1. ✅ Created CakePHP 5 application (Planejamento5)
2. ✅ Installed Authentication plugin (`cakephp/authentication`)
3. ✅ Installed Authorization plugin (`cakephp/authorization`)

---

## 📋 Next Steps to Complete

### **Step 1: Update Application Class**

**File:** `/home/luis/html/Planejamento5/src/Application.php`

Add these imports at the top:
```php
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Policy\OrmResolver;
use Authorization\Policy\ResolverCollection;
```

Make Application class implement the interfaces:
```php
class Application extends BaseApplication implements 
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
```

Add these methods to the Application class:

```php
/**
 * Get authentication service
 */
public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
{
    $service = new AuthenticationService();

    $service->setConfig([
        'loginRedirect' => [
            'controller' => 'Planejamentos',
            'action' => 'listar'
        ],
        'logoutRedirect' => [
            'controller' => 'Pages',
            'action' => 'display',
            'home'
        ],
    ]);

    // Load identifiers
    $service->loadIdentifier('Authentication.Password', [
        'fields' => [
            'username' => 'username',
            'password' => 'password',
        ],
        'resolver' => [
            'className' => 'Authentication.Orm',
            'userModel' => 'Users',
        ],
    ]);

    // Load authenticators
    $service->loadAuthenticator('Authentication.Session');
    $service->loadAuthenticator('Authentication.Form', [
        'fields' => [
            'username' => 'username',
            'password' => 'password',
        ],
        'loginUrl' => '/users/login',
    ]);

    return $service;
}

/**
 * Get authorization service
 */
public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
{
    $resolver = new ResolverCollection([
        new OrmResolver(),
    ]);

    return new AuthorizationService($resolver);
}
```

Update `middleware()` method to add auth middleware:
```php
public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
{
    // ... existing middleware ...
    
    // Add authentication & authorization middleware
    $middlewareQueue->add(new AuthenticationMiddleware($this));
    $middlewareQueue->add(new AuthorizationMiddleware($this));
    
    return $middlewareQueue;
}
```

---

### **Step 2: Update AppController**

**File:** `/home/luis/html/Planejamento5/src/Controller/AppController.php`

```php
<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Controller\Controller;
use Cake\Event\EventInterface;

class AppController extends Controller
{
    public function initialize(): void
    {
        parent::initialize();

        $this->loadComponent('Flash');
        $this->loadComponent('FormProtection');
        $this->loadComponent('Authentication.Authentication');
        $this->loadComponent('Authorization.Authorization');
    }
    
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        
        // Configure Auth - allow these actions without login
        $this->Authentication->addUnauthenticatedActions([
            'display', // Pages
            'index', 'view', 'listar', 'otp', 'nucleotematico', 'optativa' // Public views
        ]);
    }
}
```

---

### **Step 3: Create Bootstrap 5 Layout**

**File:** `/home/luis/html/Planejamento5/templates/layout/default.php`

```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($this->fetch('title')) ?> - Planejamento ESS/UFRJ</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <?= $this->Html->css('custom') ?>
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <?= $this->Html->link('Planejamento ESS', ['controller' => 'Pages', 'action' => 'display', 'home'], [
                'class' => 'navbar-brand'
            ]) ?>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <?= $this->Html->link('Planejamentos', ['controller' => 'Planejamentos', 'action' => 'listar'], [
                            'class' => 'nav-link'
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('Docentes', ['controller' => 'Docentes', 'action' => 'index'], [
                            'class' => 'nav-link'
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('Disciplinas', ['controller' => 'Disciplinas', 'action' => 'index'], [
                            'class' => 'nav-link'
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('Salas', ['controller' => 'Salas', 'action' => 'index'], [
                            'class' => 'nav-link'
                        ]) ?>
                    </li>
                    <li class="nav-item">
                        <?= $this->Html->link('Configurações', ['controller' => 'Configuraplanejamentos', 'action' => 'index'], [
                            'class' => 'nav-link'
                        ]) ?>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if ($this->request->getAttribute('identity')): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= h($this->request->getAttribute('identity')->username) ?>
                                <span class="badge bg-light text-dark"><?= h($this->request->getAttribute('identity')->role) ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <?= $this->Html->link('Perfil', ['controller' => 'Users', 'action' => 'profile'], [
                                        'class' => 'dropdown-item'
                                    ]) ?>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <?= $this->Html->link('Sair', ['controller' => 'Users', 'action' => 'logout'], [
                                        'class' => 'dropdown-item'
                                    ]) ?>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <?= $this->Html->link('Login', ['controller' => 'Users', 'action' => 'login'], [
                                'class' => 'nav-link'
                            ]) ?>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <div class="container mt-3">
        <?= $this->Flash->render() ?>
        
        <!-- Main Content -->
        <?= $this->fetch('content') ?>
    </div>

    <!-- Footer -->
    <footer class="bg-light text-center text-muted py-4 mt-5">
        <div class="container">
            <p>&copy; <?= date('Y') ?> ESS/UFRJ - Sistema de Planejamento Acadêmico</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->fetch('script') ?>
</body>
</html>
```

---

### **Step 4: Create Custom CSS**

**File:** `/home/luis/html/Planejamento5/webroot/css/custom.css`

```css
/* ESS/UFRJ Color Scheme */
:root {
    --ufrj-blue: #003366;
    --ufrj-blue-light: #0066cc;
    --ufrj-orange: #ff6600;
}

.bg-primary {
    background-color: var(--ufrj-blue) !important;
}

.navbar-brand {
    font-weight: bold;
    font-size: 1.25rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    margin-bottom: 1.5rem;
}

.card-header {
    background-color: var(--ufrj-blue-light);
    color: white;
    font-weight: bold;
}

.btn-primary {
    background-color: var(--ufrj-blue);
    border-color: var(--ufrj-blue);
}

.btn-primary:hover {
    background-color: var(--ufrj-blue-light);
    border-color: var(--ufrj-blue-light);
}

.badge-turno {
    font-size: 0.875rem;
}

.table-schedule {
    font-size: 0.875rem;
}

.table-schedule th {
    background-color: var(--ufrj-blue);
    color: white;
    text-align: center;
    vertical-align: middle;
}

.table-schedule td {
    vertical-align: middle;
    text-align: center;
}

/* Mobile responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.75rem;
    }
}
```

---

### **Step 5: Create Policy Classes**

#### **5.1: PlanejamentoPolicy**

**File:** `/home/luis/html/Planejamento5/src/Policy/PlanejamentoPolicy.php`

```php
<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Planejamento;
use Authorization\IdentityInterface;

class PlanejamentoPolicy
{
    // Anyone can view list
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    // Anyone can view单个
    public function canView(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return true;
    }

    // Must be logged in to add
    public function canAdd(IdentityInterface $user): bool
    {
        return $user !== null;
    }

    // Admin or Editor can edit
    public function canEdit(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    // Only Admin can delete
    public function canDelete(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return $user->role === 'admin';
    }

    // Only Admin can clone
    public function canClone(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    // Admin or Editor can create version
    public function canCreateVersion(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
```

#### **5.2: DocentePolicy**

**File:** `/home/luis/html/Planejamento5/src/Policy/DocentePolicy.php`

```php
<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Docente;
use Authorization\IdentityInterface;

class DocentePolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Docente $docente): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canEdit(IdentityInterface $user, Docente $docente): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    public function canDelete(IdentityInterface $user, Docente $docente): bool
    {
        return $user->role === 'admin';
    }
}
```

#### **5.3: ConfiguraplanejamentoPolicy**

**File:** `/home/luis/html/Planejamento5/src/Policy/ConfiguraplanejamentoPolicy.php`

```php
<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Configuraplanejamento;
use Authorization\IdentityInterface;

class ConfiguraplanejamentoPolicy
{
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    public function canView(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        return true;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user !== null;
    }

    // User must be owner or admin
    public function canEdit(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        return $user->role === 'admin' || $config->usuarioplanejamento_id === $user->id;
    }

    // Only admin can delete, and only if no planejamentos
    public function canDelete(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        return $user->role === 'admin';
    }
}
```

#### **5.4: UserPolicy**

**File:** `/home/luis/html/Planejamento5/src/Policy/UserPolicy.php`

```php
<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\User;
use Authorization\IdentityInterface;

class UserPolicy
{
    // Only admin can manage users
    public function canIndex(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canView(IdentityInterface $user, User $userEntity): bool
    {
        return $user->role === 'admin' || $user->id === $userEntity->id;
    }

    public function canAdd(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    public function canEdit(IdentityInterface $user, User $userEntity): bool
    {
        return $user->role === 'admin' || $user->id === $userEntity->id;
    }

    public function canDelete(IdentityInterface $user, User $userEntity): bool
    {
        return $user->role === 'admin' && $user->id !== $userEntity->id; // Can't delete self
    }
}
```

---

### **Step 6: Example Controller Using Policies**

**File:** `/home/luis/html/Planejamento5/src/Controller/PlanejamentosController.php`

```php
<?php
declare(strict_types=1);

namespace App\Controller;

class PlanejamentosController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        
        $this->loadComponent('Paginator');
    }

    public function index()
    {
        // Authorization is automatic via middleware
        $planejamentos = $this->paginate($this->Planejamentos);
        $this->set(compact('planejamentos'));
    }

    public function listar()
    {
        $query = $this->Planejamentos->find()
            ->contain(['Dia', 'Horario', 'Disciplina', 'Docente', 'Sala', 'Configuraplanejamento']);
        
        // Apply filters from query params
        $semestre_id = $this->request->getQuery('semestre_id');
        if ($semestre_id) {
            $query->where(['Planejamentos.configuraplanejamento_id' => $semestre_id]);
        }
        
        $planejamentos = $this->paginate($query);
        $this->set(compact('planejamentos'));
    }

    public function view($id = null)
    {
        $planejamento = $this->Planejamentos->get($id, [
            'contain' => ['Dia', 'Horario', 'Disciplina', 'Docente', 'Sala']
        ]);
        
        // Check authorization
        $this->Authorization->authorize($planejamento, 'view');
        
        $this->set(compact('planejamento'));
    }

    public function add()
    {
        $planejamento = $this->Planejamentos->newEmptyEntity();
        
        // Check authorization
        $this->Authorization->authorize($planejamento, 'add');
        
        if ($this->request->is('post')) {
            $planejamento = $this->Planejamentos->patchEntity($planejamento, $this->request->getData());
            if ($this->Planejamentos->save($planejamento)) {
                $this->Flash->success(__('Planejamento criado com sucesso.'));
                return $this->redirect(['action' => 'listar']);
            }
            $this->Flash->error(__('Não foi possível criar o planejamento.'));
        }
        
        $this->set(compact('planejamento'));
        // Load related data for form
        $this->set('dias', $this->Planejamentos->Dias->find('list', ['displayField' => 'dia']));
        $this->set('horarios', $this->Planejamentos->Horarios->find('list', ['displayField' => 'horario']));
        $this->set('disciplinas', $this->Planejamentos->Disciplinas->find('list', ['displayField' => 'disciplina']));
        $this->set('docentes', $this->Planejamentos->Docentes->find('list', ['displayField' => 'nome']));
        $this->set('salas', $this->Planejamentos->Salas->find('list', ['displayField' => 'sala']));
    }

    public function edit($id = null)
    {
        $planejamento = $this->Planejamentos->get($id);
        
        // Check authorization
        $this->Authorization->authorize($planejamento, 'edit');
        
        if ($this->request->is(['post', 'put'])) {
            $planejamento = $this->Planejamentos->patchEntity($planejamento, $this->request->getData());
            if ($this->Planejamentos->save($planejamento)) {
                $this->Flash->success(__('Planejamento atualizado.'));
                return $this->redirect(['action' => 'view', $planejamento->id]);
            }
            $this->Flash->error(__('Não foi possível atualizar.'));
        }
        
        $this->set(compact('planejamento'));
        $this->set('dias', $this->Planejamentos->Dias->find('list', ['displayField' => 'dia']));
        $this->set('horarios', $this->Planejamentos->Horarios->find('list', ['displayField' => 'horario']));
        $this->set('disciplinas', $this->Planejamentos->Disciplinas->find('list', ['displayField' => 'disciplina']));
        $this->set('docentes', $this->Planejamentos->Docentes->find('list', ['displayField' => 'nome']));
        $this->set('salas', $this->Planejamentos->Salas->find('list', ['displayField' => 'sala']));
    }

    public function delete($id = null)
    {
        $planejamento = $this->Planejamentos->get($id);
        
        // Check authorization
        $this->Authorization->authorize($planejamento, 'delete');
        
        $this->request->allowMethod(['post', 'delete']);
        if ($this->Planejamentos->delete($planejamento)) {
            $this->Flash->success(__('Planejamento excluído.'));
        } else {
            $this->Flash->error(__('Não foi possível excluir.'));
        }
        
        return $this->redirect(['action' => 'listar']);
    }
}
```

---

### **Step 7: Example View with Bootstrap 5**

**File:** `/home/luis/html/Planejamento5/templates/Planejamentos/listar.php`

```php
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-calendar-week"></i> Planejamentos
                    </h4>
                    <?= $this->Html->link('Novo Planejamento', ['action' => 'add'], [
                        'class' => 'btn btn-success',
                        'escape' => false
                    ]) ?>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <?= $this->Form->create(null, ['type' => 'get', 'class' => 'row g-3 mb-4']) ?>
                    <div class="col-md-3">
                        <?= $this->Form->control('semestre_id', [
                            'options' => $semestres,
                            'empty' => 'Selecione o Semestre',
                            'class' => 'form-select'
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $this->Form->control('turno', [
                            'options' => ['Diurno' => 'Diurno', 'Noturno' => 'Noturno'],
                            'empty' => 'Todos os Turnos',
                            'class' => 'form-select'
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $this->Form->control('disciplina_id', [
                            'options' => $disciplinas,
                            'empty' => 'Todas as Disciplinas',
                            'class' => 'form-select'
                        ]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $this->Form->button('Filtrar', ['class' => 'btn btn-primary']) ?>
                        <?= $this->Html->link('Limpar', ['action' => 'listar'], ['class' => 'btn btn-secondary']) ?>
                    </div>
                    <?= $this->Form->end() ?>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Dia</th>
                                    <th>Horário</th>
                                    <th>Turno</th>
                                    <th>Período</th>
                                    <th>Disciplina</th>
                                    <th>Docente</th>
                                    <th>Sala</th>
                                    <th class="actions">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($planejamentos as $planejamento): ?>
                                <tr>
                                    <td><?= h($planejamento->dia->dia) ?></td>
                                    <td><?= h($planejamento->horario->horario) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $planejamento->turno === 'Diurno' ? 'info' : 'warning' ?>">
                                            <?= h($planejamento->turno) ?>
                                        </span>
                                    </td>
                                    <td><?= h($planejamento->periodo) ?></td>
                                    <td><?= h($planejamento->disciplina->disciplina) ?></td>
                                    <td><?= h($planejamento->docente->nome) ?></td>
                                    <td><?= h($planejamento->sala->sala) ?></td>
                                    <td class="actions">
                                        <?= $this->Html->link('<i class="bi bi-eye"></i>', ['action' => 'view', $planejamento->id], [
                                            'class' => 'btn btn-sm btn-info',
                                            'escape' => false
                                        ]) ?>
                                        <?= $this->Html->link('<i class="bi bi-pencil"></i>', ['action' => 'edit', $planejamento->id], [
                                            'class' => 'btn btn-sm btn-warning',
                                            'escape' => false
                                        ]) ?>
                                        <?= $this->Form->postLink('<i class="bi bi-trash"></i>', ['action' => 'delete', $planejamento->id], [
                                            'class' => 'btn btn-sm btn-danger',
                                            'escape' => false,
                                            'confirm' => 'Tem certeza que deseja excluir?'
                                        ]) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <nav>
                        <ul class="pagination">
                            <?= $this->Paginator->first('<< ' . __('Primeiro')) ?>
                            <?= $this->Paginator->prev('< ' . __('Anterior')) ?>
                            <?= $this->Paginator->numbers() ?>
                            <?= $this->Paginator->next(__('Próximo') . ' >') ?>
                            <?= $this->Paginator->last(__('Último') . ' >>') ?>
                        </ul>
                        <p class="text-muted"><?= $this->Paginator->counter() ?></p>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## 🚀 Quick Start Commands

After completing all the setup steps above:

```bash
# 1. Create Users table migration
bin/cake bake migration CreateUsers username:string password:string role:string created modified

# 2. Run migration
bin/cake migrations migrate

# 3. Bake all models (optional, saves time)
bin/cake bake all Dias
bin/cake bake all Horarios
bin/cake bake all Salas
bin/cake bake all Docentes
bin/cake bake all Disciplinas

# 4. Start development server
bin/cake server

# 5. Access application
# http://localhost:8765
```

---

## 📚 Additional Resources

- **Bootstrap 5 Docs:** https://getbootstrap.com/docs/5.3/
- **CakePHP Auth:** https://book.cakephp.org/authentication/3/en/
- **CakePHP Authorization:** https://book.cakephp.org/authorization/3/en/
- **CakePHP 5 Docs:** https://book.cakephp.org/5/en/

---

## ✅ Checklist

- [ ] Update Application.php with auth services
- [ ] Update AppController.php with auth components
- [ ] Create Bootstrap 5 layout (templates/layout/default.php)
- [ ] Create custom CSS (webroot/css/custom.css)
- [ ] Create all Policy classes (src/Policy/*)
- [ ] Create Users table migration
- [ ] Run migrations
- [ ] Create UsersController for login/logout
- [ ] Test authentication flow
- [ ] Test authorization policies
- [ ] Migrate remaining controllers with policies
- [ ] Migrate all views to Bootstrap 5

---

**Ready to start coding!** 🎉
