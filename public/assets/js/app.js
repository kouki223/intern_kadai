function LoginViewModel() {
    var self = this;

    self.step = ko.observable("email");
    self.email = ko.observable("");
    self.password = ko.observable("");
    self.errorMessage = ko.observable("");

    self.checkEmail = function() {
        self.errorMessage("");
        fetch("/api/auth/check_email", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ email: self.email() })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                self.step("password");
            } else {
                self.errorMessage(data.message);
            }
        })
        .catch(() => {
            self.errorMessage("サーバーに接続できませんでした");
        });
    };

    self.login = function() {
        self.errorMessage("");
        fetch("/api/auth/login", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({
                email: self.email(),
                password: self.password()
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                window.location.href = "/dashboard";
            } else {
                self.errorMessage(data.message);
            }
        })
        .catch(() => {
            self.errorMessage("ログイン処理に失敗しました");
        });
    };
}

// ページ読み込み時に ViewModel を適用
document.addEventListener("DOMContentLoaded", function() {
    ko.applyBindings(new LoginViewModel());
});
