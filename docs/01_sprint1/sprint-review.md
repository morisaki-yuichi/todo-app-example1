# Sprint 1 スプリントレビュー記録

- **日付**: 2026-07-03
- **スプリントゴール**: ブラウザで Laravel の初期ページが表示され、DB(MySQL)に接続できている → **達成** ✅

## デモ・確認結果

| 確認項目 | 方法 | 結果 |
|---|---|---|
| コンテナ起動 | `docker compose up -d` | app / mysql とも起動 ✅ |
| DB 接続 | `docker compose exec app php artisan migrate:status` | 初期マイグレーション 3 件 Ran ✅ |
| ブラウザ表示 | http://localhost:8080 | Laravel ウェルカムページ表示 ✅ |
| GitHub 反映 | PR #1 をマージ | main に反映済み ✅ |

## DoD(完了の定義)チェック

- [x] ブラウザで実際に動作確認できた
- [x] 受け入れ条件をすべて満たしている(PBI #1, #2)
- [x] コードが `main` ブランチにマージされている(PR #1)
- [x] 必要なドキュメントが更新されている

## スプリント中に起きた問題と対処(重要な学び)

### 問題1: ポート 8000 が使用中で app コンテナが起動失敗
- **エラー**: `Bind for 0.0.0.0:8000 failed: port is already allocated`
- **調査**: `docker ps` と `ss -tlnp` で 8000 番の使用者を特定 → 別プロジェクトのコンテナ
- **対処**: ポートマッピングのホスト側だけを 8080 に変更(`"8080:8000"`)。コンテナ内は無変更

### 問題2: bitnami/laravel:latest が起動時にクラッシュ
- **エラー**: `chown: invalid group: 'bitnami:bitnami'`
- **調査**: 起動ログに Bitnami の 2025 年 8 月配布方針変更の告知を発見
- **対処**: 公式案内の保管リポジトリ `bitnamilegacy/laravel` に切り替え。生成途中だった `src/` は消してクリーンに再生成
- **教訓**: 上流(依存イメージ)の都合で環境が壊れることは実務でも起こる。エラー → ログの告知 → 公式の移行先、の順で調査する

## 成果物

- `docker-compose.yml`(app + mysql、経緯コメントつき)
- `src/` Laravel 12 雛形(`.env` / `vendor` はコミット対象外)
- PR #1: https://github.com/morisaki-yuichi/todo-app-example1/pull/1
