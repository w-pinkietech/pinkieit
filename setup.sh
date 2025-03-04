#!/bin/bash

# .env ファイルの存在確認
if [ -f .env ]; then
    echo ".env file already exists. Skipping setup."
    exit 0
fi

# .env ファイルのコピー
cp .env.example .env

# ユーザーに入力を促す
read -p "Enter the database username (default: pinkieit): " db_username
read -p "Enter the database password (minimum 8 characters): " db_password
while [[ ${#db_password} -lt 8 ]]; do
    db_password=$(tr -dc 'A-Za-z0-9!?%=' < /dev/urandom | head -c 12)
    echo "I have generated a password for you: $db_password"
done
read -p "Enter the Pusher app ID (default: app-id): " pusher_app_id
read -p "Enter the Pusher app key (default: app-key): " pusher_app_key
read -p "Enter the Pusher app secret (default: app-secret): " pusher_app_secret

# .env ファイルの更新
[ -n "$db_username" ] && sed -i "s/DB_USERNAME=.*/DB_USERNAME=$db_username/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$db_password/" .env

# Pusher設定の更新（値が入力された場合のみ）
[ -n "$pusher_app_id" ] && sed -i "s/PUSHER_APP_ID=.*/PUSHER_APP_ID=$pusher_app_id/" .env
[ -n "$pusher_app_key" ] && sed -i "s/PUSHER_APP_KEY=.*/PUSHER_APP_KEY=$pusher_app_key/" .env
[ -n "$pusher_app_secret" ] && sed -i "s/PUSHER_APP_SECRET=.*/PUSHER_APP_SECRET=$pusher_app_secret/" .env

echo "Environment file has been set up in .env"
# if /workspace exists, devcontainer is running
if [ -d "/workspace" ]; then
    echo "Rebuild the devcontainer to apply changes."
else
    echo "Please run 'docker compose up -d --build' to start the containers."
fi
