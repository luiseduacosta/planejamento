#!/bin/bash

# Generate a random 64-character salt
SALT=$(php -r "echo bin2hex(openssl_random_pseudo_bytes(32));")

# Replace __SALT__ with the generated salt in app_local.php
sed -i "s/__SALT__/$SALT/g" /home/luis/html/Planejamento5/config/app_local.php

echo "✅ Security salt has been updated!"
echo "New salt value: $SALT"
echo ""
echo "File updated: /home/luis/html/Planejamento5/config/app_local.php"
