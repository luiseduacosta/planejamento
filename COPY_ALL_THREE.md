# Copy ALL Files - Dias, Horarios, Salas

## ✅ All Files Created!

### **Copy All at Once:**

```bash
cd /home/luis/html/planejamento

# Create directories
mkdir -p /home/luis/html/Planejamento5/src/Model/Table
mkdir -p /home/luis/html/Planejamento5/src/Model/Entity
mkdir -p /home/luis/html/Planejamento5/src/Controller
mkdir -p /home/luis/html/Planejamento5/templates/Dias
mkdir -p /home/luis/html/Planejamento5/templates/Horarios
mkdir -p /home/luis/html/Planejamento5/templates/Salas

# ==================== DIAS ====================
cp MANUAL_DiasTable.php /home/luis/html/Planejamento5/src/Model/Table/DiasTable.php
cp MANUAL_Dia.php /home/luis/html/Planejamento5/src/Model/Entity/Dia.php
cp MANUAL_DiasController_FIXED.php /home/luis/html/Planejamento5/src/Controller/DiasController.php
cp MANUAL_Dias_index.php /home/luis/html/Planejamento5/templates/Dias/index.php
cp MANUAL_Dias_view.php /home/luis/html/Planejamento5/templates/Dias/view.php
cp MANUAL_Dias_add.php /home/luis/html/Planejamento5/templates/Dias/add.php
cp MANUAL_Dias_edit.php /home/luis/html/Planejamento5/templates/Dias/edit.php

echo "✅ Dias files copied"

# ==================== HORARIOS ====================
cp MANUAL_HorariosTable.php /home/luis/html/Planejamento5/src/Model/Table/HorariosTable.php
cp MANUAL_Horario.php /home/luis/html/Planejamento5/src/Model/Entity/Horario.php
cp MANUAL_HorariosController.php /home/luis/html/Planejamento5/src/Controller/HorariosController.php
cp MANUAL_Horarios_index.php /home/luis/html/Planejamento5/templates/Horarios/index.php
cp MANUAL_Horarios_view.php /home/luis/html/Planejamento5/templates/Horarios/view.php
cp MANUAL_Horarios_add.php /home/luis/html/Planejamento5/templates/Horarios/add.php
cp MANUAL_Horarios_edit.php /home/luis/html/Planejamento5/templates/Horarios/edit.php

echo "✅ Horarios files copied"

# ==================== SALAS ====================
cp MANUAL_SalasTable.php /home/luis/html/Planejamento5/src/Model/Table/SalasTable.php
cp MANUAL_Sala.php /home/luis/html/Planejamento5/src/Model/Entity/Sala.php
cp MANUAL_SalasController.php /home/luis/html/Planejamento5/src/Controller/SalasController.php
cp MANUAL_Salas_index.php /home/luis/html/Planejamento5/templates/Salas/index.php
cp MANUAL_Salas_view.php /home/luis/html/Planejamento5/templates/Salas/view.php
cp MANUAL_Salas_add.php /home/luis/html/Planejamento5/templates/Salas/add.php
cp MANUAL_Salas_edit.php /home/luis/html/Planejamento5/templates/Salas/edit.php

echo "✅ Salas files copied"

echo ""
echo "=== ALL FILES COPIED! ==="
echo "Clear cache and test:"
echo "  cd /home/luis/html/Planejamento5"
echo "  bin/cake cache clear_all"
echo ""
echo "Test at:"
echo "  http://localhost:8765/dias"
echo "  http://localhost:8765/horarios"
echo "  http://localhost:8765/salas"
```

---

## 📋 **What Was Created:**

### **Dias (Days):**
- ✅ DiasTable.php - Model with validation
- ✅ Dia.php - Entity
- ✅ DiasController.php - CRUD with auth (public index/view)
- ✅ 4 Views (index, view, add, edit)

### **Horarios (Time Slots):**
- ✅ HorariosTable.php - Model with validation
- ✅ Horario.php - Entity
- ✅ HorariosController.php - CRUD with auth (public index/view)
- ✅ 4 Views (index, view, add, edit)

### **Salas (Rooms):**
- ✅ SalasTable.php - Model with validation
- ✅ Sala.php - Entity
- ✅ SalasController.php - CRUD with auth (public index/view)
- ✅ 4 Views (index, view, add, edit)

---

## 🔐 **Authentication:**

All three controllers have:
- ✅ **Public access**: index, view (no login required)
- ✅ **Protected**: add, edit, delete (login required)
- ✅ **Authorization**: Uses policy classes we created

---

## 🎨 **Features:**

- ✅ Bootstrap 5 styling
- ✅ Portuguese labels and messages
- ✅ Pagination
- ✅ Flash messages
- ✅ Form validation
- ✅ Delete confirmations
- ✅ Responsive design

---

## ✅ **After Copying:**

```bash
cd /home/luis/html/Planejamento5
bin/cake cache clear_all
```

**Then test all three:**
- http://localhost:8765/dias
- http://localhost:8765/horarios
- http://localhost:8765/salas

**Login as admin to add/edit/delete!**
