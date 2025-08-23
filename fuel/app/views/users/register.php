<h1>新規登録</h1>

<form action="<?php echo Uri::create('users/create'); ?>"  method="post">
    <label for="email">メールアドレス:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">登録</button>
</form>

<p>既にアカウントをお持ちですか？ <a href="users/login">ログインはこちら</a></p>
