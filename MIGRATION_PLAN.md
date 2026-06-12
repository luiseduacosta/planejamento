# Migration Plan: CakePHP 2.x → CakePHP 5.x
## Projeto: Planejamento Acadêmico ESS/UFRJ

---

## 📊 Application Analysis

### **What This App Does:**
Academic course planning and scheduling management system for ESS/UFRJ (Escola de Serviço Social).

**Core Features:**
1. ✅ Semester-based class planning with versioning
2. ✅ Teacher (Docente) management by department
3. ✅ Discipline/Course catalog management  
4. ✅ Room (Sala) allocation and scheduling
5. ✅ Time slot (Horário) and day management
6. ✅ Schedule conflict detection
7. ✅ Syllabus (Ementa) management
8. ✅ Elective (Optativa) course handling
9. ✅ OTP & Núcleo Temático special programs
10. ✅ User authentication with role-based access (admin/editor)

---

## 🗄️ Database Schema (10 Tables)

| Old Table | New Model | Relationships | Description |
|-----------|-----------|---------------|-------------|
| `planejamentos` | Planejamento | BelongsTo: Dia, Horario, Disciplina, Docente, Sala, Configuraplanejamento, Ementa, Optativa | Core planning records |
| `configuraplanejamentos` | Configuraplanejamento | HasMany: Planejamento | Semester configurations with versions |
| `docentes` | Docente | HasMany: Planejamento | Teachers/Professors |
| `disciplinas` | Disciplina | HasMany: Planejamento, HasMany: Ementa | Courses/Subjects |
| `salas` | Sala | HasMany: Planejamento | Classrooms |
| `horarios` | Horario | - | Time slots (6 periods) |
| `dias` | Dia | - | Days of week (Mon-Fri) |
| `ementas` | Ementa | BelongsTo: Disciplina, Docente; HasMany: Planejamento | Course syllabi |
| `optativas` | Optativa | HasMany: Planejamento | Elective courses |
| `users` | Usuarioplanejamento | HasMany: Configuraplanejamento | User authentication |

---

## 🔍 Key Business Logic to Migrate

### 1. **Scheduling System (PlanejamentosController)**
- **listar()** - Filter planning by semester, shift, period, professor, discipline, department
- **index()** - Generate schedule grid (Diurno: 8 periods × 5 days, Noturno: 10 periods × 5 days)
- **otp()** - Special OTP courses (codes: SSW401, SSW402, SSW403, SSW405)
- **nucleotematico()** - Thematic nucleus courses (codes: SSW409, SSW410)
- **optativa()** - Elective courses (ids: 54, 55)
- **novoplano()** - Clone planning from previous semester
- **novaversao()** - Create new version of current semester
- **clonar()** - Clone to next semester (admin only)

### 2. **Conflict Detection**
- Check if day/time/shift/period slot is already occupied
- Allow multiple classes in same slot (superposition tracking)
- Room conflict prevention

### 3. **Semester Management**
- Format: YYYY-N (1=first semester, 2=second semester)
- Versioning system for planning iterations
- Previous/next semester calculation

