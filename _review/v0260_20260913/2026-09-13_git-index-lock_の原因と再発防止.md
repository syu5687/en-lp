# git の "A lock file already exists" ｜原因と再発防止

発生：2026-09-13 ／ GUIでコミットしようとして `Commit failed` になった
状態：**解消済み。コミットできます。**

---

## 【事実】起きたこと

`en-lp/.git/index.lock`（0バイト）が残っていて、GUIのコミットが止まっていました。

```
A lock file already exists in the repository, which blocks this operation from completing.
```

git は index を書き換えるとき `.git/index.lock` を作り、終わったら消します。
このロックが消えずに残ると、以降すべてのコミット・ステージ操作が止まります。

---

## 【原因】Claudeの実行環境は、ファイルを削除できません

Cowork から接続している端末側シェル（device_bash）には **削除権限がありません**。
`rm` / `rmdir` / `unlink` は `Operation not permitted` で失敗します。

そのため、Claudeが `git status` や `git add` を実行すると次のことが起きます。

1. git が `.git/index.lock` を作る
2. 処理が終わり、git がロックを消そうとする
3. **消せない**（`warning: unable to unlink '.../.git/index.lock'`）
4. ロックが残り、以後のコミットが全部止まる

リポジトリ直下にあった `_DELETE_index.lock.stale` `_DELETE_index.lock2`（ともに0バイト、9/8作成）は、
同じことが以前にも起きていた痕跡です。

**つまり、Claudeが端末側で git コマンドを打つたびに、この状態になり得ます。**

---

## 【対処】今回やったこと

1. `.git/index.lock` を `_to_delete/git-index.lock.stale.20260913-024612` へ退避（消せないので移動）
2. `.git` 配下に他のロックが無いことを確認（`find .git -name "*.lock"` → 0件）
3. リポジトリ直下の `_DELETE_index.lock.stale` `_DELETE_index.lock2` も `_to_delete/` へ移動
   （0バイトの残骸で、Dockerイメージにも入っていました）
4. `.gitignore` にロックファイルを追加

```
# git のロックファイル（device_bash から git を触ると消せずに残り、
# GUIのコミットが "A lock file already exists" で止まるため）
.git/index.lock
_DELETE_index.lock*
*.lock.stale
```

---

## 【再発防止】Claude側の運用ルール

### 端末側で git の書き込み系コマンドを実行しない

`git add` / `git commit` / `git rm` / `git mv` / `git checkout` / `git stash` は
**必ず syu が GUI または自分のターミナルで実行する**。Claudeは実行しない。

### 状態確認は `--no-optional-locks` を付ける

`git status` も index を更新するためロックを作ります。読み取りだけなら次の形にします。

```bash
git --no-optional-locks status --short
git --no-optional-locks diff --stat
git --no-optional-locks ls-files
```

このオプションを付けると index を書かないため、ロックが作られません。

### ファイルの追加・削除は git を使わず、ファイル操作で行う

- 追加：普通にファイルを書く（GUIに `??` として出る）
- 削除：`_to_delete/` へ `mv` する（GUIに `D` として出る）

どちらも git を触らずに済み、syu がGUIでまとめてコミットできます。

### ロックが残ってしまったときの復旧手順

```bash
cd <リポジトリ>
find .git -name "*.lock"                     # 何が残っているか確認
mkdir -p _to_delete
mv .git/index.lock _to_delete/index.lock.stale.$(date +%s)
git --no-optional-locks status --short       # 復旧確認
```

**注意**：git のプロセスが本当に動いている最中であれば、ロックは正当なものです。
`ps aux | grep git` で動いているものが無いことを確かめてから退避してください。

---

## 現在の状態

```
 M .gitignore
 M VERSION
 D _DELETE_index.lock.stale
 D _DELETE_index.lock2
 M _review/README.md
 M contact/index.php
 M includes/config.php
 M includes/shiryou-cta.php
 M worker/en-contact/worker.js
?? _review/v0260_20260913/
```

ロックは残っていません。GUIからコミットできます。
`_DELETE_*` の2ファイルは0バイトの残骸なので、この機会に削除で問題ありません。
残したい場合は `_to_delete/` から戻せます。
