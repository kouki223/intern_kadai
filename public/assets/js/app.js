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
            return res.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);

                if (data.success) {
                    self.step("password");
                } else {
                    self.errorMessage(data.message);
                }
            }
            catch(e) {
                self.errorMessage("サーバーの応答が不正です");
            }
        })
        .catch(() => {
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
                fuel_csrf_token: window.csrfToken
            })
        })
        .then(res => {
            return res.text();
        })
        .then(text => {
            try {
                const data = JSON.parse(text);

                if (data.success) {
                    window.location.href = "/";
                } else {
                    self.errorMessage(data.message);
                }
            }
            catch(e) {
                self.errorMessage("サーバーの応答が不正です");
            }
        })
        .catch(() => {
            self.errorMessage("サーバーに接続できませんでした");
        });
    };
}

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

    self.startCreatingNote = function() {
        self.isCreatingNote(true);
        self.newNoteTitle("");

        setTimeout(() => {
            const input = document.querySelector('.form-control');
            if (input) input.focus();
        }, 0);
    };

    self.cancelCreatingNote = function() {
        self.isCreatingNote(false);
        self.newNoteTitle("");
    };

    self.createNewNote = function() {
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
                title: self.newNoteTitle(),
                content: ""
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                self.newNoteTitle("");
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
        });
    };

    self.goToDetail = function(note) {
        window.location.href = "/notes/detail/" + note.id;
    };

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

function NoteDetailViewModel(noteId) {
    var self = this;

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

    self.loadNote = function() {
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
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                self.title(data.note.title);
                self.content(data.note.content);
                self.updatedAt(data.note.updated_at);
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
        .finally(() => {
            self.isLoading(false);
        });
    };

    self.goBack = function() {
        window.location.href = "/notes/index";
    };

    self.deleteNote = function()
    {
        if (!confirm("このノートを削除しますか？")) return;

        self.isLoading(true);

        fetch("/notes/api/delete_note", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ id: noteId })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert("削除しました");
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

    self.setupAutoSave = function() {
        self.title.subscribe(function(newValue) {
            if (self.isInitialized) {
                self.scheduleAutoSave();
            }
        });

        self.content.subscribe(function(newValue) {
            if (self.isInitialized) {
                self.scheduleAutoSave();
            }
        });
    };

    self.scheduleAutoSave = function() {
        if (self.autoSaveTimeout) {
            clearTimeout(self.autoSaveTimeout);
        }

        self.saveStatus("");

        self.autoSaveTimeout = setTimeout(function() {
            self.saveNote();
        }, 2000);
    };

    self.saveNote = function() {
        if (!self.isInitialized) return;
        self.saveStatus("saving");
        fetch("/notes/api/update/" + self.noteId, {
            method: "PUT",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
                "X-Requested-With": "XMLHttpRequest"
            },
            body: new URLSearchParams({
                title: self.title(),
                content: self.content()
            })
        })
        .then(res => {
            if (!res.ok) {
                throw new Error(`HTTP ${res.status}: ${res.statusText}`);
            }
            return res.json();
        })
        .then(data => {
            if (data.success) {
                self.saveStatus("saved");
                self.updatedAt(data.updated_at);
                self.lastSaveTime(new Date(data.updated_at * 1000).toLocaleTimeString());

                self.loadNote();
                
                setTimeout(function() {
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

    self.manualSave = function() {
        if (self.autoSaveTimeout) {
            clearTimeout(self.autoSaveTimeout);
        }
        self.saveNote();
    };

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

    self.init();
}

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