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
                password: self.password()
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

// ページ読み込み時に ViewModel を適用
document.addEventListener("DOMContentLoaded", function() {
    ko.applyBindings(new LoginViewModel());
});
