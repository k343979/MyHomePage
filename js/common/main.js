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

// 問い合わせページ関連
$(function () {
    // 入力可能な文字数
    var maxCount = 1000;

    // 問い合わせ詳細の文字制限
    $('#contact-detail').on('input', function () {
        var count = $(this).val().length;
        $('#detail-count').text(count + '/' + maxCount);
        if (count > maxCount) {
            $('#detail-count').css('color', '#ff0211');
        }
    });

    // 送信ボタン押下時の処理
    $('#contactSubmit').on('click', function () {
        // valueである文字列"lang=*"から"lang="を取り除きlangに格納
        str = $(this).val();
        // langのデフォルト
        lang = '';
        if (str.indexOf('lang=') != -1) {
            lang = str.replace('lang=', '');
        }

        // バリデーション件数
        var errorCount = 0;
        // id格納用配列
        var ids = [];
        // ユーザーの入力値格納用配列
        var values = [];

        // id属性に"contact-"のプレフィクスが付く要素を取得
        // 全ての入力項目が取得対象になる
        $('[id^=contact-]').each(function () {
            // required属性がついている、入力必須項目のバリデーション
            if ($(this).attr('required') && $(this).val() == '') {
                createAlert(this, lang, false);
                $(this).css('border', '1px solid #ff0211');
                errorCount++;
                return;
            }
            // required属性がついているセレクトボックスのバリデーション
            if ($(this).attr('required') && $(this).val() == '0') {
                createAlert(this, lang, false);
                $(this).css('border', '1px solid #ff0211');
                errorCount++;
                return;
            }
            // 入力可能な文字数のバリデーション
            if ($(this).val() != '' && $(this).val().length > maxCount) {
                createAlert(this, lang, true);
                $(this).css('border', '1px solid #ff0211');
                errorCount++;
                return;
            }

            // id属性から"contact-"を取り除き、"-"を"_"に置換
            var id = $(this).attr("id");
            id = id.replace('contact-', '');
            id = id.replace('-', '_');
            // 配列に格納
            ids.push(id);
            values.push($(this).val());
        });

        // バリデーション件数が1件以上存在する場合
        if (errorCount != 0) {
            return;
        }

        // ajaxの通信データ格納用オブジェクト
        var obj = {};
        // idの数(入力項目数)だけループし、{id: value}の形式に格納
        for (let i = 0; i < ids.length; i++) {
            obj[ids[i]] = values[i];
        }

        // info配列にオブジェクトを格納
        info = [obj];
        // info配列をJSON文字列に変換
        data = JSON.stringify(info);

        // ajax通信
        $.ajax({
            type: 'POST',
            url: '../../contact/contact.php',
            data: {
                data: data,
            }
        }).done(function (data) {
            return;
        }).fail(function (XMLHttpRequest, textStatus, errorThrown) {
            return;
        });
    });

    // アラートメッセージ生成
    function createAlert(object, lang, countViolateFlag) {
        // 入力項目のid属性を取得
        id = $(object).attr('id');
        // 入力項目のラベルを取得
        label = $('label[for="' + id + '"]').text();
        // タグの種別を取得
        tagKind = $(object)[0].nodeName;
        // タグの種別毎にエラーメッセージを分岐
        switch (tagKind) {
            case 'INPUT':
                // デフォルト
                $('.' + id).text('please input "' + label + '"');
                // 日本語
                if (lang == 'ja') {
                    $('.' + id).text(label + 'を入力して下さい');
                }
                break;
            case 'SELECT':
                // デフォルト
                $('.' + id).text('please select "' + label + '"');
                // 日本語
                if (lang == 'ja') {
                    $('.' + id).text(label + 'を選択して下さい');
                }
                break;
            case 'TEXTAREA':
                // 文字数制限を超えていた場合
                if (countViolateFlag) {
                    // デフォルト
                    $('.' + id).text('word count is over.');
                    // 日本語
                    if (lang == 'ja') {
                        $('.' + id).text('文字数制限を超えています');
                    }
                } else {
                    // デフォルト
                    $('.' + id).text('please input "' + label + '"');
                    // 日本語
                    if (lang == 'ja') {
                        $('.' + id).text(label + 'を入力して下さい');
                    }
                }
                break;
            default:
                // 日本語
                if (lang == 'ja') {
                    $('.' + id).text(label + 'を確認して下さい');
                }
                break;
        }
    };
});