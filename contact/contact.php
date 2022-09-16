<?php


//　エスケープ処理やデータチェックを行う関数のファイルの読み込み
require './libs/function.php';

// replace this with email address as you like
$to = 'dev.yusuke040989@gmail.com';

function url()
{
    return sprintf(
        "%s://%s",
        isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http',
        $_SERVER['SERVER_NAME']
    );
}


// 送信ボタンが押された時の処理
if (isset($_POST['data'])) {

    // POSTされたデータに不正な値がないかを別途定義した checkInput()関数で検証
    $_POST = checkInput($_POST);

    // POSTされたデータを変数に格納
    $last_name    = trim(stripslashes($_POST['last_name']));
    $first_name   = trim(stripslashes($_POST['first_name']));
    $email        = trim(stripslashes($_POST['email']));
    $company_name = trim(stripslashes($_POST['company_name']));
    $reason       = trim(stripslashes($_POST['reason']));
    $detail       = trim(stripslashes($_POST['message']));

    // 件名の組み立て
    $subject = $last_name . "様より、HPからお問い合わせがありました";

    // メールの組み立て
    $message .= "名前：　" . $last_name . $first_name . "<br />";
    $message .= "メールアドレス：　" . $email . "<br />";
    // 企業名に入力があればメッセージにセット
    if (!empty($company_name)) {
        $message .= "企業名: " . $company_name . "<br />";
    }
    $message .= "概要: " . $reason . "<br />";
    $message .= "詳細：　<br /><br />";
    $message .= nl2br($detail);

    // Fromの定義
    $from = $last_name . $first_name . "  <" . $email . ">";

    // メールヘッダーの組み立て
    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-2022-JP\r\n";

    ini_set("sendmail_from", $to); // for windows server
    mb_language("Japanese");
    mb_internal_encoding("UTF-8");

    $result = mb_send_mail($to, $subject, $message, $headers);

    if ($result) {

        // 空の配列を代入し、すべてのPOST変数を消去
        $_POST = array();

        // 変数も初期化
        $name = '';
        $email = '';
        $contact_message = '';

        // 再読み込みによる二重送信の防止
        $params = '?result=' . $result;
        $url = (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
        header('Location:' . $url . $params);
        exit;
    }
}


?>


<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>問い合わせ完了</title>

    <!-- google font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c&display=swap" rel="stylesheet">
    <!-- font awesome css -->
    <link href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" rel="stylesheet">

    <!-- favicon -->
    <link rel="icon" href="/favicon.ico" id="favicon">
    <link rel="apple-touch-icon" sizes="180×180" href="/apple-touch-icon-180x180.png">
    <!-- bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">
    <!-- style.css -->
    <link rel="stylesheet" href="/css/common/style.css" />
</head>

<body>
    <!-- splash -->
    <div id="loader-bg" class="loader-zindex">
        <div id="loader">
            <video autoplay loop muted src="/img/splash-logo.mp4"></video>
        </div>
    </div>

    <!-- ヘッダー -->
    <header id="header" class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light">
            <div class="container-fluid">
                <a class="navbar-brand d-flex align-items-center" href="/homepage-ja/index.html">
                    <img src="/img/logo.png" alt="" class="logo img-fluid" />
                    <p class="d-inline mb-0 fs-3 fw-lighter ff-jose">yusuke</p>
                </a>
                <button class="navbar-toggler border-white hamburger-btn" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse position-relative" id="hamburgerContent">
                    <ul class="navbar-nav mb-2 mb-lg-0 position-absolute end-0 header-zindex">
                        <a class="nav-link stretch-underline-anime" href="/homepage-ja/profile.html">
                            <li class="nav-item px-3 ff-nsj">自己紹介</li>
                        </a>
                        <a class="nav-link stretch-underline-anime" href="/homepage-ja/skill.html">
                            <li class="nav-item px-3 ff-nsj">スキル</li>
                        </a>
                        <a class="nav-link stretch-underline-anime" href="/homepage-ja/achievement.html">
                            <li class="nav-item px-3 ff-nsj">実績</li>
                        </a>
                        <a class="nav-link stretch-underline-anime active-page" href="/homepage-ja/contact.html">
                            <li class="nav-item px-3 ff-nsj">問い合わせ</li>
                        </a>
                        <span class="border-start border-2"></span>
                        <a class="nav-link" href="/homepage-en/contact.html">
                            <li class="nav-item px-3 ff-nsj">言語(英)</li>
                        </a>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- アイコン設定 -->
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z" />
        </symbol>
        <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z" />
        </symbol>
        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z" />
        </symbol>
    </svg>

    <!-- main -->
    <main id="contact" class="container-fluid py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-11">
                <h2 class="py-4 text-center ff-mpr fs-1">お問い合わせ</h2>
                <div class="py-4">
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24">
                            <use xlink:href="#check-circle-fill" />
                        </svg>
                        <div>
                            問い合わせが完了しました。<br />内容を確認し、ご返信させて頂きますので、しばらくお待ちください。
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- ソーシャルアイコンの固定バー -->
    <div class="fixed-under-bar social-area">
        <div class="d-flex justify-content-end py-4 px-4">
            <a class="px-2" href="https://github.com/k343979" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-github fa-3x"></i>
            </a>
            <a class="px-2" href="http://facebook.com/yusuke49" target="_blank" rel="noopener noreferrer">
                <i class="fab fa-facebook fa-3x fb-color"></i>
            </a>
        </div>
    </div>

    <!-- footer -->
    <footer>
        <div class="row align-items-center py-3">
            <div class="col-12 text-center">
                <script>
                    document.write(new Date().getFullYear());
                </script>
            </div>
        </div>
    </footer>

    <script src="/js/jquery-3.3.1.min.js"></script>
    <script src="/js/bootstrap.bundle.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/common/main.js"></script>
</body>

</html>