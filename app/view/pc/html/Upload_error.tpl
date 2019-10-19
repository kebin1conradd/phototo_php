<html>
<head>
    <title>フォトット</title>
    {{include file ='parts/Head.tpl'}}
</head>
<body>
{{include file ='parts/HeaderMenu.tpl'}}
<p >
    {{if $is_invalid_title}}
        タイトルを入力してください。
    {{/if}}
    {{if $is_not_login}}
        セッション情報が不正です。
        再度ログインしてください。
    {{/if}}

</p>
{{include file ='parts/Footer.tpl'}}
</body>
</html>