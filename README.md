# インターン課題環境構築手順

## Dockerの基本知識
Dockerの基本的な概念については、以下のリンクを参考にしてください：
- [Docker入門（1）](https://qiita.com/Sicut_study/items/4f301d000ecee98e78c9)
- [Docker入門（2）](https://qiita.com/takusan64/items/4d622ce1858c426719c7)

## セットアップ手順

1. **リポジトリをクローン**
   ```bash
   git clone <リポジトリURL>
   ```

2. **dockerディレクトリに移動**
   ```bash
   cd docker
   ```

3. **データベース名の設定**
   `docker-compose.yml` 内の `db` サービスにある `MYSQL_DATABASE` の値を、各自任意のデータベース名に設定してください。
   
   例:
   ```yaml
   environment:
     MYSQL_ROOT_PASSWORD: root
     MYSQL_DATABASE: <your_database_name>  # 任意のデータベース名を指定
   ```

4. **Dockerイメージのビルド**
   ```bash
   docker-compose build
   ```

5. **コンテナの起動**
   ```bash
   docker-compose up -d
   ```
6. **ブラウザからlocalhostにアクセス**

## PHP周りのバージョン
- **PHP**: 7.3
- **FuelPHP**: 1.8

## ログについて
- **アクセスログ**: Dockerのコンテナのログ
- **FuelPHPのエラーログ**: /var/www/html/intern_kadai/fuel/app/logs/
  - 年月日ごとにログが管理されている
  - tail -f {見たいログファイル}でログを出力

## MySQLコンテナ設定
このプロジェクトには、MySQLを使用するDBコンテナが含まれています。設定は以下の通りです。

- **MySQLバージョン**: 8.0
- **ポート**: `3306`
- **環境変数**:
  - `MYSQL_ROOT_PASSWORD`: root
  - `MYSQL_DATABASE`: 各自設定したデータベース名

### アクセス情報
- **ホスト**: `localhost`
- **ポート**: `3306`
- **ユーザー名**: `root`
- **パスワード**: `root`
- **データベース名**: 各自設定した名前

**DB作成時のクエリ**
- 用意するDB名：memoapp
   - テーブル
      - users
         - id(各ユーザーを識別する一意な整数):int
            - PRIMARY KEY
            - NOT NULL制約
               - NULLを許可しない
            - Default
               -  NULL
            - auto_increment
         - username(ユーザーの名前):varchar(100)
            - NOT NULL制約
               - NULLを許可しない
         - password(ユーザーのパスワード):varchar(255)
            - Default
               -  NULL
            - NOT NULL制約
               - NULLを許可しない
         - created_at:datetime
            - NULLは許可
            - DEFAULT_GENERATED
         - updated_at:datetime
            - NULLは許可
            - DEFAULT_GENERATED on update CURRENT_TIMESTAMP
         - login_hash:varchar(255)
            - NULLは許可
            - Default
               -  NULL
         - last_login:int
            - NULLは許可
            - Default
               -  NULL
      - notes
         - id(各ノートを識別する一意な整数):int
            - NOT NULL制約
               - NULLを許可しない
            - Default
               -  NULL
            - auto_increment
         - user_id(ユーザーをnotesテーブルにおいても一意に認識するためのカラム):int
            - NOT NULL制約
               - NULLを許可しない
            - Default
               -  NULL
         - title(ノートの見出し):varchar(255)
            - Default
               -  NULL
            - NULLは許可
         - content(ノート内部の内容):text
            - Default
               -  NULL
            - NULLは許可
         - created_at(ノート作成日):int
            - Default
               -  NULL
            - NULLは許可
         - updated_at(ノートの最終更新日):int
            - Default
               -  NULL
            - NULLは許可
- クエリ
   - notesテーブル
      ```
      CREATE TABLE 'notes' (
         `id` INT NOT NULL AUTO_INCREMENT,
         `user_id` INT NOT NULL,
         `title` VARCHAR(255) DEFAULT NULL,
         `content` TEXT DEFAULT NULL,
         `created_at` INT DEFAULT NULL,
         `updated_at` INT DEFAULT NULL,
         PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ```
   - usersテーブル
      ```
      CREATE TABLE `users` (
         `id` INT NOT NULL AUTO_INCREMENT,
         `password` VARCHAR(255) NOT NULL,
         `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
         `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
         `username` VARCHAR(100) NOT NULL UNIQUE,
         `last_login` INT DEFAULT NULL,
         `login_hash` VARCHAR(255) DEFAULT NULL,
         `email` VARCHAR(100) DEFAULT NULL,
         PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
      ```
