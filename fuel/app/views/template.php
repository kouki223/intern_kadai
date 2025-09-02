<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title><?php echo $title ?? "Noteアプリケーション"; ?></title>
        <script src="https://cdn.jsdelivr.net/npm/knockout@3.5.1/build/output/knockout-latest.js"></script>
        <script src="/assets/js/app.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="/assets/css/style.css">
        <style>
        .auto-save-status {
            position: fixed;
            top: 10px;
            right: 10px;z-index: 1000;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
        }
        .note-content {
            min-height: 400px;
            resize: vertical;
        }
        .note-title {
            font-size: 1.5rem;
            border: none;
            background: transparent;
        }
        .note-title:focus {
            outline: 1px solid #0d6efd;
        }
    </style>
    </head>
    <body data-page="<?php echo $page ?? ''; ?>">
        <header>
            <div class="container">
                <h1>Noteアプリケーション</h1>
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
