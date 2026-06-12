# Fix Security Salt - Quick Command

## Run this command in your terminal:

```bash
cd /home/luis/html/Planejamento5 && php -r "\$salt = bin2hex(random_bytes(32)); \$file = 'config/app_local.php'; \$content = file_get_contents(\$file); \$content = str_replace('__SALT__', \$salt, \$content); file_put_contents(\$file, \$content); echo \"✅ Salt updated: \$salt\\n\";"
```

---

## Or use the PHP script I created:

```bash
cd /home/luis/html/planejamento
php fix_salt.php
```

---

## Manual Method (if scripts don't work):

1. Open: `/home/luis/html/Planejamento5/config/app_local.php`
2. Find line 31: `'salt' => env('SECURITY_SALT', '__SALT__'),`
3. Replace `__SALT__` with: `8ae77209d61651d92f799bbb10d9880ae9042f96e856bfa1451a23ee0218a5c5`
4. Save the file

**Example after change:**
```php
'Security' => [
    'salt' => env('SECURITY_SALT', '8ae77209d61651d92f799bbb10d9880ae9042f96e856bfa1451a23ee0218a5c5'),
],
```

---

## Verify it worked:

After updating, restart your server:

```bash
cd /home/luis/html/Planejamento5
bin/cake server
```

The security warning should be gone! ✅
