# Implementation Summary: Bootstrap 5 + Authorization Policies
## Quick Reference Guide

---

## 🎯 What's Been Prepared

### ✅ Installed Packages
1. **cakephp/authentication** (v3.3.5) - User authentication
2. **cakephp/authorization** (v3.5.2) - Policy-based authorization
3. **Bootstrap 5.3.3** - Via CDN (ready to use in layout)

### 📁 Files Created
1. ✅ **MIGRATION_PLAN.md** - Complete migration roadmap
2. ✅ **SETUP_BOOTSTRAP_AUTH.md** - Detailed setup guide with code examples
3. ✅ **IMPLEMENTATION_SUMMARY.md** - This quick reference

---

## 🚀 Implementation Steps (In Order)

### **Step 1: Configure Application.php**
Open `/home/luis/html/Planejamento5/src/Application.php`

**Add these imports at top:**
```php
use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Authorization\AuthorizationService;
use Authorization\AuthorizationServiceInterface;
use Authorization\AuthorizationServiceProviderInterface;
use Authorization\Policy\ResolverCollection;
use Authorization\Policy\OrmResolver;
```

**Update class declaration:**
```php
class Application extends BaseApplication implements 
    AuthenticationServiceProviderInterface,
    AuthorizationServiceProviderInterface
```

**Add these two methods before the closing brace:**
```php
public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
{
    $service = new AuthenticationService();
    $service->setConfig([
        'loginRedirect' => ['controller' => 'Planejamentos', 'action' => 'listar'],
        'logoutRedirect' => ['controller' => 'Pages', 'action' => 'display', 'home'],
    ]);
    
    $service->loadIdentifier('Authentication.Password', [
        'fields' => ['username' => 'username', 'password' => 'password'],
        'resolver' => [
            'className' => 'Authentication.Orm',
            'userModel' => 'Users',
        ],
    ]);
    
    $service->loadAuthenticator('Authentication.Session');
    $service->loadAuthenticator('Authentication.Form', [
        'fields' => ['username' => 'username', 'password' => 'password'],
        'loginUrl' => '/users/login',
    ]);
    
    return $service;
}

public function getAuthorizationService(ServerRequestInterface $request): AuthorizationServiceInterface
{
    return new AuthorizationService([
        'policyResolver' => new ResolverCollection([
            new OrmResolver(),
        ]),
    ]);
}
```

**Update middleware() method - add before `return $middlewareQueue;`:**
```php
$middlewareQueue->add(new AuthenticationMiddleware($this));
$middlewareQueue->add(new AuthorizationMiddleware($this));
```

---

### **Step 2: Update AppController.php**
Open `/home/luis/html/Planejamento5/src/Controller/AppController.php`

**Replace entire file with:**
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
        $this->Authentication->addUnauthenticatedActions([
            'display', 'index', 'view'
        ]);
    }
}
```

---

### **Step 3: Create Bootstrap Layout**
Create file: `/home/luis/html/Planejamento5/templates/layout/default.php`

**Copy the complete layout from SETUP_BOOTSTRAP_AUTH.md (Step 3)**

---

### **Step 4: Create Custom CSS**
Create file: `/home/luis/html/Planejamento5/webroot/css/custom.css`

**Copy CSS from SETUP_BOOTSTRAP_AUTH.md (Step 4)**

---

### **Step 5: Create Policy Classes**

Create these files in `/home/luis/html/Planejamento5/src/Policy/`:

1. **PlanejamentoPolicy.php** (copy from SETUP_BOOTSTRAP_AUTH.md Step 5.1)
2. **DocentePolicy.php** (copy from Step 5.2)
3. **ConfiguraplanejamentoPolicy.php** (copy from Step 5.3)
4. **UserPolicy.php** (copy from Step 5.4)

You can also create policies for other models later:
- DisciplinaPolicy.php
- SalaPolicy.php
- HorarioPolicy.php
- DiaPolicy.php
- EmentaPolicy.php
- OptativaPolicy.php

---

### **Step 6: Create Users Table**

```bash
cd /home/luis/html/Planejamento5

