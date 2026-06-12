# Step 4: Create Users Table Migration 🔐

## What to Do:

### **Run this command to create the migration:**

```bash
cd /home/luis/html/Planejamento5

bin/cake bake migration CreateUsers \
    username:string[50] \
    password:string[255] \
    role:string[20] \
    email:string[100] \
    created \
    modified
```

This will create a migration file in `config/Migrations/`

---

### **Then run the migration:**

```bash
bin/cake migrations migrate
```

This will create the `users` table in your database.

---

## What This Creates:

**Table: `users`**

| Column | Type | Description |
|--------|------|-------------|
| id | INT (auto) | Primary key |
| username | VARCHAR(50) | User's login name |
| password | VARCHAR(255) | Hashed password |
| role | VARCHAR(20) | User role (admin/editor) |
| email | VARCHAR(100) | User's email |
| created | DATETIME | Record creation time |
| modified | DATETIME | Last modification time |

---

## ⚠️ **Important: Database Configuration**

Before running the migration, make sure your database is configured!

**Edit:** `/home/luis/html/Planejamento5/config/app_local.php`

Update the database settings (around line 40-64):

```php
'Datasources' => [
    'default' => [
        'host' => 'localhost',
        'username' => 'root',        // Your DB username
        'password' => 'root',        // Your DB password
        'database' => 'planejamento5', // Database name
        // ... rest of config
    ],
],
```

**Then create the database:**

```bash
bin/cake migrations create_database
```

Or manually in MySQL:

```sql
CREATE DATABASE planejamento5 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Verification:

After running the migration, check if the table was created:

```bash
bin/cake orm_cache clear
mysql -u root -p -e "USE planejamento5; DESCRIBE users;"
```

You should see the table structure!

---

## Next Step:

After creating the users table, we'll proceed to:
- **Step 5: Create UsersController** (login/logout functionality)
- **Step 6: Create first admin user**
- **Step 7: Test authentication!**

Then you'll be able to **log in** to your application! 🎉
