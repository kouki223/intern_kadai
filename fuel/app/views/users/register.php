<div class="main-content">
        <div class="form-container">
            <h1>新規登録</h1>

            <form action="<?php echo Uri::create('users/create'); ?>" method="post">
                <div class="form-group">
                    <label for="username">ユーザー名:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="password">パスワード:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit">登録</button>
            </form>

            <div class="login-link">
                <p>既にアカウントをお持ちですか？ <a href="<?php echo Uri::create('users/login'); ?>">ログインはこちら</a></p>
            </div>
        </div>
    </div>
