# Sprint 1 バックログ

- **期間**: 2026-07-03(想定作業時間 3〜4 時間)
- **スプリントゴール**: ブラウザで Laravel の初期ページが表示され、DB(MySQL)に接続できている
- **対応 PBI**: #1 開発環境構築 / #2 GitHub リポジトリ整備

## タスク一覧

| # | タスク | 完了条件 | 状態 |
|---|---|---|---|
| 1 | `feature/setup` ブランチ作成 | ブランチ上で作業が始められる | 完了 ✅ |
| 2 | `docker-compose.yml` 作成 | bitnami/laravel + MySQL の 2 コンテナが定義されている | 完了 ✅(bitnamilegacy に変更) |
| 3 | Laravel プロジェクト起動 | コンテナ起動で Laravel の雛形が生成される | 完了 ✅ |
| 4 | DB 接続確認 | コンテナ内から `php artisan migrate` が成功する | 完了 ✅ |
| 5 | ブラウザで初期ページ表示 | http://localhost:8080 で Laravel の画面が見える(ポートは 8000→8080 に変更) | 完了 ✅ |
| 6 | main へマージ + スプリント文書化 | レビュー・レトロが `docs/01_sprint1/` に残っている | 完了 ✅(PR #1) |

## スコープ外(Sprint 1 ではやらないこと)

- todos テーブルの作成(Sprint 2)
- 画面・機能の実装(Sprint 2 以降)
