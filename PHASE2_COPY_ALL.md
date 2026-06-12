# Phase 2: Copy Docentes, Disciplinas, Optativas

## ✅ All Files Created!

### **Copy All Phase 2 Modules:**

```bash
cd /home/luis/html/planejamento

# Create directories
mkdir -p /home/luis/html/Planejamento5/src/Model/Table
mkdir -p /home/luis/html/Planejamento5/src/Model/Entity
mkdir -p /home/luis/html/Planejamento5/src/Controller
mkdir -p /home/luis/html/Planejamento5/templates/Docentes
mkdir -p /home/luis/html/Planejamento5/templates/Disciplinas
mkdir -p /home/luis/html/Planejamento5/templates/Optativas

# ==================== DOCENTES ====================
cp MANUAL_DocentesTable.php /home/luis/html/Planejamento5/src/Model/Table/DocentesTable.php
cp MANUAL_Docente.php /home/luis/html/Planejamento5/src/Model/Entity/Docente.php
cp MANUAL_DocentesController.php /home/luis/html/Planejamento5/src/Controller/DocentesController.php
cp MANUAL_Docentes_index.php /home/luis/html/Planejamento5/templates/Docentes/index.php
cp MANUAL_Docentes_view.php /home/luis/html/Planejamento5/templates/Docentes/view.php
cp MANUAL_Docentes_add.php /home/luis/html/Planejamento5/templates/Docentes/add.php
cp MANUAL_Docentes_edit.php /home/luis/html/Planejamento5/templates/Docentes/edit.php

echo "✅ Docentes copied"

# ==================== DISCIPLINAS ====================
cp MANUAL_DisciplinasTable.php /home/luis/html/Planejamento5/src/Model/Table/DisciplinasTable.php
cp MANUAL_Disciplina.php /home/luis/html/Planejamento5/src/Model/Entity/Disciplina.php
cp MANUAL_DisciplinasController.php /home/luis/html/Planejamento5/src/Controller/DisciplinasController.php
cp MANUAL_Disciplinas_index.php /home/luis/html/Planejamento5/templates/Disciplinas/index.php
cp MANUAL_Disciplinas_view.php /home/luis/html/Planejamento5/templates/Disciplinas/view.php
cp MANUAL_Disciplinas_add.php /home/luis/html/Planejamento5/templates/Disciplinas/add.php
cp MANUAL_Disciplinas_edit.php /home/luis/html/Planejamento5/templates/Disciplinas/edit.php

echo "✅ Disciplinas copied"

# ==================== OPTATIVAS ====================
cp MANUAL_OptativasTable.php /home/luis/html/Planejamento5/src/Model/Table/OptativasTable.php
cp MANUAL_Optativa.php /home/luis/html/Planejamento5/src/Model/Entity/Optativa.php
cp MANUAL_OptativasController.php /home/luis/html/Planejamento5/src/Controller/OptativasController.php
cp MANUAL_Optativas_index.php /home/luis/html/Planejamento5/templates/Optativas/index.php
cp MANUAL_Optativas_view.php /home/luis/html/Planejamento5/templates/Optativas/view.php
cp MANUAL_Optativas_add.php /home/luis/html/Planejamento5/templates/Optativas/add.php
cp MANUAL_Optativas_edit.php /home/luis/html/Planejamento5/templates/Optativas/edit.php

echo "✅ Optativas copied"

# Clear cache
cd /home/luis/html/Planejamento5
bin/cake cache clear_all

echo ""
echo "=== ALL PHASE 2 MODULES COPIED! ==="
echo "Test at:"
echo "  http://localhost:8765/docentes"
echo "  http://localhost:8765/disciplinas"
echo "  http://localhost:8765/optativas"
```

---

## 📊 **Phase 2 Summary:**

| Module | Fields | Relationships | Features |
|--------|--------|---------------|----------|
| **Docentes** | nome, titulo, departamento, email | HasMany Planejamentos | Email validation |
| **Disciplinas** | codigo, nome, carga_horaria, ementa | HasMany Planejamentos | Code + workload |
| **Optativas** | codigo, nome, carga_horaria, ementa | Independent | Elective courses |

---

## ✅ **All Have:**
- ✅ Bootstrap 5 styling
- ✅ Portuguese labels
- ✅ Public index/view
- ✅ Protected add/edit/delete
- ✅ Authorization policies
- ✅ Pagination
- ✅ Form validation

---

**Copy all files and test all three modules!** 🚀
