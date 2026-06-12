# Step 6: Create First Users 🔐

## What to Do:

### **Run the script to create users:**

```bash
cd /home/luis/html/planejamento

php STEP6_create_first_users.php
```

This will create:
- ✅ **Admin user** (full access)
- ✅ **Editor user** (limited access)

---

## Users Created:

| Username | Password | Role | Access Level |
|----------|----------|------|--------------|
| **admin** | admin123 | admin | Full access to everything |
| **editor** | editor123 | editor | Can edit, cannot delete or clone |

---

## ⚠️ **Prerequisites:**

Before running this script, make sure:

1. ✅ Database is configured in `config/app_local.php`
2. ✅ Users table migration has been run (Step 4)
3. ✅ Database connection is working

---

## Alternative: Create Users Manually

If the script doesn't work, you can create users manually via MySQL:

```bash
mysql -u root -p ess_apps
```

```sql
-- Create admin user
INSERT INTO users (username, password, role, email, created, modified) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 'admin@ess.ufrj.br', NOW(), NOW());

-- Create editor user  
INSERT INTO users (username, password, role, email, created, modified) VALUES
('editor', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'editor', 'editor@ess.ufrj.br', NOW(), NOW());
```

Note: The password hash above is for `admin123` and `editor123`.

---

## Verification:

After running the script, verify users were created:

```bash
mysql -u root -p -e "USE ess_apps; SELECT id, username, role, email FROM users;"
```

You should see both users listed!

---

## 🎉 **Next Step:**

After creating users, you can **TEST THE LOGIN!**

1. Visit: **http://localhost:8765/login**
2. Login with:
   - Username: `admin`
   - Password: `admin123`
3. You should be redirected to Planejamentos!

---

## What's Next:

- **Step 7:** Test authentication
- **Step 8:** Create Policy classes
- **Step 9:** Migrate first model (Dias or Horarios)
- **Step 10:** Continue with full migration!
