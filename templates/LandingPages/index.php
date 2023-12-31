<!DOCTYPE html>
<html lang="ja">

<head prefix="og: https://ogp.me/ns#">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Profile</title>
    <meta property="og:url" content="https://s-profile.jp">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Smart Profile - プロフィールサイト作成ツールです。">
    <meta property="og:description" content="簡単にプロフィールサイトを作成できるWebツールです。" />
    <meta property="og:site_name" content="Smart Profile" />
    <meta property="og:image" content="https://s-profile.jp/sp-logo.png" />
    <meta name="twitter:card" content="summary">
    <meta name=“twitter:site” content=“@TakaoKimura3 />
    <meta name="twitter:title" content="Smart Profile - プロフィールサイト作成ツールです。">
    <meta name="twitter:description" content="簡単にプロフィールサイトを作成できるWebツールです。">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;500;700&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .mt108 {
            margin-top: 80px;
        }

        .container {
            max-width: 768px;
            margin: 0 auto;
        }

        .fv {
            height: 480px;
            background-image: url('<?= $this->Url->image('LandingPages/LandingPagesBg.webp') ?>');
            background-size: cover;
            background-position: 50% -180px;
            position: relative;
        }

        .fv_title {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            font-size: 72px;
            font-family: 'M PLUS Rounded 1c', sans-serif;
            font-family: 'Playfair Display', serif;
        }

        .content_text {
            margin-top: 56px;
            line-height: 2.5em;
        }

        .exp_text {
            margin-top: 80px;
            color: #000;
            font-weight: 600;
            background: #F2F1EF;
            padding: 4px 12px;
            border-left: 4px solid #000;
            border-bottom: 1px solid #000;
            letter-spacing: .2em;
            margin-bottom: 40px;
        }

        .content_title {
            font-weight: 600;
            border-bottom: 1px solid #333;
            padding: 2px 4px;
        }

        .work_style_content_list {
            margin-top: 16px;
            margin-left: 32px;
            line-height: 1.8em;
        }

        .mt32 {
            margin-top: 32px;
        }

        .footer {
            background: #333;
            height: 56px;
            line-height: 56px;
            color: #fff;
            margin-top: 80px;
        }

        @media screen and (max-width:640px) {
            .fv {
                height: 320px;
                background-position: 50% 0px;
            }

            .fv_title {
                font-size: 56px;
            }

            .container {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="fv">
        <h1 class="fv_title">Smart Profile</h1>
    </div>
    <div class="overview mt108">
        <div class="container">
            <p class="content_text">
                サイトの外観、ヘッダー画像、アイコン設定、その他、実績や経歴等を設定したら、あなたのプロフィールサイトが作成されます。<br />
                型に当てはめて作成するだけなので、Webの知識は不要です。<br />
                サーバー、ドメインの取得も不要、こちらのURLからアクセスできます。<br />
                転職活動や商談にご使用ください。<br />
            </p>
            <p style="margin-top: 24px;">
                <span style="font-weight: 600;">作成例：</span> <?= $this->Html->link($_SERVER['SERVER_NAME'] . '/' . 'example', ['controller' => 'Portfolios', 'action' => 'index', 'example'], ['target' => '_blank']) ?>
            </p>
            <p class="exp_text">このアプリケーションでできること</p>
            <p class="content_title">経歴の設定</p>
            <ul class="work_style_content_list">
                <li>ご自身の経歴を設定できます。</li>
            </ul>
            <p class="content_title mt32">実績の設定</p>
            <ul class="work_style_content_list">
                <li>ご自身がこれまでやってこられた仕事を設定できます。</li>
                <li>画像、URLを貼り詳細に見せることができます。</li>
            </ul>
            <p class="content_title mt32">その他、ワークスタイルなど</p>
            <ul class="work_style_content_list">
                <li>稼働時間</li>
                <li>ご自身の単価</li>
                <li>資格等々</li>
                <li>記載したいものがあれば何でも設定できます。</li>
            </ul>
            <p class="content_title mt32">サイトの外観設定</p>
            <ul class="work_style_content_list">
                <li>プロフィール画像</li>
                <li>ヘッダー画像</li>
                <li>サイトタイトル、ファビコン等</li>
                <li>上記の項目が設定可能です。</li>
            </ul>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <div class="footer_copy">©Smart Profile inc</div>
        </div>
    </footer>
</body>

</html>