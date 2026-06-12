# Manual Setup - Dias, Horarios, Salas

## The baking didn't work. Let's do it manually!

### **Step 1: Create Directories**

```bash
cd /home/luis/html/Planejamento5

# Create directories
mkdir -p src/Model/Table
mkdir -p src/Model/Entity
mkdir -p src/Controller
mkdir -p templates/Dias
mkdir -p templates/Horarios
mkdir -p templates/Salas
```

---

### **Step 2: Try Baking Again (One at a Time)**

```bash
cd /home/luis/html/Planejamento5

# Bake Dias
bin/cake bake model Dias
bin/cake bake controller Dias
bin/cake bake template Dias

# If Dias works, continue with others
bin/cake bake model Horarios
bin/cake bake controller Horarios
bin/cake bake template Horarios

bin/cake bake model Salas
bin/cake bake controller Salas
bin/cake bake template Salas
```

---

### **Step 3: If Baking Still Fails - Check Database**

```bash
# Check if tables exist
mysql -u root -p -e "USE ess_apps; SHOW TABLES LIKE 'dias';"
mysql -u root -p -e "USE ess_apps; SHOW TABLES LIKE 'horarios';"
mysql -u root -p -e "USE ess_apps; SHOW TABLES LIKE 'salas';"
```

**If tables don't exist, create them:**

```bash
cd /home/luis/html/Planejamento5

# Create migration for Dias
bin/cake bake migration CreateDias dia:string[50] ordem:integer created modified

# Create migration for Horarios
bin/cake bake migration CreateHorarios horario:string[50] ordem:integer created modified

# Create migration for Salas
bin/cake bake migration CreateSalas sala:string[100] created modified

# Run migrations
bin/cake migrations migrate
```

---

### **Step 4: Verify Files Created**

```bash
# Check if files exist
ls -la src/Model/Table/DiasTable.php
ls -la src/Model/Entity/Dia.php
ls -la src/Controller/DiasController.php
ls -la templates/Dias/
```

---

### **Step 5: Try Again with Debug**

```bash
cd /home/luis/html/Planejamento5

# Run bake with verbose output
bin/cake bake model Dias -v
```

This will show you what's going wrong!

---

## 🐛 **Common Issues:**

### **Issue 1: Database not configured**
**Fix:** Edit `config/app_local.php` and add database credentials

### **Issue 2: Tables don't exist**
**Fix:** Create migrations and run them (Step 3 above)

### **Issue 3: Permission denied**
**Fix:** Make sure you own the directory
```bash
sudo chown -R luis:luis /home/luis/html/Planejamento5
```

### **Issue 4: PHP errors**
**Fix:** Check PHP version
```bash
php -v  # Should be 8.1+
```

---

## ✅ **Quick Test:**

After fixing, test if model works:

```bash
cd /home/luis/html/Planejamento5
bin/cake orm_cache clear
bin/cake server
```

Then visit: http://localhost:8765/dias

---

**Try running bake with `-v` flag and tell me what error you see!** 🔍
