# Copy All Manual Files - Dias, Horarios, Salas

## ✅ All Files Created!

### **Copy Dias Files:**

```bash
cd /home/luis/html/planejamento

# Create directories
mkdir -p /home/luis/html/Planejamento5/src/Model/Table
mkdir -p /home/luis/html/Planejamento5/src/Model/Entity
mkdir -p /home/luis/html/Planejamento5/src/Controller
mkdir -p /home/luis/html/Planejamento5/templates/Dias

# Copy Dias Model
cp MANUAL_DiasTable.php /home/luis/html/Planejamento5/src/Model/Table/DiasTable.php
cp MANUAL_Dia.php /home/luis/html/Planejamento5/src/Model/Entity/Dia.php

# Copy Dias Controller
cp MANUAL_DiasController.php /home/luis/html/Planejamento5/src/Controller/DiasController.php

# Copy Dias Views
cp MANUAL_Dias_index.php /home/luis/html/Planejamento5/templates/Dias/index.php
cp MANUAL_Dias_view.php /home/luis/html/Planejamento5/templates/Dias/view.php
cp MANUAL_Dias_add.php /home/luis/html/Planejamento5/templates/Dias/add.php
cp MANUAL_Dias_edit.php /home/luis/html/Planejamento5/templates/Dias/edit.php
```

---

## 📝 **What Was Created:**

### **Dias Model:**
- ✅ `DiasTable.php` - Table class with validation
- ✅ `Dia.php` - Entity class

### **Dias Controller:**
- ✅ `DiasController.php` - Full CRUD with authorization
  - index() - List all dias
  - view() - View single dia
  - add() - Create new dia
  - edit() - Update dia
  - delete() - Delete dia

### **Dias Views:**
- ✅ `index.php` - List with pagination (Bootstrap styled)
- ✅ `view.php` - Detail view
- ✅ `add.php` - Create form
- ✅ `edit.php` - Edit form

---

## 🎨 **Features:**

- ✅ Bootstrap 5 styling
- ✅ Portuguese labels and messages
- ✅ Authorization checks (using policies)
- ✅ Flash messages
- ✅ Pagination
- ✅ Form validation
- ✅ Delete confirmation

---

## 🚀 **Next Steps:**

After copying Dias files, I'll create the same for **Horarios** and **Salas**!

**Copy the Dias files first and test:**
```
http://localhost:8765/dias
```

**Let me know when ready and I'll create Horarios and Salas!** 🎯
