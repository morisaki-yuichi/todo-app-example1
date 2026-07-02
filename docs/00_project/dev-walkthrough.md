# 開発トレースガイド 〜コミットを辿って作り方を追体験する〜

このドキュメントは、**「どのファイルのどの部分を、どの順番で、なぜ作ったか」**を
一人で辿れるようにしたガイドです。コミット履歴がそのまま開発手順書になっています。

> 📖 差分に出てくる用語(MVC、Eloquent、CSRF、PRG パターンなど)が分からなくなったら
> **[Laravel 概念解説集](laravel-concepts.md)** を参照してください。
> 「一言定義 → なぜ必要か → このリポジトリでの実例」の形で、登場順にまとめてあります。

## このガイドの使い方(差分の見方 3 通り)

### 方法1: GitHub で見る(いちばん手軽)
各ステップの「GitHub」リンクを開くと、そのコミットの差分(緑=追加、赤=削除)が見られます。
スプリント単位でまとめて見たいときは PR(プルリクエスト)の「Files changed」タブが便利です。

### 方法2: ローカルで `git show` を使う
```bash
git show 91d71ee            # そのコミットの説明文と差分を表示
git show 91d71ee --stat     # 変更されたファイル一覧だけ表示
git show 91d71ee:src/app/Models/Todo.php  # その時点のファイル全体を表示
```

### 方法3: ファイル単位で歴史を追う
```bash
git log --oneline -- src/routes/web.php   # このファイルを変えたコミット一覧
git log -p -- src/routes/web.php          # 差分つきで全履歴を表示
```

> 💡 コミットは古い順に `git log --oneline --reverse` で一覧できます。
> 本ガイドのステップ番号はこの順序に対応しています。

---

## 全体マップ(ユーザーストーリー × コミット)

