# Step 2: Update AppController.php ✅

## What to Do:

**Copy the prepared file to replace your current AppController.php**

### Command:

```bash
cp /home/luis/html/planejamento/STEP2_AppController.php /home/luis/html/Planejamento5/src/Controller/AppController.php
```

---

## What This Does:

✅ Loads Flash component for messages  
✅ Loads FormProtection component for security  
✅ Loads Authentication component  
✅ Loads Authorization component  
✅ Configures public actions (no login required)  
✅ Sets up beforeFilter for authentication rules  

---

## Key Changes:

**Old initialize():**
```php
$this->loadComponent('Flash');
```

**New initialize():**
```php
$this->loadComponent('Flash');
$this->loadComponent('FormProtection');
$this->loadComponent('Authentication.Authentication');
$this->loadComponent('Authorization.Authorization');
```

**New beforeFilter() method:**
```php
public function beforeFilter(EventInterface $event)
{
    parent::beforeFilter($event);
    $this->Authentication->addUnauthenticatedActions([
        'display', // Pages controller (home page)
    ]);
}
```

---

## Verification:

After copying, test again:

```bash
cd /home/luis/html/Planejamento5
bin/cake server
```

Visit: http://localhost:8765

If the page still loads without errors, **Step 2 is successful!** ✅

---

## What's Next:

After completing Step 2, let me know and we'll proceed to:
- **Step 3: Create Bootstrap 5 Layout** (the visual design!)
