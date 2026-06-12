# Quick Database & Bake Troubleshooting

## The Issue:
Baking is not creating the files. Let's debug step by step.

---

## **Step 1: Check Database Connection**

```bash
cd /home/luis/html/Planejamento5

# Test database connection
php bin/cake.php connection_list
```

**If you see an error**, your database is not configured!

**Fix:** Edit `config/app_local.php` and add your MySQL credentials:

```php
'Datasources' => [
    'default' => [
        'host' => 'localhost',
        'username' => 'YOUR_USERNAME',
        'password' => 'YOUR_PASSWORD',
        'database' => 'ess_apps',
    ],
],
```

---

## **Step 2: Check If Tables Exist**

```bash
# Check tables (non-interactive, no password prompt)
mysql -u root ess_apps -e "SHOW TABLES;" 2>&1 | grep -E "dias|horarios|salas"
```

**If NO output** = tables don't exist!

**Fix:** Create the tables:

```bash
cd /home/luis/html/Planejamento5

# Create migration
php bin/cake.php bake migration CreateDias dia:string[50] ordem:integer created modified
php bin/cake.php bake migration CreateHorarios horario:string[50] ordem:integer created modified  
php bin/cake.php bake migration CreateSalas sala:string[100] created modified

# Run migration
php bin/cake.php migrations migrate
```

---

## **Step 3: Clear All Caches**

```bash
cd /home/luis/html/Planejamento5

php bin/cake.php cache clear_all
php bin/cake.php orm_cache clear
```

---

## **Step 4: Try Bake Again**

```bash
cd /home/luis/html/Planejamento5

# Try this command
php bin/cake.php bake all Dias
```

**Watch the output carefully!** It will tell you what's wrong.

---

## **Common Errors:**

### Error: "Unknown database 'ess_apps'"
**Fix:** Create the database
```bash
mysql -u root -e "CREATE DATABASE ess_apps CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### Error: "Access denied for user"
**Fix:** Check username/password in `config/app_local.php`

### Error: "Table 'dias' doesn't exist"
**Fix:** Create the table (Step 2 above)

### Error: Nothing happens / hangs
**Fix:** Check PHP and MySQL are running
```bash
php -v
mysql -V
ps aux | grep mysql
```

---

## **Alternative: Create Files Manually**

If bake still doesn't work, tell me and I'll create ALL the files manually for you!

---

**Run Step 1 and tell me what you see!** 🔍
