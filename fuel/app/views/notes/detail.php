<!DOCTYPE html>
<html lang="ja">
    <body data-page="note-detail">
        <div class="container-fluid mt-3">
            <div data-bind="visible: saveStatusText(), css: saveStatusClass" class="auto-save-status">
                <span data-bind="text: saveStatusText"></span>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                <button class="btn btn-outline-secondary" data-bind="click: goBack">
                    ← ノート一覧に戻る
                </button>
                <div>
                    <span class="text-muted me-3" data-bind="text: 'Last Updated: ' + updatedAt()"></span>
                        <button class="btn btn-outline-primary btn-sm me-2" data-bind="click: manualSave">
                            手動保存
                        </button>
                    <button class="btn btn-outline-danger btn-sm" data-bind="click: deleteNote">
                        削除
                    </button>
                </div>
            </div>

            <div data-bind="visible: errorMessage()" class="alert alert-danger">
                <span data-bind="text: errorMessage"></span>
            </div>

            <div data-bind="visible: isLoading()" class="text-center py-5">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">読み込み中...</span>
                </div>
            </div>

            <div data-bind="visible: !isLoading()" class="row">
                <div class="col-12">
                    <input
                        type="text" 
                        data-bind="value: title, valueUpdate: 'input'"
                        class="form-control note-title mb-3" 
                        placeholder="ノートのタイトルを入力..."
                    >
                    <textarea
                        data-bind="value: content, valueUpdate: 'input'" 
                        class="form-control note-content" 
                        placeholder="ここにノートの内容を入力してください...自動保存機能により、入力した内容は自動的に保存されます。Ctrl+S で手動保存も可能です。"
                    >
                    </textarea>
                </div>
            </div>

            <div class="mt-3">
                <small class="text-muted">
                    💡 ヒント: 内容は自動的に保存されます。ボタンクリックで手動保存も可能です。
                </small>
            </div>
        </div>
    </body>
</html>