# Generate migration
bin/cake bake migration CreateUsers \
    username:string[50] \
    password:string[255] \
    role:string[20] \
    email:string[100] \
    created \
    modified

# Run migration
bin/cake migrations migrate
```

---

### **Step 7: Create UsersController for Login/Logout**

```bash
# Generate controller
bin/cake bake controller Users --no-actions
```

**Edit `/home/luis/html/Planejamento5/src/Controller/UsersController.php`:**

```php
<?php
declare(strict_types=1);

namespace App\Controller;

class UsersController extends AppController
{
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Authentication->addUnauthenticatedActions(['login']);
    }

    public function login()
    {
        $this->request->allowMethod('get');
        $result = $this->Authentication->getResult();
        
        if ($result->isValid()) {
            return $this->redirect([
                'controller' => 'Planejamentos',
                'action' => 'listar'
            ]);
        }
        
        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Usuário ou senha inválidos');
        }
    }

    public function logout()
    {
        $this->Flash->success('Até mais!');
        return $this->redirect($this->Authentication->logout());
    }
}
```

**Create login template:** `/home/luis/html/Planejamento5/templates/Users/login.php`

```php
<div class="row justify-content-center mt-5">
    <div class="col-md-6 col-lg-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0"><i class="bi bi-box-arrow-in-right"></i> Login</h4>
            </div>
            <div class="card-body">
                <?= $this->Form->create() ?>
                <div class="mb-3">
                    <?= $this->Form->control('username', [
                        'class' => 'form-control',
                        'label' => 'Usuário',
                        'required' => true
                    ]) ?>
                </div>
                <div class="mb-3">
                    <?= $this->Form->control('password', [
                        'class' => 'form-control',
                        'label' => 'Senha',
                        'required' => true
                    ]) ?>
                </div>
                <?= $this->Form->button('Entrar', ['class' => 'btn btn-primary w-100']) ?>
                <?= $this->Form->end() ?>
            </div>
        </div>
    </div>
</div>
```

---

### **Step 8: Create First Admin User**

```bash
# Start CakePHP console
bin/cake console
```

**In the console:**
```php
// Load Users table
$users = \Cake\ORM\TableRegistry::getTableLocator()->get('Users');

// Create admin user
$user = $users->newEntity([
    'username' => 'admin',
    'password' => 'admin123', // Will be hashed automatically
    'role' => 'admin',
    'email' => 'admin@ess.ufrj.br'
]);

// Save
$users->save($user);

// Create editor user for testing
$user2 = $users->newEntity([
    'username' => 'editor',
    'password' => 'editor123',
    'role' => 'editor',
    'email' => 'editor@ess.ufrj.br'
]);

$users->save($user2);

echo "Users created!\n";
exit
```

---

### **Step 9: Update Routes**

Edit `/home/luis/html/Planejamento5/config/routes.php`

**Add before the closing `});`:**
```php
// Custom routes
$builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
$builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);
```

---

### **Step 10: Test Everything**

```bash
# Start development server
bin/cake server
```

**Visit:** http://localhost:8765

**Test login:**
- Username: `admin`
- Password: `admin123`

---

## 🔑 Key Concepts

### **How Authorization Policies Work:**

1. **User tries to access action** → Middleware intercepts
2. **Controller calls:** `$this->Authorization->authorize($entity, 'action')`
3. **Authorization finds policy** → e.g., `PlanejamentoPolicy`
4. **Policy method called** → e.g., `canEdit($user, $planejamento)`
5. **Returns true/false** → Allowed or Forbidden exception

### **Policy Naming Convention:**
```
Model: Planejamento
Policy Class: PlanejamentoPolicy
Policy File: src/Policy/PlanejamentoPolicy.php
Methods: canIndex, canView, canAdd, canEdit, canDelete
```

### **Using Policies in Controllers:**
```php
// For single entity
$this->Authorization->authorize($planejamento, 'edit');

// For collection/index
$this->Authorization->authorize(Planejamento::class, 'index');

