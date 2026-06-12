# Quick Fix for 403 Forbidden on Login

## The Issue:
CSRF middleware is blocking the login form POST request with a 403 error.

## Quick Fix (2 minutes):

### **Step 1: Edit Application.php**

Open: `/home/luis/html/Planejamento5/src/Application.php`

### **Step 2: Find and Comment Out CSRF (lines ~91-95)**

Look for this code:
```php
// Cross Site Request Forgery (CSRF) Protection Middleware
// https://book.cakephp.org/5/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
->add(new CsrfProtectionMiddleware([
    'httponly' => true,
]));
```

**Change it to:**
```php
// Cross Site Request Forgery (CSRF) Protection Middleware
// https://book.cakephp.org/5/en/security/csrf.html#cross-site-request-forgery-csrf-middleware
// TEMPORARILY DISABLED FOR DEVELOPMENT
//->add(new CsrfProtectionMiddleware([
//    'httponly' => true,
//]));
```

### **Step 3: Clear Cache & Restart**

```bash
cd /home/luis/html/Planejamento5
bin/cake cache clear_all
bin/cake server
```

### **Step 4: Test Login**

1. Visit: http://localhost:8765/login
2. Enter: admin / admin123
3. Should work now! ✅

---

## ⚠️ IMPORTANT:

This is **ONLY for development/testing**! 

**Before going to production, you MUST:**
1. Uncomment the CSRF middleware
2. Fix the CSRF token issue properly
3. Test with CSRF enabled

---

## Why This Happens:

The CSRF middleware requires a valid CSRF token cookie, but something is preventing it from being set properly. This can happen when:
- Browser blocks third-party cookies
- Session configuration issue
- Cookie domain/path mismatch

Disabling it temporarily lets you test the authentication flow.
