<!DOCTYPE html>
<html lang="ja">
<body data-page="login-page">

<div data-bind="visible: step() === 'username'">
    <h2>ログイン</h2>
    <input type="text" data-bind="value: username" placeholder="ユーザー名">
    <button data-bind="click: checkUsername">次へ</button>
    <p data-bind="text: errorMessage, visible: errorMessage"></p>
</div>

<div data-bind="visible: step() === 'password'">
    <p data-bind="text: username"></p>
    <input type="password" data-bind="value: password" placeholder="パスワード">
    <button data-bind="click: passwordLogin">ログイン</button>
    <p data-bind="text: errorMessage, visible: errorMessage"></p>
</div>
</html>