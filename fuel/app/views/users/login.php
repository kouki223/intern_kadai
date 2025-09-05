<!DOCTYPE html>
<html lang="ja">

<script>
    window.csrfToken = '<?php echo \Security::fetch_token(); ?>';
</script>

<header>
<style>
    /* メインコンテンツエリア */
    .main-content {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 80px);
        padding: 2rem;
    }

    /* ログインフォームコンテナ */
    .login-container {
        background: white;
        padding: 3rem;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        width: 100%;
        max-width: 450px;
        min-height: 350px;
        position: relative;
        overflow: hidden;
    }

    /* ステップタイトル */
    .login-container h2 {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        text-align: center;
        margin-bottom: 2rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ログインステップ */
    .login-step {
        animation: fadeIn 0.5s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* ユーザー名表示（パスワードステップ用） */
    .username-display {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1rem;
        border-radius: 8px;
        text-align: center;
        font-weight: 600;
        margin-bottom: 1.5rem;
        font-size: 1.1rem;
    }

    /* 入力フィールド */
    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 1.25rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        background-color: #f8f9fa;
        margin-bottom: 1.5rem;
    }

    input[type="text"]:focus,
    input[type="password"]:focus {
        outline: none;
        border-color: #667eea;
        background-color: white;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: translateY(-1px);
    }

    input::placeholder {
        color: #a0aec0;
        font-weight: 400;
    }

    /* ボタンスタイル */
    button {
        width: 100%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.25rem;
        border: none;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 1rem;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    button:active {
        transform: translateY(0);
    }

    /* エラーメッセージ */
    .error-message {
        background-color: #fed7d7;
        color: #c53030;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #e53e3e;
        font-weight: 500;
        margin-top: 1rem;
        animation: shake 0.5s ease-in-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    </style>
</header>

<div class="main-content">
        <div class="login-container">
            <div class="login-step" data-bind="visible: step() === 'username'">
                <h2>ログイン</h2>
                <input type="text" data-bind="value: username" placeholder="ユーザー名">
                <button data-bind="click: checkUsername">次へ</button>
                <p class="error-message" data-bind="text: errorMessage, visible: errorMessage"></p>
            </div>

            <div class="login-step" data-bind="visible: step() === 'password'">
                <h2>ログイン</h2>
                <div class="username-display" data-bind="text: username"></div>
                <input type="password" data-bind="value: password" placeholder="パスワード">
                <button data-bind="click: passwordLogin">ログイン</button>
                <p class="error-message" data-bind="text: errorMessage, visible: errorMessage"></p>
            </div>
        </div>
    </div>
</html>