# Phase 2: Docentes, Disciplinas, Optativas

## ✅ Docentes Files Created!

### **Copy Docentes:**

```bash
cd /home/luis/html/planejamento

# Create directory
mkdir -p /home/luis/html/Planejamento5/src/Model/Table
mkdir -p /home/luis/html/Planejamento5/src/Model/Entity
mkdir -p /home/luis/html/Planejamento5/src/Controller
mkdir -p /home/luis/html/Planejamento5/templates/Docentes

# Copy Docentes files
cp MANUAL_DocentesTable.php /home/luis/html/Planejamento5/src/Model/Table/DocentesTable.php
cp MANUAL_Docente.php /home/luis/html/Planejamento5/src/Model/Entity/Docente.php
cp MANUAL_DocentesController.php /home/luis/html/Planejamento5/src/Controller/DocentesController.php
cp MANUAL_Docentes_index.php /home/luis/html/Planejamento5/templates/Docentes/index.php
cp MANUAL_Docentes_view.php /home/luis/html/Planejamento5/templates/Docentes/view.php
cp MANUAL_Docentes_add.php /home/luis/html/Planejamento5/templates/Docentes/add.php
cp MANUAL_Docentes_edit.php /home/luis/html/Planejamento5/templates/Docentes/edit.php

# Clear cache
cd /home/luis/html/Planejamento5
bin/cake cache clear_all

echo "✅ Docentes copied successfully!"
echo "Test at: http://localhost:8765/docentes"
```

---

## 📋 **Docentes Features:**

- ✅ Fields: nome, titulo, departamento, email
- ✅ HasMany relationship with Planejamentos
- ✅ Public index/view (no login required)
- ✅ Protected add/edit/delete (login required)
- ✅ Uses DocentePolicy for authorization
- ✅ Bootstrap 5 styled views
- ✅ Portuguese labels

---

## 🚀 **Next: Disciplinas & Optativas**

After testing Docentes, let me know and I'll create:
- **Disciplinas** (Disciplines)
- **Optativas** (Electives)

Both will follow the same pattern!

---

**Copy Docentes files and test!** 🎯
