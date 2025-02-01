# PinkieIt

PinkieItは、Laravelを使用したWebアプリケーションです。このREADMEでは、プロジェクトのセットアップと実行方法について説明します。

## 必要条件

- Docker
- Docker Compose

## Dockerのインストール

Dockerがインストールされていない場合は、以下の手順でインストールしてください：

1. Dockerをインストールします：
   ```
   curl -fsSL https://get.docker.com | sudo sh
   ```

2. 現在のユーザーをdockerグループに追加します：
   ```
   sudo usermod -aG docker $USER
   ```

3. 変更を適用するために、ログアウトして再度ログインするか、システムを再起動してください。

4. インストールを確認します：
   ```
   docker --version
   ```

注意: Docker Composeは通常、上記の手順でDockerと一緒にインストールされます。インストールされていない場合は、`sudo apt-get install -y docker-compose-plugin` を実行してインストールしてください。

## セットアップ手順

1. リポジトリをクローンします：
   ```
   git clone <repository-url>
   ```

2. プロジェクトディレクトリに移動します：
   ```
   cd pinkieit
   ```

3. セットアップスクリプトに実行権限を付与します：
   ```
   chmod +x setup.sh
   ```

4. セットアップスクリプトを実行し、プロンプトに従って必要な情報を入力します：
   ```
   ./setup.sh
   ```
   注: データベースのユーザー名とパスワード、Pusher関連の設定を入力するよう求められます。
   DBのパスワードは必須項目です。その他の項目については、デフォルト値を使用する場合は、そのままEnterキーを押してください。

5. Dockerコンテナを起動します：
   ```
   docker compose up -d --build
   ```
   注: 初回実行時や、Dockerfileやdocker-compose.ymlを変更した場合は、必ず`--build`オプションを付けてください。それ以外の場合は、単に`docker compose up -d`で十分です。

6. （オプション）シードデータを投入したい場合：
   ```
   docker compose exec web-app composer install
   docker compose exec web-app php artisan db:seed
   ```
   まず、composer installを実施し、devに必要なパッケージをインストールします。 その後、db:seedを実行します。

7. （オプション）管理者ユーザーを作成します：
   ```
   docker compose exec web-app php artisan make:user <userrole> <email> <password>
   ```
   例：
   ```
   docker compose exec web-app php artisan make:user admin admin@example.com your_secure_password
   ```
   注意: 実際の使用時は、強力で一意のパスワードを使用してください。上記の例は説明のためのものです。

## 使用方法

セットアップが完了したら、ブラウザで http://localhost:18080、またはhttp://Dockerコンテナが動いてるマシンのIP:18080 にアクセスしてアプリケーションを使用できます。

## 開発

### 環境変数

このプロジェクトは独自のWebSocketサーバーを使用しています。
PUSHER_*の設定は外部のPusherサービスではなく、
このアプリケーション内のWebSocketサーバーの設定に使用されます。

### コマンド

- アプリケーションログの確認：
  ```
  docker compose logs -f web-app
  ```

- mqtt brokerの確認：
  ```
  docker compose exec mqtt mosquitto_sub -h localhost -p 1883 -t production
  ```
   注意: mqttのtopicがproductionの場合。


- Artisanコマンドの実行：
  ```
  docker compose exec web-app php artisan <command>
  ```

## トラブルシューティング

問題が発生した場合は、以下の手順を試してください：

1. Dockerコンテナを再起動する：
   ```
   docker compose restart
   ```

2. キャッシュをクリアする：
   ```
   docker compose exec web-app php artisan cache:clear
   docker compose exec web-app php artisan config:clear
   ```

3. コンポーザーの依存関係を更新する：
   ```
   docker compose exec web-app composer update
   ```

それでも問題が解決しない場合は、イシューを作成してください。

## ライセンス

[Apache License 2.0](LICENSE)