// splash画面のアニメーション
$(function () {
    // 高さを取得
    const h = $(window).height();
    // ローディング画像を表示
    $('#loader-bg, #loader').height(h).css('display', 'block');
});
// 読み込み完了後に実行する
$(window).on("load", function () {
    // ローディングをフェードアウト
    $("#loader").delay(1500).fadeOut('slow');
    // 背景色をフェードアウト
    $("#loader-bg").delay(1200).fadeOut('slow');
});

// ハンバーガーメニューのアニメーション
$('.hamburger-btn').on('click', function () {
    $('#hamburgerContent').toggleClass('active');
});