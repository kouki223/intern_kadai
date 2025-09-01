<!DOCTYPE html>
<html lang="ja">
<body data-page="notes-index">
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>ノート一覧</h1>
        <div>
            <span data-bind="text: notesCount"></span> 件のノート
            <button class="btn btn-primary ms-3" data-bind="click: startCreatingNote, enable: !isCreatingNote()">
                <span data-bind="visible: isCreatingNote">作成中...</span>
                <span data-bind="visible: !isCreatingNote()">新しいノート</span>
            </button>
        </div>
    </div>

    <!-- 新規ノート入力フォーム -->
    <div data-bind="visible: isCreatingNote" class="mb-4">
        <div class="input-group">
            <input type="text" class="form-control" placeholder="ノートタイトルを入力"
                   data-bind="value: newNoteTitle, valueUpdate: 'afterkeydown'">
            <!-- ボタンをクリックした時にhasFocusが外れてしまう事でcreate_noteが呼ばれなくなるのを防ぐ -->
            <button type="button" class="btn btn-success" data-bind="click: createNewNote, enable: newNoteTitle">
                作成
            </button>
            <button type="button" class="btn btn-outline-secondary" data-bind="click: cancelCreatingNote">
                キャンセル
            </button>
        </div>
    </div>

    <!-- エラーメッセージ -->
    <div data-bind="visible: errorMessage()" class="alert alert-danger">
        <span data-bind="text: errorMessage"></span>
    </div>

    <!-- ローディング -->
    <div data-bind="visible: isLoading()" class="text-center">
        <div class="spinner-border" role="status">
            <span class="visually-hidden">読み込み中...</span>
        </div>
    </div>

    <!-- ノート一覧 -->
    <div data-bind="visible: hasNotes() && !isLoading()" class="row">
        <!-- ko foreach: notes -->
        <div class="col-md-6 col-lg-4 mb-3">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="card-title" data-bind="text: title, click: $parent.goToDetail" 
                        style="cursor: pointer; color: #0d6efd;"></h5>
                </div>
                <div class="card-footer">
                    <button class="btn btn-sm btn-outline-danger" 
                            data-bind="click: $parent.deleteNote">
                        削除
                    </button>
                </div>
            </div>
        </div>
        <!-- /ko -->
    </div>

    <!-- ノートなし -->
    <div data-bind="visible: !hasNotes() && !isLoading()" class="text-center py-5">
        <p class="text-muted">まだノートがありません</p>
        <button class="btn btn-primary" data-bind="click: startCreatingNote">
            最初のノートを作成
        </button>
    </div>
</div>
</body>
</html>
