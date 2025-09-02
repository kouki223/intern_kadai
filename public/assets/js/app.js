function LoginViewModel() {
    var self = this;

    self.step = ko.observable("username");
    self.username = ko.observable("");
    self.password = ko.observable("");
    self.errorMessage = ko.observable("");

    self.checkUsername = function() {
        self.errorMessage("");
        fetch("/users/check_username", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ username: self.username() })
        })
        .then(res => {
            console.log("HTTP status:", res.status); // ステータスコード確認
            console.log("Content-Type:", res.headers.get("Content-Type")); // ヘッダー確認
            return res.text(); // 一旦テキストで取得
        })
        .then(text => {
            console.log("Raw response text:", text); // サーバーからの生データ確認
            try {
                const data = JSON.parse(text); // JSON に変換
                console.log("Parsed JSON:", data); // パース結果確認
            if (data.success) {
                self.step("password");
            } else {
                self.errorMessage(data.message);
            }
            }
            catch(e) {
                console.error("JSON parse error:", e);
                self.errorMessage("サーバーの応答が不正です");
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            self.errorMessage("サーバーに接続できませんでした");
        });
    };

    self.passwordLogin = function() {
        self.errorMessage("");
        fetch("/users/password_login", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                username: self.username(),
                password: self.password(),
                fuel_csrf_token: window.csrfToken // CSRFトークンを追加
            })
        })
        .then(res => {
            console.log("HTTP status:", res.status); // ステータスコード確認
            console.log("Content-Type:", res.headers.get("Content-Type")); // ヘッダー確認
            return res.text(); // 一旦テキストで取得
        })
        .then(text => {
            console.log("Raw response text:", text); // サーバーからの生データ確認
            try {
                const data = JSON.parse(text); // JSON に変換
                console.log("Parsed JSON:", data); // パース結果確認
            if (data.success) {
                window.location.href = "/"; // ログイン成功後にリダイレクト
            } else {
                self.errorMessage(data.message);
            }
            }
            catch(e) {
                console.error("JSON parse error:", e);
                self.errorMessage("サーバーの応答が不正です");
            }
        })
        .catch(err => {
            console.error("Fetch error:", err);
            self.errorMessage("サーバーに接続できませんでした");
        });
    };
}

// ノート一覧ViewModel-----------------------------------------------------------------------------------------
function NotesIndexViewModel() {
    var self = this;

    self.notes = ko.observableArray([]);
    self.notesCount = ko.computed(() => self.notes().length);

    self.isLoading = ko.observable(false);
    self.errorMessage = ko.observable("");
    self.isCreatingNote = ko.observable(false);
    self.newNoteTitle = ko.observable("");

    self.loadNotes = function() {
        self.isLoading(true);
        fetch("/notes/api/notes")
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.notes)) {
                    self.notes(data.notes);
                } else {
                    self.errorMessage("ノートの取得に失敗しました");
                }
            })
            .catch(() => self.errorMessage("通信エラーが発生しました"))
            .finally(() => self.isLoading(false));
    };

    self.hasNotes = ko.computed(() => self.notes().length > 0);

    // 新規作成開始
    self.startCreatingNote = function() {
        self.isCreatingNote(true);
        self.newNoteTitle("");       // タイトル初期化

        setTimeout(() => {
            const input = document.querySelector('.form-control');
            if (input) input.focus();
        }, 0);
    };

    // キャンセル
    self.cancelCreatingNote = function() {
        self.isCreatingNote(false);
        self.newNoteTitle("");
    };

    // ノート作成
    self.createNewNote = function() {
        //　タイトルが空の場合は作成しない
        if (!self.newNoteTitle())
        {
            self.errorMessage("タイトルを入力してください");
            return;
        }

        self.isLoading(true);

        fetch("/notes/create_note", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                // Viewで入力されたタイトルを送信
                title: self.newNoteTitle(),
                content: "" // 新規作成時は内容は空で送信
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                self.newNoteTitle("");
                // 作成されたノートの詳細ページへ
                window.location.href = "/notes/detail/" + data.note.id;
            } else {
                self.errorMessage(data.message || "");
            }
        })
        .catch(() => {
            self.errorMessage("通信エラーが発生しました");
        })
        .finally(() => {
            self.isLoading(false);
            // self.isCreatingNote(false);
        });
    };

    // ノート詳細ページへ移動
    self.goToDetail = function(note) {
        window.location.href = "/notes/detail/" + note.id;
    };

    // ノート削除
    self.deleteNote = function(note) {
        if (!confirm("このノートを削除しますか？")) return;

        self.isLoading(true);

        fetch("/notes/api/delete_note" , {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                id: note.id
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                self.notes.remove(note);
            } else {
                self.errorMessage(data.message || "ノートの削除に失敗しました");
            }
        })
        .catch(() => {
            self.errorMessage("通信エラーが発生しました");
        })
        .finally(() => {
            self.isLoading(false);
        });
    };

    self.loadNotes();
}


