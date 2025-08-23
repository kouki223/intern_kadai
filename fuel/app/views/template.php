<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="utf-8">
	<title><?php echo $title ?? "Noteアプリケーション"; ?></title>
	<script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
	<script src="/assets/js/app.js"></script>
</head>
<body>
    <header>
        <h1>Noteアプリケーション</h1>
        <nav>
            <ul>
                <li><a href="<?php echo Uri::create('users/login'); ?>">ログイン</a></li>
                <li><a href="<?php echo Uri::create('users/register'); ?>">新規登録</a></li>
                <li><a href="<?php echo Uri::create('users/logout'); ?>">ログアウト</a></li>
                <li><a href="<?php echo Uri::create('notes/index'); ?>">ノート一覧</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <?php echo $content; ?>
    </main>
	<footer>
		<div>copy : This app made by kouki(2025/8/19~)</div>
	</footer>
</body>
</html>
