# TODOアプリ(スクラム開発教材)

スクラム開発の流れ(全4スプリント)に沿って、初級エンジニアがシニアエンジニアとペアで
CRUD機能を持つTODOアプリを完成させる教材プロジェクトです。

## 機能

- TODOの新規作成(タイトル必須・100文字以内 / 内容任意・1000文字以内)
- 一覧表示(作成日時の新しい順)・詳細表示
- 編集、完了/未完了のワンクリック切り替え
- 削除(確認ページによる誤操作防止つき)

## 技術スタック

| 分類 | 技術 |
|---|---|
| コンテナ | Docker Compose(**Laravel Sail**。Sprint 5 で bitnamilegacy から移行) |
| バックエンド | Laravel 12 |
| フロントエンド | Plain HTML/CSS(Blade テンプレート。JS/CSS フレームワーク不使用) |
| データベース | MySQL 8.4 |

## セットアップ(Laravel Sail)

前提: Docker / Docker Compose が導入済みであること。

```bash
git clone https://github.com/morisaki-yuichi/todo-app-example1.git
cd todo-app-example1/src

# 1. vendor/ がないと sail コマンドが使えないため、使い捨てコンテナで一度だけ composer install
docker run --rm -u "$(id -u):$(id -g)" \
  -v "$(pwd):/var/www/html" -w /var/www/html \
  laravelsail/php85-composer:latest composer install

# 2. 環境設定を用意して起動(初回はイメージのビルドで数分かかります)
cp .env.example .env
./vendor/bin/sail up -d
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate
```

起動後、ブラウザで **http://localhost:8080** を開いてください。
ポートを変えたい場合は `.env` の `APP_PORT`(と `VITE_PORT`)を編集します。

### 開発時のヒント

- artisan・composer は `./vendor/bin/sail artisan ...` / `sail composer ...` で実行する
  (Sail がホストとユーザー ID を合わせるため、旧環境であった root 所有者問題は起きない)
- ブランチ切り替えで 500 エラーになったら `./vendor/bin/sail restart laravel.test`
- 停止は `./vendor/bin/sail down`(DB データはボリュームに残る)

## ディレクトリ構成

```
├── src/                 # Laravel アプリケーション本体
│   └── compose.yaml     # Laravel Sail のコンテナ定義(Laravel + MySQL)
└── docs/                # スクラム成果物(すべてここに記録)
    ├── 00_project/      # ロードマップ、ユーザーストーリー、プロダクトバックログ、Q&A記録
    ├── 01_sprint1/      # 各スプリントのバックログ、レビュー、レトロスペクティブ
    ├── 02_sprint2/
    ├── 03_sprint3/
    ├── 04_sprint4/
    └── 05_sprint5/      # 番外編: Laravel Sail 移行
```

## 学習者向け: 作り方を辿る

**[開発トレースガイド](docs/00_project/dev-walkthrough.md)** に、どのファイルのどの部分を
どの順番で作ったかを、コミット差分へのリンクつきでまとめています。
「写経しながら追体験したい」場合はここから始めてください。

あわせて **[Laravel 概念解説集](docs/00_project/laravel-concepts.md)** に、MVC・マイグレーション・
Eloquent・CSRF などの概念を「一言定義 → なぜ必要か → このリポジトリでの実例」の形で
まとめています。差分を読んでいて用語に詰まったらこちらへ。

## スクラム成果物(ドキュメント)

- [プロダクトロードマップ](docs/00_project/roadmap.md)
- [ユーザーストーリー](docs/00_project/user-stories.md)
- [プロダクトバックログ](docs/00_project/product-backlog.md)
- [開発前の仕様決定 Q&A](docs/00_project/qa-log.md)

各スプリントの計画・レビュー・振り返りは `docs/0N_sprintN/` を参照してください。
スプリント中に遭遇した実際のトラブル(ポート衝突、Bitnami イメージの配布方針変更、
タイムゾーン設定など)と調査・解決の過程も記録されています。
