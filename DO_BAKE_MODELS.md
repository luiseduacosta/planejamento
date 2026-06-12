# Step 8-10: Bake Dias, Horarios, and Salas

## ✅ Ready to Bake!

### **Run the baking script:**

```bash
cd /home/luis/html/planejamento
chmod +x bake_simple_models.sh
bash bake_simple_models.sh
```

---

## 📋 **What This Creates:**

For each model (Dias, Horarios, Salas):

### **1. Table Class** (src/Model/Table/)
- `DiasTable.php`
- `HorariosTable.php`
- `SalasTable.php`

### **2. Entity Class** (src/Model/Entity/)
- `Dia.php`
- `Horario.php`
- `Sala.php`

### **3. Controller** (src/Controller/)
- `DiasController.php`
- `HorariosController.php`
- `SalasController.php`

### **4. Views** (templates/)
- `templates/Dias/` (index, view, add, edit)
- `templates/Horarios/` (index, view, add, edit)
- `templates/Salas/` (index, view, add, edit)

---

## ⚠️ **Before Baking - Important!**

### **Make sure database tables exist!**

If you haven't migrated the data from the old app yet, you need to create these tables first:

**Option 1: Copy from old database**
```bash
# If using the same database (ess_apps), tables already exist!
# Skip to baking
```

**Option 2: Create migrations**
```bash
cd /home/luis/html/Planejamento5

# Create Dias table
./bin/cake bake migration CreateDias dia:string ordem:integer created modified

# Create Horarios table
./bin/cake bake migration CreateHorarios horario:string ordem:integer created modified

# Create Salas table
./bin/cake bake migration CreateSalas sala:string created modified

# Run migrations
./bin/cake migrations migrate
```

---

## 🧪 **After Baking - Test Each:**

### **1. Test Dias:**
```
Visit: http://localhost:8765/dias
- Should see list (empty if no data)
- Click "New Dia"
- Add a test record
- Try edit and delete
```

### **2. Test Horarios:**
```
Visit: http://localhost:8765/horarios
- Should see list
- Add, edit, delete test records
```

### **3. Test Salas:**
```
Visit: http://localhost:8765/salas
- Should see list
- Add, edit, delete test records
```

---

## 🔐 **Authorization:**

All three controllers will automatically use the policies we created:

- **View**: Anyone (even guests)
- **Add/Edit**: Admin or Editor
- **Delete**: Admin only

**Make sure you're logged in as admin to test add/edit/delete!**

---

## 🎨 **Bootstrap Styling:**

The baked views will use default CakePHP styling. To apply Bootstrap 5:

**We'll need to customize the bake templates OR manually update the views later.**

For now, the functionality will work - we can style it after!

---

## ✅ **Verification Checklist:**

After baking, verify:

- [ ] Dias CRUD works (add, view, edit, delete)
- [ ] Horarios CRUD works
- [ ] Salas CRUD works
- [ ] Authorization policies are enforced
- [ ] Flash messages appear
- [ ] Forms work correctly
- [ ] No errors in browser console

---

## 🎉 **Next Steps:**

After these three work perfectly:

1. **Migrate Docentes** (medium complexity)
2. **Migrate Disciplinas** (medium complexity)
3. **Migrate Ementas** (medium complexity)
4. **Migrate Configuraplanejamentos** (complex)
5. **Migrate Planejamentos** (most complex!)

---

**Run the bake script and let me know the results!** 🚀