// Skip authorization (public actions)
$this->Authorization->skipAuthorization();
```

### **Bootstrap 5 Form Example:**
```php
// Standard Bootstrap form
<?= $this->Form->create($entity, ['class' => 'needs-validation']) ?>
<div class="mb-3">
    <?= $this->Form->control('field_name', [
        'class' => 'form-control',
        'label' => 'Field Label'
    ]) ?>
</div>
<?= $this->Form->button('Save', ['class' => 'btn btn-primary']) ?>
<?= $this->Form->end() ?>
```

---

## 📊 Migration Priority Order

### **Week 1: Foundation** (Current)
- [x] Install CakePHP 5
- [x] Install Auth packages
- [ ] Configure Application.php
- [ ] Configure AppController.php
- [ ] Create Bootstrap layout
- [ ] Create Users table
- [ ] Create login/logout

### **Week 2: Simple CRUD**
- [ ] Bake Dias model + controller + views
- [ ] Bake Horarios model + controller + views
- [ ] Bake Salas model + controller + views
- [ ] Add policies to each

### **Week 3: Medium Complexity**
- [ ] Bake Docentes + policy
- [ ] Bake Disciplinas + policy
- [ ] Bake Ementas + policy
- [ ] Bake Optativas + policy

### **Week 4: Complex Logic**
- [ ] Migrate Configuraplanejamentos
- [ ] Migrate Planejamentos (most complex)
- [ ] Implement scheduling logic
- [ ] Implement conflict detection

### **Week 5-6: Testing & Polish**
- [ ] Test all features
- [ ] Migrate data from old DB
- [ ] Fix bugs
- [ ] Deploy

---

## 🎨 Bootstrap 5 Quick Reference

### **Common Components:**

**Buttons:**
```html
<button class="btn btn-primary">Primary</button>
<button class="btn btn-success">Success</button>
<button class="btn btn-danger">Danger</button>
<button class="btn btn-warning">Warning</button>
<button class="btn btn-info">Info</button>
```

**Badges:**
```html
<span class="badge bg-primary">Primary</span>
<span class="badge bg-success">Success</span>
```

**Cards:**
```html
<div class="card">
    <div class="card-header">Header</div>
    <div class="card-body">Content</div>
</div>
```

**Tables:**
```html
<table class="table table-striped table-hover">
    <thead class="table-primary">...</thead>
    <tbody>...</tbody>
</table>
```

**Alerts (Flash Messages):**
```html
<div class="alert alert-success">Success message</div>
<div class="alert alert-danger">Error message</div>
```

---

## 🔒 Policy Rules Summary

| Resource | Action | Admin | Editor | Guest |
|----------|--------|-------|--------|-------|
| Planejamento | index/view | ✅ | ✅ | ✅ |
| Planejamento | add | ✅ | ✅ | ❌ |
| Planejamento | edit | ✅ | ✅ | ❌ |
| Planejamento | delete | ✅ | ❌ | ❌ |
| Planejamento | clone | ✅ | ❌ | ❌ |
| Docente | index/view | ✅ | ✅ | ✅ |
| Docente | add/edit | ✅ | ✅ | ❌ |
| Docente | delete | ✅ | ❌ | ❌ |
| Configuraplanejamento | edit | ✅ | if owner | ❌ |
| Configuraplanejamento | delete | ✅ | ❌ | ❌ |

---

## 💡 Next Actions

1. **Follow Steps 1-10** in this guide sequentially
2. **Refer to SETUP_BOOTSTRAP_AUTH.md** for complete code examples
3. **Use MIGRATION_PLAN.md** for the overall migration strategy
4. **Start with simple controllers** (Dias, Horarios, Salas)
5. **Test frequently** after each step

---

## 🆘 Troubleshooting

**Issue:** "Authorization Required" error
**Solution:** Make sure you're logged in and policy allows the action

**Issue:** Bootstrap not loading
**Solution:** Check CDN links in layout, ensure internet connection

**Issue:** Login not working
**Solution:** Verify Users table exists, user has hashed password

**Issue:** Policy not found
**Solution:** Check Policy class name matches model name exactly

---

**You're all set! Happy coding!** 🎉