| 機能 | ストーリー | 実装コミット | PR |
|---|---|---|---|
| 環境構築 | (土台) | `884b072` `ae470f7` | [#1](https://github.com/morisaki-yuichi/todo-app-example1/pull/1/files) |
| DB・モデル | (土台) | `91d71ee` | [#2](https://github.com/morisaki-yuichi/todo-app-example1/pull/2/files) |
| 一覧表示 | US-2 | `edd271d` | #2 |
| 新規作成 | US-1 | `223db9e` | #2 |
| 詳細表示 | US-3 | `78b8a8c` | [#3](https://github.com/morisaki-yuichi/todo-app-example1/pull/3/files) |
| 編集 | US-4 | `cd03e16` | #3 |
| 完了切替 | US-5 | `5206d3c` | #3 |
| 削除 | US-6 | `bdbec8f` | [#4](https://github.com/morisaki-yuichi/todo-app-example1/pull/4/files) |
| 見た目 | (品質) | `287aa54` | #4 |

---

## Sprint 0: プロジェクト立ち上げ

### Step 1: 計画ドキュメント一式 — `8c5ec44`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/8c5ec44) / `git show 8c5ec44`

- **見るファイル**: `docs/00_project/` の qa-log / roadmap / user-stories / product-backlog
- **ポイント**: コードを書く前に「何を・どの順で・どこまで作るか」を文書で固定した。
  仕様の合意(タイトル100文字、認証なし等)はすべて [qa-log.md](qa-log.md) に残っている

## Sprint 1: 環境構築(PR [#1](https://github.com/morisaki-yuichi/todo-app-example1/pull/1/files))

### Step 2: Docker Compose 定義 — `884b072`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/884b072) / `git show 884b072`

- **見るファイル**: `docker-compose.yml`(このコミットで新規作成、全 42 行)
- **ポイント**:
  - `ports: "8080:8000"` — 左が PC 側・右がコンテナ側。**8000 が別プロジェクトと衝突したため 8080 に変えた経緯**がコメントに残っている
  - `image: bitnamilegacy/laravel` — 最初は `bitnami/laravel:latest` で起動に失敗した。
    調査の顛末は [Sprint 1 レビュー](../01_sprint1/sprint-review.md) の「問題2」を参照
  - `environment:` の `DB_HOST=mysql` — サービス名がそのままホスト名になる Compose の仕組み
  - `healthcheck` + `depends_on` — MySQL の準備完了を待ってから Laravel を起動する順序制御

### Step 3: Laravel 雛形の生成 — `ae470f7`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/ae470f7) / `git show ae470f7 --stat`(59 ファイルあるので --stat 推奨)

- **ポイント**: このコミットは**手書きではなく、コンテナ初回起動時の自動生成**。
  自分で書いたコードと生成されたコードを別コミットに分けてあるので、
  以降のコミットを見れば「教材として書いたコード」だけを追える
- `src/.gitignore` に注目 — `.env`(秘密情報)と `/vendor`(外部ライブラリ)は
  リポジトリに**入っていない**。理由は [Sprint 1 レトロ](../01_sprint1/sprint-retrospective.md) の学び 4

## Sprint 2: 作成と一覧(PR [#2](https://github.com/morisaki-yuichi/todo-app-example1/pull/2/files))

### Step 4: todos テーブルとモデル — `91d71ee`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/91d71ee) / `git show 91d71ee`

- **見るファイル**:
  - `src/database/migrations/2026_07_02_230759_create_todos_table.php` — `up()` に 3 カラム
    (`string('title', 100)` / `text nullable` / `boolean default(false)`)。`down()` とセットな点に注目
  - `src/app/Models/Todo.php` — `$fillable`(マスアサインメント対策の許可リスト)と
    `casts()`(MySQL の 0/1 を PHP の true/false に変換)
- **命名規約**: モデル `Todo`(単数)⇔ テーブル `todos`(複数)で自動的に紐づく

### Step 5: 一覧表示(US-2) — `edd271d`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/edd271d) / `git show edd271d`

MVC の一本道「ルート → コントローラ → モデル → ビュー」が 1 コミットで揃う。**この差分は MVC の最小構成の教科書**。

- `src/routes/web.php` — `Route::get('/todos', ...)` と `/` からのリダイレクト
- `src/app/Http/Controllers/TodoController.php` — `index()` の `Todo::latest()->get()`(作成日時の降順)
- `src/resources/views/layout.blade.php` — 共通レイアウト(`@yield` と `@section` の親子関係)
- `src/resources/views/todos/index.blade.php` — `@foreach`、0 件時の分岐、`{{ }}` の自動エスケープ(XSS 対策)
- **注目**: このコミットには「新規作成」リンクが**ない**。未実装ルートへのリンクを入れると
  エラーになるため、「各コミットで常に動く状態」を保つ工夫(次のコミットで追加される)

### Step 6: 新規作成(US-1) — `223db9e`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/223db9e) / `git show 223db9e`

- `src/routes/web.php` — フォーム表示(GET)と保存(POST)の **2 ルートで 1 機能**
- `TodoController.php` の `store()` — `$request->validate()` のルールと日本語メッセージ。
  保存後の `redirect()->route(...)` は **PRG パターン**(F5 での二重登録防止)
- `src/resources/views/todos/create.blade.php` — `@csrf`(書き忘れると 419 エラー)、
  `old('title')`(検証失敗時の入力値復元)、`$errors->all()` のエラー表示
- 動作確認の様子(curl での正常系・異常系・境界値 101 文字)は [Sprint 2 レビュー](../02_sprint2/sprint-review.md)

## Sprint 3: 詳細・編集・完了切替(PR [#3](https://github.com/morisaki-yuichi/todo-app-example1/pull/3/files))

### Step 7: 詳細表示(US-3) — `78b8a8c`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/78b8a8c) / `git show 78b8a8c`

- `web.php` — `/todos/{todo}` の**可変パス**。`/todos/create` を**先に**定義する理由がコメントにある
  (後にすると "create" が {todo} として解釈される)
- `TodoController.php` の `show(Todo $todo)` — **ルートモデルバインディング**。
  型付き引数だけで「ID 検索+なければ 404」が自動化される(コード量に注目。ほぼ 1 行)
- `todos/show.blade.php` — 改行表示は JS ではなく CSS の `pre-wrap` で解決

### Step 8-9: タイムゾーン修正 — `064f055` → `3273804`
[GitHub 1](https://github.com/morisaki-yuichi/todo-app-example1/commit/064f055) / [GitHub 2](https://github.com/morisaki-yuichi/todo-app-example1/commit/3273804)

**「エラーが出ない不具合」のデバッグ実録**。2 コミットの順番に意味がある。

1. `064f055`(chore): 環境変数 `APP_TIMEZONE=Asia/Tokyo` を追加 → **これだけでは直らなかった**
2. `3273804`(fix): 原因は `src/config/app.php` の `'timezone' => 'UTC'` **直書き**。
   `env('APP_TIMEZONE', 'UTC')` に修正(差分はたった 1 行)
- 教訓: Laravel の設定は「環境変数 → config → アプリ」の 2 段構え。
  切り分けの過程は [Sprint 3 レビュー](../03_sprint3/sprint-review.md) の「気づきと対処」

### Step 10: 編集(US-4) — `cd03e16`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/cd03e16) / `git show cd03e16`

- `todos/edit.blade.php` — `@method('PUT')`(**メソッドスプーフィング**: HTML フォームは
  GET/POST しか送れないため隠しフィールドで補う)と `old('title', $todo->title)`
  (第 2 引数 = 初回表示時の現在値)
- `TodoController.php` — **このコミットで `store()` がリファクタリングされている**点に注目。
  バリデーションを `validateTodo()` に共通化(DRY)。差分の赤い行(削除)と緑の行(追加)を
  見比べると「共通化」が何をすることか分かる

### Step 11: 完了切替(US-5) — `5206d3c`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/5206d3c) / `git show 5206d3c`

- `web.php` — `PATCH /todos/{todo}/toggle`
- `todos/index.blade.php` — 切替が**リンクではなくフォーム**である理由:
  「GET でデータを変えない」原則(ブラウザの先読みで TODO が完了になる事故の防止)
- `TodoController.php` の `toggle()` — `back()` で「一覧から押せば一覧へ、詳細から押せば詳細へ」戻る

## Sprint 4: 削除と仕上げ(PR [#4](https://github.com/morisaki-yuichi/todo-app-example1/pull/4/files))

### Step 12: 削除(US-6) — `bdbec8f`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/bdbec8f) / `git show bdbec8f`

- 設計図は [Sprint 4 バックログ](../04_sprint4/sprint-backlog.md) の「設計メモ」(実装前に描いたもの)
- `todos/delete.blade.php` — JS の confirm() を使わない**確認ページ方式**。
  「確認を見る(GET)」と「実行する(DELETE)」を分けることで誤クリックでは消えない

### Step 13: CSS 仕上げ — `287aa54`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/287aa54) / `git show 287aa54`

- `src/public/css/style.css` の全面改訂。`button` と `.button`(リンク)を同じ見た目に統一し、
  削除系だけ `.danger` で赤に(**色で操作の重みを伝える**)

### Step 14: README とプロジェクト総括 — `2817104` `c5285d2`
[GitHub](https://github.com/morisaki-yuichi/todo-app-example1/commit/2817104)

- 通し受け入れ確認(全 US)の結果と「フラッシュデータの寿命によるテストの偽陽性」の学びは
  [Sprint 4 レビュー](../04_sprint4/sprint-review.md) に記録

---

## コミットに残っていない出来事(ドキュメント参照)

コードの差分だけでは分からないトラブルは、各スプリントのレビュー/レトロに記録してある。

| 出来事 | 記録場所 |
|---|---|
| ポート 8000 衝突と調査手順(docker ps / ss) | [Sprint 1 レビュー](../01_sprint1/sprint-review.md) 問題 1 |
| bitnami/laravel:latest の起動失敗と bitnamilegacy への切替 | 同上 問題 2 |
| ブランチ切替でサーバーが 500 になる現象と復旧法 | [Sprint 1 レトロ](../01_sprint1/sprint-retrospective.md) 申し送り |
| src/ の所有者が root になる問題(以後 `-u 1000:1000` で生成) | 同上 |
| フラッシュデータの寿命によるテストの偽陽性 | [Sprint 4 レビュー](../04_sprint4/sprint-review.md) |

## おすすめの辿り方(演習)

1. `git log --oneline --reverse` で全体の流れを眺める
2. Step 5(`edd271d`)の差分を写経し、MVC の一本道を自分の手で再現する
3. Step 10(`cd03e16`)の差分で「リファクタリング前後」を見比べる
4. 各 PR の「Files changed」でスプリント単位のまとまりを確認する
5. 仕上げに `git checkout 884b072 -- docker-compose.yml` のように過去の状態を取り出して
   現在と比較してみる(戻すときは `git checkout main -- docker-compose.yml`)
