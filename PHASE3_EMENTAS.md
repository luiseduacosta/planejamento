# Phase 3: Copy Ementas

## ✅ Ementas Files Created!

### **Copy Ementas:**

```bash
cd /home/luis/html/planejamento

# Create directory
mkdir -p /home/luis/html/Planejamento5/templates/Ementas

# Copy Ementas files
cp MANUAL_EmentasTable.php /home/luis/html/Planejamento5/src/Model/Table/EmentasTable.php
cp MANUAL_Ementa.php /home/luis/html/Planejamento5/src/Model/Entity/Ementa.php
cp MANUAL_EmentasController.php /home/luis/html/Planejamento5/src/Controller/EmentasController.php
cp MANUAL_Ementas_index.php /home/luis/html/Planejamento5/templates/Ementas/index.php
cp MANUAL_Ementas_view.php /home/luis/html/Planejamento5/templates/Ementas/view.php
cp MANUAL_Ementas_add.php /home/luis/html/Planejamento5/templates/Ementas/add.php
cp MANUAL_Ementas_edit.php /home/luis/html/Planejamento5/templates/Ementas/edit.php

# Clear cache
cd /home/luis/html/Planejamento5
bin/cake cache clear_all

echo "✅ Ementas copied!"
echo "Test at: http://localhost:8765/ementas"
```

---

## 📋 **Ementas Features:**

- ✅ **BelongsTo** Disciplinas relationship
- ✅ Fields: disciplina_id, conteudo_programatico, objetivos, bibliografia_basica, bibliografia_complementar
- ✅ Dropdown to select discipline
- ✅ Textareas for long content
- ✅ Public index/view
- ✅ Protected add/edit/delete
- ✅ Uses EmentaPolicy

---

## 🎯 **Migration Progress:**

### **✅ COMPLETED:**

**Phase 1 - Simple CRUD:**
- ✅ Dias
- ✅ Horarios
- ✅ Salas

**Phase 2 - Medium Complexity:**
- ✅ Docentes
- ✅ Disciplinas
- ✅ Optativas

**Phase 3 - Complex:**
- ✅ Ementas

---

## 🚀 **Remaining Modules:**

**Still to migrate:**
- ⏳ Configuraplanejamentos (semester configs - complex!)
- ⏳ Planejamentos (main planning - MOST complex!)

These two are the heart of the application with:
- Multiple relationships
- Version control (clone semesters)
- Complex business logic
- Owner-based authorization

---

**Copy Ementas and test!** Then let me know if you want to tackle Configuraplanejamentos next! 🎯
