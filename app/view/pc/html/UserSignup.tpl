<html>
	<head>
	<title>フォトット − 新規登録</title>
        {{include file ='parts/Head.tpl'}}
	</head>
	<body>
{{include file ='parts/HeaderMenu.tpl'}}
				<h2>新規登録</h2>
					<form action="./" method="post" name=registform>
						<input type="hidden" name="action" value="apply" >
						<input type="hidden" name="token" value="{{$token}}" >
						<dl class="form-groupe">
							<dt >メールアドレス<span >※必須</span></dt>
							<dd ><input size="50" name="mail_address" type="email" value="" required></dd>
							<dt ">ユーザーネーム<span >※必須</span></dt>
							<dd ><input name="user_name" size="30" type="text" value="" required/></dd>
							<dt >パスワード<span >※必須</span></dt>
							<dd ><input name="password" type="password" size="30" minlength="8" value="" required/></dd>
						</dl>
                        <br>
						<p ><button type="submit"  name="submit_btn">確認</button></p>
					</form>

{{include file ='parts/Footer.tpl'}}
	</body>
</html>