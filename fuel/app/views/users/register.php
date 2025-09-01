<h1>新規登録</h1>

<form action="<?php echo Uri::create('users/create'); ?>"  method="post">
    <label for="username">ユーザー名:</label>
    <input type="text" id="username" name="username" required>
    
    <label for="password">パスワード:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">登録</button>
</form>
<br />
<p>既にアカウントをお持ちですか？ <a href="<?php echo Uri::create('users/login'); ?>">ログインはこちら</a></p>