### 4. **Authentication & Authorization**
- Admin: Full access
- Editor: Limited access (can't clone, only create versions)
- Guest: View-only access to public pages

---

## ⚠️ Major Breaking Changes (CakePHP 2 → 5)

### **1. PHP Version**
- **Old:** PHP 5.3+
- **New:** PHP 8.1+ (requires PHP 8.1 minimum)

### **2. Namespace Structure**
```php
// CakePHP 2.x
class Planejamento extends AppModel { }

// CakePHP 5.x
namespace App\Model\Table;
use Cake\ORM\Table;

class PlanejamentosTable extends Table { }
```

### **3. ORM Changes**
```php
// CakePHP 2.x
$this->Planejamento->find('all', [
    'conditions' => ['turno' => 'Diurno'],
    'fields' => ['id', 'turno']
]);

// CakePHP 5.x
$query = $this->Planejamentos->find()
    ->where(['turno' => 'Diurno'])
    ->select(['id', 'turno']);
```

### **4. Authentication**
```php
// CakePHP 2.x
AuthComponent::password($password);

// CakePHP 5.x
use Cake\Auth\DefaultPasswordHasher;
(new DefaultPasswordHasher)->hash($password);
```

### **5. Session Management**
```php
// CakePHP 2.x
$this->Session->write('key', $value);
$this->Session->read('key');

// CakePHP 5.x
$this->request->getSession()->write('key', $value);
$this->request->getSession()->read('key');
```

### **6. Flash Messages**
```php
// CakePHP 2.x
$this->Session->setFlash('Message');

// CakePHP 5.x
$this->Flash->success('Message');
$this->Flash->error('Message');
```

### **7. Request/Data Handling**
```php
// CakePHP 2.x
$this->data
$this->params['named']

// CakePHP 5.x
$this->request->getData()
$this->request->getQuery()
```

### **8. Views (.ctp → .php)**
- CakePHP 5 uses Twig by default (optional)
- Can still use .php templates
- Helper methods changed slightly

---

## 📋 Migration Roadmap

### **Phase 1: Setup & Database (Week 1)**
- [x] Create new CakePHP 5 app (`Planejamento5`)
- [ ] Configure database connection in `config/app_local.php`
- [ ] Create database migration files for all 10 tables
- [ ] Run migrations to create schema
- [ ] Create Table classes with proper associations
- [ ] Create Entity classes with validation rules

### **Phase 2: Authentication & Authorization with Policies (Week 1-2)**
- [ ] Install Authentication plugin: `composer require cakephp/authentication`
- [ ] Install Authorization plugin: `composer require cakephp/authorization`
- [ ] Create Users table migration
- [ ] Create LoginController
- [ ] Migrate login/logout functionality
- [ ] **Create Policy classes for each model:**
  - [ ] `UserPolicy` - User management authorization
  - [ ] `PlanejamentoPolicy` - Planning access control
  - [ ] `DocentePolicy` - Teacher management
  - [ ] `DisciplinaPolicy` - Discipline management
  - [ ] `SalaPolicy` - Room management
  - [ ] `ConfiguraplanejamentoPolicy` - Semester config
  - [ ] `EmentaPolicy` - Syllabus management
  - [ ] `OptativaPolicy` - Elective management
- [ ] **Define Policy rules:**
  - **Admin**: Full access to all resources
  - **Editor**: Can view, create, edit planejamentos, docentes, disciplinas, ementas
  - **Editor**: Cannot delete configuraplanejamentos or clone semesters
  - **Guest**: View-only access to public pages (listar, index, otp, nucleotematico, optativa)
- [ ] Apply policies in controllers using `$this->Authorization->authorize()`
- [ ] Update password hashing to use DefaultPasswordHasher

### **Phase 3: Core Models & CRUD (Week 2-3)**
- [ ] Migrate simpler controllers first:
  - [ ] DiasController
  - [ ] HorariosController
  - [ ] SalasController
  - [ ] OptativasController
- [ ] Then complex ones:
  - [ ] DocentesController
  - [ ] DisciplinasController
  - [ ] EmentasController
  - [ ] ConfiguraplanejamentosController
  - [ ] PlanejamentosController (most complex)

### **Phase 4: Business Logic (Week 3-4)**
- [ ] Implement semester management logic
- [ ] Port scheduling conflict detection
- [ ] Migrate OTP/Núcleo Temático filtering
- [ ] Port semester cloning/versioning logic
- [ ] Implement room occupancy matrix

### **Phase 5: Views & UI with Bootstrap 5 (Week 4-5)**
- [ ] **Install Bootstrap 5.x**
  - [ ] Option 1: Use CDN in layout
  - [ ] Option 2: Install via npm: `npm install bootstrap@5`
  - [ ] Option 3: Use cakephp-bootstrap-ui plugin
- [ ] Create Bootstrap-based layout structure
  - [ ] Navbar with navigation menu
  - [ ] Flash messages using Bootstrap alerts
  - [ ] Responsive grid system for schedules
  - [ ] Cards for data display
- [ ] Migrate all .ctp views to Bootstrap components:
  - [ ] Forms → Bootstrap form controls
  - [ ] Tables → Bootstrap tables (table-striped, table-hover)
  - [ ] Buttons → Bootstrap buttons (btn-primary, btn-success, etc.)
  - [ ] Pagination → Bootstrap pagination
  - [ ] Modals for confirmations
  - [ ] Badges for status indicators
- [ ] Update JavaScript to work with Bootstrap
  - [ ] Replace custom jQuery with Bootstrap JS where possible
  - [ ] Use Bootstrap tooltips, popovers, dropdowns
- [ ] Mobile-first responsive design
- [ ] Consistent color scheme and branding

### **Phase 6: Testing & Polish (Week 5-6)**
- [ ] Write unit tests for models
- [ ] Write integration tests for controllers
- [ ] Test all CRUD operations
- [ ] Test authentication & authorization
- [ ] Test scheduling conflicts
- [ ] Performance optimization
- [ ] Security review

### **Phase 7: Data Migration & Go-Live (Week 6)**
- [ ] Create data migration script from old DB to new DB
- [ ] Test with production data copy
- [ ] Update password hashes (if needed)
- [ ] Deploy to staging
- [ ] User acceptance testing
- [ ] Deploy to production
- [ ] Monitor and fix issues

---

## 🛠️ Technical Stack Changes

| Component | CakePHP 2.x | CakePHP 5.x |
|-----------|-------------|-------------|
| PHP Version | 5.3+ | 8.1+ |
| ORM | Model | Table + Entity |
| Query Builder | Array-based | Fluent query builder |
| Authentication | AuthComponent | Authentication plugin |
| Authorization | Custom isAuthorized() | **Authorization Plugin + Policies** |
| CSS Framework | Custom CSS | **Bootstrap 5.x** |
| Validation | Model::validate | Entity validation rules |
| Templates | .ctp | .php or .twig |
| Asset Management | Manual | AssetCompress plugin (recommended) |
| Migrations | Manual SQL | Phinx/CakePHP Migrations |
| Testing | PHPUnit 3.7 | PHPUnit 10+ |

---

## 📁 New Directory Structure

```
Planejamento5/
├── src/
│   ├── Controller/
│   │   ├── AppController.php
│   │   ├── PlanejamentosController.php
│   │   ├── DocentesController.php
│   │   ├── DisciplinasController.php
│   │   ├── SalasController.php
│   │   ├── HorariosController.php
│   │   ├── DiasController.php
│   │   ├── EmentasController.php
│   │   ├── OptativasController.php
│   │   ├── ConfiguraplanejamentosController.php
│   │   └── UsuarioplanejamentosController.php
│   ├── Model/
│   │   ├── Table/
│   │   │   ├── PlanejamentosTable.php
│   │   │   ├── DocentesTable.php
│   │   │   └── ... (8 more)
│   │   └── Entity/
│   │       ├── Planejamento.php
│   │       ├── Docente.php
│   │       └── ... (8 more)
│   ├── Template/  (or templates/)
│   │   ├── layout/
│   │   │   └── default.php
│   │   ├── Planejamentos/
│   │   │   ├── index.php
│   │   │   ├── listar.php
│   │   │   ├── add.php
│   │   │   ├── edit.php
│   │   │   └── view.php
│   │   └── ... (other controllers)
│   └── View/
│       └── AppView.php
├── config/
│   ├── app_local.php
│   ├── routes.php
│   └── Migrations/
│       ├── 20260611000001_CreateUsers.php
│       ├── 20260611000002_CreateDias.php
│       └── ... (8 more)
└── tests/
    ├── TestCase/
    │   ├── Controller/
    │   └── Model/
    └── Fixture/
```

---

## 🔐 Security Improvements in CakePHP 5

1. **CSRF Protection** - Enabled by default
2. **Password Hashing** - bcrypt/argon2 instead of SHA1
3. **SQL Injection** - Better query builder protection
4. **XSS Protection** - Auto-escaping in templates
5. **Form Tampering** - Form protection component
6. **HTTPS** - Easier enforcement
7. **Authorization Policies** - Granular, maintainable access control

---

## 🎨 UI Improvements with Bootstrap 5

### **Why Bootstrap 5?**
- ✅ No jQuery dependency (vanilla JavaScript)
- ✅ Modern, responsive design out-of-the-box
- ✅ Consistent component library
- ✅ Mobile-first approach
- ✅ Extensive documentation and community
- ✅ Easy to customize with CSS variables

### **Bootstrap Components to Use:**
- **Navbar** - Main navigation menu
- **Cards** - Display planning grids, teacher info, etc.
- **Tables** - Schedule tables with striping and hover
- **Badges** - Status indicators (turno, período)
- **Alerts** - Flash messages (success, danger, warning, info)
- **Buttons** - Consistent action buttons
- **Forms** - Clean, validated form controls
- **Modals** - Confirmation dialogs
- **Tabs** - Organize complex views (Diurno/Noturno)
- **Grid System** - Responsive layouts

### **Color Scheme (ESS/UFRJ Branding):**
```css
:root {
  --primary-color: #003366;    /* UFRJ Blue */
  --secondary-color: #0066cc;  /* Lighter Blue */
  --accent-color: #ff6600;     /* Orange accent */
  --success-color: #28a745;
  --danger-color: #dc3545;
  --warning-color: #ffc107;
}
```

### **Authorization Policies Structure:**

**Example: PlanejamentoPolicy.php**
```php
namespace App\Policy;

use App\Model\Entity\Planejamento;
use Authorization\IdentityInterface;

class PlanejamentoPolicy
{
    // Index/Listar - Anyone can view
    public function canIndex(IdentityInterface $user): bool
    {
        return true;
    }

    // View - Anyone can view
    public function canView(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return true;
    }

    // Add - Must be logged in
    public function canAdd(IdentityInterface $user): bool
    {
        return $user !== null;
    }

    // Edit - Admin or Editor
    public function canEdit(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }

    // Delete - Admin only
    public function canDelete(IdentityInterface $user, Planejamento $planejamento): bool
    {
        return $user->role === 'admin';
    }

    // Clone - Admin only
    public function canClone(IdentityInterface $user): bool
    {
        return $user->role === 'admin';
    }

    // Create version - Admin or Editor
    public function canCreateVersion(IdentityInterface $user): bool
    {
        return in_array($user->role, ['admin', 'editor']);
    }
}
```

**Example: ConfiguraplanejamentoPolicy.php**
```php
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

    public function canEdit(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        // User must be owner or admin
        return $user->role === 'admin' || $config->usuarioplanejamento_id === $user->id;
    }

    public function canDelete(IdentityInterface $user, Configuraplanejamento $config): bool
    {
        // Only admin can delete, and only if no planejamentos associated
        return $user->role === 'admin';
    }
}
```

**Using Policies in Controllers:**
```php
// In PlanejamentosController.php
public function add()
{
    $planejamento = $this->Planejamentos->newEmptyEntity();
    
    // Check authorization
    $this->Authorization->authorize($planejamento, 'add');
    
    if ($this->request->is('post')) {
        $planejamento = $this->Planejamentos->patchEntity($planejamento, $this->request->getData());
        if ($this->Planejamentos->save($planejamento)) {
            $this->Flash->success(__('Planning created.'));
            return $this->redirect(['action' => 'listar']);
        }
        $this->Flash->error(__('Could not create planning.'));
    }
    $this->set(compact('planejamento'));
}

public function edit($id = null)
{
    $planejamento = $this->Planejamentos->get($id);
    
    // Check authorization
    $this->Authorization->authorize($planejamento, 'edit');
    
    // ... rest of the code
}
```

---

## ⚡ Performance Improvements

1. **PHP 8.1+** - JIT compiler, union types, enums
2. **Better Caching** - Improved cache engine
3. **Query Optimization** - Lazy loading, containable
4. **PSR-7** - HTTP message interface standard
5. **Middleware** - Better request pipeline

---

## 🎯 Quick Wins (Start Here)

These are the easiest to migrate and will give you momentum:

1. **DiasController** - Simple CRUD, no complex logic
2. **HorariosController** - Simple CRUD
3. **SalasController** - Simple CRUD + tabela view
4. **OptativasController** - Simple CRUD

Then move to medium complexity:
5. **DocentesController** - Filtering logic
6. **DisciplinasController** - Filtering logic
7. **EmentasController** - Relationships

Finally tackle the complex ones:
8. **ConfiguraplanejamentosController** - Versioning logic
9. **PlanejamentosController** - Core business logic (most complex)
10. **UsuarioplanejamentosController** - Authentication

---

## 📝 Next Steps

1. **Review this plan** and adjust timeline as needed
2. **Backup your current database** before starting
3. **Set up development environment** with PHP 8.1+
4. **Start with Phase 1** - Database configuration and migrations
5. **Use Bake tool** to generate initial CRUD: 
   ```bash
   bin/cake bake all Dias
   bin/cake bake all Horarios
   ```

---

## 💡 Tips for Migration

- **Don't copy-paste code** - Rewrite following CakePHP 5 patterns
- **Use CakePHP Bake** - It generates modern code structure
- **Test frequently** - After each controller migration
- **Keep old app running** - Reference it during migration
- **Use Git branches** - One branch per phase
- **Document changes** - Keep notes on business logic quirks
- **Upgrade incrementally** - Don't try to do everything at once

---

## 🆘 Common Issues & Solutions

| Issue | Solution |
|-------|----------|
| `find('list')` syntax changed | Use `->find('list')->select(['id', 'name'])` |
| Session not working | Use `$this->request->getSession()` |
| Auth not working | Install authentication plugin |
| Pagination broken | Use `$this->paginate($query)` |
| Validation rules | Move from Model to Entity |
| `$this->data` | Replace with `$this->request->getData()` |
| Flash messages | Use `$this->Flash->success()` |
| Named parameters | Use query params: `$this->request->getQuery()` |

---

**Migration Estimated Time:** 6-8 weeks (part-time)
**Complexity:** Medium-High (due to complex scheduling logic)
**Risk Level:** Medium (well-tested migration path exists)

Good luck with your migration! 🚀
