# Step 7: Copy All Policy Classes

## ✅ All Policy Files Created!

### **Copy all policies at once:**

```bash
# Create Policy directory
mkdir -p /home/luis/html/Planejamento5/src/Policy

# Copy all policy files
cd /home/luis/html/planejamento

cp STEP7_PlanejamentoPolicy.php /home/luis/html/Planejamento5/src/Policy/PlanejamentoPolicy.php
cp STEP7_DocentePolicy.php /home/luis/html/Planejamento5/src/Policy/DocentePolicy.php
cp STEP7_DisciplinaPolicy.php /home/luis/html/Planejamento5/src/Policy/DisciplinaPolicy.php
cp STEP7_SalaPolicy.php /home/luis/html/Planejamento5/src/Policy/SalaPolicy.php
cp STEP7_HorarioPolicy.php /home/luis/html/Planejamento5/src/Policy/HorarioPolicy.php
cp STEP7_DiaPolicy.php /home/luis/html/Planejamento5/src/Policy/DiaPolicy.php
cp STEP7_EmentaPolicy.php /home/luis/html/Planejamento5/src/Policy/EmentaPolicy.php
cp STEP7_OptativaPolicy.php /home/luis/html/Planejamento5/src/Policy/OptativaPolicy.php
cp STEP7_ConfiguraplanejamentoPolicy.php /home/luis/html/Planejamento5/src/Policy/ConfiguraplanejamentoPolicy.php
cp STEP7_UserPolicy.php /home/luis/html/Planejamento5/src/Policy/UserPolicy.php
```

---

## 📋 **Policy Summary:**

| Policy | View | Add/Edit | Delete | Special |
|--------|------|----------|--------|---------|
| **Planejamento** | All | Admin, Editor | Admin | Clone: Admin only |
| **Docente** | All | Admin, Editor | Admin | - |
| **Disciplina** | All | Admin, Editor | Admin | - |
| **Sala** | All | Admin, Editor | Admin | - |
| **Horario** | All | Admin only | Admin only | - |
| **Dia** | All | Admin only | Admin only | - |
| **Ementa** | All | Admin, Editor | Admin | - |
| **Optativa** | All | Admin, Editor | Admin | - |
| **Configuraplanejamento** | All | Logged in | Admin | Edit: Owner or Admin, Clone: Admin |
| **User** | Admin/Self | Admin only | Admin | Edit: Admin/Self, Can't delete self |

---

## 🔐 **Authorization Rules:**

### **Admin Role:**
- ✅ Full access to everything
- ✅ Can clone semesters
- ✅ Can delete any record
- ✅ Can manage users

### **Editor Role:**
- ✅ Can view all public data
- ✅ Can add/edit planejamentos, docentes, disciplinas, ementas, salas, optativas
- ❌ Cannot delete records
- ❌ Cannot clone semesters
- ❌ Cannot manage users
- ❌ Cannot modify dias/horarios (system data)

### **Guest (Not Logged In):**
- ✅ Can view index/listar pages
- ✅ Can view individual records
- ❌ Cannot add, edit, or delete anything

---

## ✅ **After Copying:**

Verify all policies are in place:

```bash
ls -la /home/luis/html/Planejamento5/src/Policy/
```

You should see **10 policy files**!

---

## 🎉 **Next Step:**

Once all policies are copied, we're ready to start migrating the actual models and controllers!

**Ready to continue?** We can start baking the first model (Dias or Horarios)! 🚀
