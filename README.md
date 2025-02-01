# PinkieIt

PinkieItは、Laravelを使用したWebアプリケーションです。このREADMEでは、プロジェクトのセットアップと実行方法について説明します。

## 必要条件

- Docker 20.10.13以上
- Docker Compose v2.17.2以上

## Dockerのインストール

Dockerがインストールされていない場合は、以下の手順でインストールしてください：

1. Dockerをインストールします：
   ```bash
   curl -fsSL https://get.docker.com | sudo sh
   ```
   ※ 環境に応じて公式ドキュメントの最新手順を確認してください

2. 現在のユーザーをdockerグループに追加します：
   ```bash
   sudo usermod -aG docker $USER
   newgrp docker  # グループ変更を即時反映
   ```

3. インストールを確認します：
   ```bash
   docker --version
   docker compose version  # Compose v2を確認
   ```

## セットアップ手順

1. リポジトリをクローンします：
   ```bash
   git clone git@github.com:w-pinkietech/pinkieit.git
   cd pinkieit
   ```

2. セットアップスクリプトを実行：
   ```bash
   chmod +x setup.sh
   ./setup.sh  # .envファイル生成と設定確認
   ```
   ※ DBパスワードは12文字以上の英数字記号を推奨

3. Dockerコンテナをビルド＆起動：
   - 初回またはDockerfile変更時
   ```bash
   docker compose up -d --build
   ```
   - 通常起動時（2回目以降）
   ```bash
   docker compose up -d
   ```

4. 依存関係インストールとデータベース移行：
   ```bash
   docker compose exec web-app composer install
   docker compose exec web-app php artisan migrate
   ```

5. （オプション）テストデータ投入：
   ```bash
   docker compose exec web-app php artisan db:seed
   ```

6. （オプション）管理者ユーザー作成：
   ```bash
   docker compose exec web-app php artisan make:user admin \
     admin@example.com 'StrongP@ssw0rd!'
   ```
   ※ パスワードはシングルクォートで囲んでください

## 使用方法

- ローカルアクセス：http://localhost:18080
- リモートアクセス：http://<サーバーIP>:18080

※ ファイアウォール設定でポート18080と6001（WebSocket）を開放してください

## 開発

### 環境変数
`.env`ファイルの主な設定項目：
```ini
# 内部WebSocketサーバー設定（外部Pusherサービス不要）
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=local_pinkieit
PUSHER_APP_KEY=local_key
PUSHER_APP_SECRET=local_secret
PUSHER_HOST=websocket
PUSHER_PORT=6001
```

### 常用コマンド
アプリケーションログ監視：
```bash
docker compose logs -f web-app  # 複数サービス指定可能
```

MQTTメッセージ監視：
```bash
docker compose exec mqtt mosquitto_sub -h mqtt -p 1883 -t 'production/#'
```

リアルタイムリソース監視：
```bash
docker compose stats  # 全コンテナのCPU/Memory使用量表示
```

## トラブルシューティング

キャッシュ問題が疑われる場合：
```bash
docker compose exec web-app php artisan optimize:clear
docker compose exec web-app php artisan route:cache
docker compose exec web-app php artisan config:cache
```

コンテナ再構築（根本解決が必要な場合）：
```bash
docker compose down -v --remove-orphans
docker compose up -d --build
```

## ライセンス

[Apache License 2.0](LICENSE)