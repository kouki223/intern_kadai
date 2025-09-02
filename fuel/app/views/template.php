<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title ?? "Noteアプリケーション"; ?></title>
        <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
        <script src="/assets/js/app.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/assets/css/style.css">
    </head>
    <body data-page="<?php echo $page ?? ''; ?>">
        <header>
            <div class="app-header">
                <a href="<?php echo Uri::create('notes/index'); ?>" style="color: inherit; text-decoration: none;">
                    <h1>Noteアプリケーション</h1>
                </a>
                <nav>
                    <ul>
                        <li><a href="<?php echo Uri::create('users/login'); ?>">ログイン</a></li>
                        <li><a href="<?php echo Uri::create('users/register'); ?>">新規登録</a></li>
                        <li><a href="<?php echo Uri::create('users/logout'); ?>">ログアウト</a></li>
                        <li><a href="<?php echo Uri::create('notes/index'); ?>">ノート一覧</a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <main>
            <?php echo $content; ?>
        </main>
        <footer>
            <div>copy : This app made by kouki(2025/8/19~)</div>
        </footer>
    </body>
</html>
