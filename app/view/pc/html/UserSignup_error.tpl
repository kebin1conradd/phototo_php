<html>
<head>
	<title>フォトット − loginエラー</title>
    {{include file ='parts/Head.tpl'}}
<body>
{{include file ='parts/HeaderMenu.tpl'}}

		<ul >
            {{if $is_mail_invalid}}
                <li>入力されたメールアドレスの形式が不正です。</li>
            {{/if}}
			{{if $is_password_invalid}}
			<li >パスワードには半角英数字のみしか利用できません</li>
			{{/if}}

			{{if $is_password_not_enough}}
				<li >パスワードの最低文字数は6文字です。</li>
			{{/if}}
			{{if $is_duplicate_mail}}
			<li >同じメールアドレスがサービスに登録済みです。</li>
			{{/if}}
		</ul>

{{include file ='parts/Footer.tpl'}}
</body>
</html>