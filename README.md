# PinkieIt

[![js-semistandard-style](https://img.shields.io/badge/code%20style-semistandard-brightgreen.svg)](https://github.com/standard/semistandard)
[![SonarCloud](https://sonarcloud.io/images/project_badges/sonarcloud-white.svg)](https://sonarcloud.io/summary/new_code?id=w-pinkietech_pinkieit)

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

### VS Code Devcontainer / GitHub Codespaces

本プロジェクトはVS Code DevcontainerとGitHub Codespaces対応しています。以下の機能が利用可能です：

1. VS Code Devcontainerでの開発:
   - VS Codeで「Dev Containersで再度開く」を選択
   - 必要な拡張機能が自動的にインストール
   - Laravel開発用の設定が自動的に適用

2. GitHub Codespaces対応:
   - GitHubウェブサイトから「Code」→「Codespaces」→「新しいcodespace」
   - ブラウザ上でVS Code環境が利用可能
   - 自動的に開発環境が構築

3. Docker開発サポート:
   - コンテナ内でのDocker操作が可能
   - VS Code Docker拡張機能によるコンテナ管理
   - Docker Buildxとcompose v2対応

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

## SonarQube MCP Integration 🔧

このプロジェクトではSonarQube Model Context Protocol (MCP)統合により、Claude Codeが自動的にコード品質の問題を検出・修正できます。

### セットアップ
```bash
# SonarQube MCPサーバーをセットアップ
export SONARQUBE_TOKEN="your_sonarcloud_token"
./scripts/setup-sonarqube-mcp.sh

# 統合をテスト
./scripts/test-sonarqube-mcp.sh
```

### 機能
- 📊 **リアルタイム分析**: コード変更時に即座に品質チェック
- 🔍 **問題検出**: セキュリティ、バグ、コードスメルの自動検出
- 🛠️ **自動修正**: 一般的な問題の自動修正提案
- 📈 **品質メトリクス**: プロジェクトの品質指標をリアルタイム取得

### 詳細ドキュメント
- [SonarQube MCP統合ガイド](docs/SONARQUBE_MCP_INTEGRATION.md)
- [設定ファイル](.mcp/sonarqube-config.json)
- [環境設定テンプレート](.mcp/.env.template)

## CI/CD & 品質管理

このプロジェクトでは以下の自動化を実施しています：

- **GitHub Actions**: プルリクエストとmainブランチへのプッシュ時に自動テスト実行
- **テストカバレッジ**: PHPUnitとPCOVによるカバレッジ測定
- **SonarQube Cloud**: コード品質とセキュリティの継続的な分析

詳細は [CI/CDワークフロードキュメント](docs/CI_CD_WORKFLOW.md) を参照してください。

## ライセンス

[Apache License 2.0](LICENSE)