// ノート詳細ViewModel-----------------------------------------------------------------------------------------
function NoteDetailViewModel(noteId) {
    var self = this;

    // オブザーブルで管理するプロパティ
    self.noteId = noteId;
    self.title = ko.observable("");
    self.content = ko.observable("");
    self.updatedAt = ko.observable("");
    self.isLoading = ko.observable(false);
    self.errorMessage = ko.observable("");
    self.saveStatus = ko.observable("");
    self.lastSaveTime = ko.observable("");
    self.autoSaveTimeout = null;
    self.isInitialized = false;

    self.init = function() {
        self.loadNote();
        self.setupAutoSave();
    };

    // ノート詳細を読み込み
    self.loadNote = function() {
        // 読み込み中の画面を表示
        self.isLoading(true);
        self.errorMessage("");

        fetch("/notes/api_note/" + self.noteId, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest"
            }
        })
        .then(res => {
            // HTTPエラーステータスの処理をしてからJSONを返す
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                // 取得したノートの内容を各オブザーバブルにセット
                self.title(data.note.title);
                self.content(data.note.content);
                self.updatedAt(data.note.updated_at);
                // フラグを立てる事で編集可能になった時にオブザーブルで変更を監視できるようにする
                self.isInitialized = true;
            } else {
                self.errorMessage(data.message || "ノートの読み込みに失敗しました");
            }
        })
        .catch(err => {
            console.error("Load note error:", err);
            if (err.message.includes("404")) {
                self.errorMessage("ノートが見つかりません");
            } else {
                self.errorMessage("ノートの読み込み中にエラーが発生しました");
            }
        })
        // 画面の状態を戻す
        .finally(() => {
            self.isLoading(false);
        });
    };

    // ノート一覧に戻る関数
    self.goBack = function() {
        window.location.href = "/notes/index";
    };

    // ノートの削除
    self.deleteNote = function()
    {
        if (!confirm("このノートを削除しますか？")) return;

        self.isLoading(true);

        fetch("/notes/api/delete_note", {
            method: "POST", // FuelPHP が DELETE 非対応なら POST でOK
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ id: noteId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("削除しました");
                // 配列から remove ではなく一覧ページへ戻す
                window.location.href = "/notes/index";
            } else {
                self.errorMessage(data.message || "ノートの削除に失敗しました");
            }
        })
        .catch(() => {
            self.errorMessage("通信エラーが発生しました");
        })
        .finally(() => {
            self.isLoading(false);
        });
    };

    // 自動保存の設定
    self.setupAutoSave = function() {
        // タイトルの変更を監視
        self.title.subscribe(function(newValue) {
            // isInitializedがtrueの時のみ2秒後の自動保存をスケジュール
            if (self.isInitialized) {
                self.scheduleAutoSave();
            }
        });

        // コンテンツの変更を監視
        self.content.subscribe(function(newValue) {
            // isInitializedがtrueの時のみ2秒後の自動保存をスケジュール
            if (self.isInitialized) {
                self.scheduleAutoSave();
            }
        });
    };

    // 自動保存のスケジュール
    self.scheduleAutoSave = function() {
        // 既存のタイマーをクリア
        if (self.autoSaveTimeout) {
            clearTimeout(self.autoSaveTimeout);
        }

        // 保存状態をリセット
        self.saveStatus("");

        // 2秒後に自動保存を実行
        self.autoSaveTimeout = setTimeout(function() {
            self.saveNote();
        }, 2000);
    };

    // ノートの保存
    self.saveNote = function() {
        // ノート詳細の取得をした際にはisInitializedがtrueになるためfalseの場合にはretuernする
        if (!self.isInitialized) return;

        // オブザーバルの監視対象であるsaveStatusを"保存中"に変更する
        self.saveStatus("saving");

        // 詳細を見ているnoteIdを受け取りURLに含めてPUTリクエストを送信
        fetch("/notes/api/update/" + self.noteId, {
            method: "PUT",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: new URLSearchParams({
                // titleとcontentを送信
                title: self.title(),
                content: self.content()
            })
        })
        // HTTPレスポンスをチェックしてからJSONを返す
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                // 保存に成功した場合にオブザーバルで管理されているsaveStatusを"保存済み"に変更
                self.saveStatus("saved");
                // 更新日時と最後の保存時間を更新
                self.updatedAt(data.updated_at);
                self.lastSaveTime(new Date(data.updated_at * 1000).toLocaleTimeString());
            
                // ノートの状態をリロード
                self.loadNote();
                
                // 5秒後に保存状態をクリア
                setTimeout(function() {
                    // オブザーバルの監視対象であるsaveStatusが"保存済み"の時のみクリアする
                    if (self.saveStatus() === "saved") {
                        self.saveStatus("");
                    }
                }, 5000);
            } else {
                self.saveStatus("error");
                self.errorMessage(data.message || "保存に失敗しました");
            }
        })
        .catch(err => {
            console.error("Save note error:", err);
            self.saveStatus("error");
            self.errorMessage("保存中にエラーが発生しました");
        });
    };

    // 手動保存を実行した際に実行される関数
    self.manualSave = function() {
        // 自動保存がスケジュールされている場合にはクリアして即座に保存を実行する
        if (self.autoSaveTimeout) {
            clearTimeout(self.autoSaveTimeout);
        }
        // 自動保存がスケジュールされていなければ保存を実行
        self.saveNote();
    };

    // オブザーバルで管理されているsaveStatusに応じて表示するテキストを変更する計算済みのプロパティ
    self.saveStatusText = ko.computed(function() {
        switch (self.saveStatus()) {
            case "saving":
                return "保存中...";
            case "saved":
                return "保存済み (" + self.lastSaveTime() + ")";
            case "error":
                return "保存エラー";
            default:
                return "";
        }
    });

    // オブザーバルで管理されているsaveStatusに応じて表示するクラスを変更する計算済みのプロパティ
    self.saveStatusClass = ko.computed(function() {
        switch (self.saveStatus()) {
            case "saving":
                return "text-warning";
            case "saved":
                return "text-success";
            case "error":
                return "text-danger";
            default:
                return "";
        }
    });

    // 初期化実行
    self.init();
}

// ページ読み込みをした際にpage属性に応じてViewModelを適用する------------------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function() {
    var page = document.body.getAttribute("data-page");

    switch(page) {
        case "login":
            ko.applyBindings(new LoginViewModel());
            break;
        case "notes-index":
            ko.applyBindings(new NotesIndexViewModel());
            break;
        case "note-detail":
            var pathArray = window.location.pathname.split('/');
            var noteId = pathArray[pathArray.length - 1];
            if (noteId && !isNaN(noteId)) {
                ko.applyBindings(new NoteDetailViewModel(noteId));
            } else {
                alert("無効なノートIDです");
                window.location.href = "/notes/index";
            }
            break;
        default:
            console.warn("Unknown page: " + page);
    }
});