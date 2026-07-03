# Sprint 5 スプリントレビュー記録(番外編: Laravel Sail 移行)

- **日付**: 2026-07-03
- **スプリントゴール**: 開発環境を bitnamilegacy/laravel から Laravel Sail に移行し、全機能がこれまでどおり動く → **達成** ✅

## デモ・確認結果

| 確認項目 | 方法 | 結果 |
|---|---|---|
| Sail 導入 | composer require + sail:install --with=mysql | compose.yaml 生成 ✅ |
| ビルド・起動 | `sail up -d` | laravel.test + mysql 起動 ✅ |
| マイグレーション | `sail artisan migrate` | 4 件 DONE(todos 含む)✅ |
| スモークテスト | curl で一覧・作成・完了切替・削除 | すべて動作 ✅ |
| タイムゾーン | 一覧の日時表示 | 日本時間 ✅(APP_TIMEZONE を .env に移設) |
| アプリコード | git diff | **コントローラ・ビュー・マイグレーションは無変更** ✅ |
| マージ後動作 | sail restart + curl | HTTP 200 ✅ |

## スプリント中に起きた問題と対処(実録)

### 問題1: sail:install の生成物が「見つからない」
- `docker-compose.yml` を探したが存在しない → 実際は **`compose.yaml`**(Docker Compose の現行標準名)で生成されていた
- 教訓: ツールの挙動は思い込まず `git status` で「何が作られ・何が書き換えられたか」を確認する
  (phpunit.xml まで書き換えられていたことも git status で発見)

### 問題2: Vite ポート 5173 の衝突
- Sprint 1 のポート衝突と同じパターン。ただし今回は compose を編集せず **.env に `VITE_PORT=5273` を 1 行**で解決
- .env 駆動構成の利点が早速体感できた

### 問題3: 移行直後の 500 エラー(sessions テーブルがない)
- ログの 1 行目を読むと `Table 'todo_app.sessions' doesn't exist` — 接続は成功、テーブルが未作成なだけ
- bitnami が起動時に自動実行していたマイグレーションを、Sail では自分で叩く(事前に予想していた差異が実際に出た)

### 問題4: PR #5 のコミット漏れ(f5f5db2 で修正)
- ポート衝突対処で .env.example に足した 2 行がコミットされておらず、ブランチ切替時に発覚
- 教訓: **ブランチを切り替える前に `git status` で作業ツリーが綺麗か確認**する

## 移行で変わったこと・変わらなかったこと

| | 旧(bitnamilegacy) | 新(Sail) |
|---|---|---|
| compose の場所 | リポジトリ直下 | src/compose.yaml |
| 設定の持ち方 | compose に直書き | .env 駆動(.env.example が再現性の要) |
| イメージ | 既製品を pull | vendor 内 Dockerfile からローカルビルド |
| マイグレーション | 起動時に自動 | `sail artisan migrate` を手動 |
| 所有者問題 | `-u 1000:1000` が必要 | 起きない(WWWUSER で UID を合わせる仕組み) |
| 日常コマンド | `docker compose exec app ...` | `./vendor/bin/sail ...` |
| **アプリのコード** | — | **1 行も変更なし** |

## 成果物

- PR #5: https://github.com/morisaki-yuichi/todo-app-example1/pull/5
- 補修: f5f5db2(.env.example のコミット漏れ修正)
- 旧 MySQL ボリューム `todo-app-example1_mysql_data` は退路として残置(安定後に `docker volume rm` で削除可)
