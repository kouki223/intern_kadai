<h1>新規登録画面(register)</h1>

<p>新規登録のためのフォームをここに配置します。</p>

<form action="users/register" method="post">
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" value="test@example.com" required>
    
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">登録</button>
</form>

<p>既にアカウントをお持ちですか？ <a href="users/login">ログインはこちら</a></p>
