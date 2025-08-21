<h1>ログイン画面</h1>

<div data-bind="visible: step() === 'email'">
    <h2>ログイン</h2>
    <input type="email" data-bind="value: email" placeholder="メールアドレス">
    <button data-bind="click: checkEmail">次へ</button>
    <p data-bind="text: errorMessage, visible: errorMessage"></p>
</div>

<div data-bind="visible: step() === 'password'">
    <p data-bind="text: email"></p>
    <input type="password" data-bind="value: password" placeholder="パスワード">
    <button data-bind="click: login">ログイン</button>
    <p data-bind="text: errorMessage, visible: errorMessage"></p>
</div>
