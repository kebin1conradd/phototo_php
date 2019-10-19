
<html>
<head>
    <title>フォトット</title>
    {{include file ='parts/Head.tpl'}}

</head>
<body>
{{include file ='parts/HeaderMenu.tpl'}}
    <p >
        {{if $is_invalid}}
        既に認証を終えているか、
        指定された認証コードが間違っています。
        {{/if}}
    </p>
{{include file ='parts/Footer.tpl'}}
</body>
</html>