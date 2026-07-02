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
| コンテナ | Docker Compose(bitnamilegacy/laravel) |
| バックエンド | Laravel 12 |
| フロントエンド | Plain HTML/CSS(Blade テンプレート。JS/CSS フレームワーク不使用) |
| データベース | MySQL 8.4 |

## セットアップ

前提: Docker / Docker Compose が導入済みであること。

```bash
git clone https://github.com/morisaki-yuichi/todo-app-example1.git
cd todo-app-example1
docker compose up -d
```

初回起動は Laravel の初期化のため数分かかります。起動後、ブラウザで
**http://localhost:8080** を開いてください(ポート 8000 ではなく 8080 です)。

マイグレーションはコンテナ起動時に自動実行されます。手動で行う場合:

```bash
docker compose exec app php artisan migrate
```

### 開発時のヒント

- artisan の生成コマンドは所有者問題を避けるため `-u 1000:1000` で実行する
  (例: `docker compose exec -u 1000:1000 app php artisan make:model Xxx`)
- ブランチ切り替えで 500 エラーになったら `docker compose restart app`

## ディレクトリ構成

```
├── docker-compose.yml   # Laravel + MySQL のコンテナ定義
├── src/                 # Laravel アプリケーション本体
└── docs/                # スクラム成果物(すべてここに記録)
    ├── 00_project/      # ロードマップ、ユーザーストーリー、プロダクトバックログ、Q&A記録
    ├── 01_sprint1/      # 各スプリントのバックログ、レビュー、レトロスペクティブ
    ├── 02_sprint2/
    ├── 03_sprint3/
    └── 04_sprint4/
```

## 学習者向け: 作り方を辿る

**[開発トレースガイド](docs/00_project/dev-walkthrough.md)** に、どのファイルのどの部分を
どの順番で作ったかを、コミット差分へのリンクつきでまとめています。
「写経しながら追体験したい」場合はここから始めてください。

## スクラム成果物(ドキュメント)

- [プロダクトロードマップ](docs/00_project/roadmap.md)
- [ユーザーストーリー](docs/00_project/user-stories.md)
- [プロダクトバックログ](docs/00_project/product-backlog.md)
- [開発前の仕様決定 Q&A](docs/00_project/qa-log.md)

各スプリントの計画・レビュー・振り返りは `docs/0N_sprintN/` を参照してください。
スプリント中に遭遇した実際のトラブル(ポート衝突、Bitnami イメージの配布方針変更、
タイムゾーン設定など)と調査・解決の過程も記録されています。
