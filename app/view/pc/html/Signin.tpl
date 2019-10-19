<html>
	<head>
	<title>ログイン − フォトット</title>


        {{include file ='parts/Head.tpl'}}
	</head>
	<body>
{{include file ='parts/HeaderMenu.tpl'}}
<section class="container ">

        <div class="row">
            <div class="col-xs-4">

					<form id="login_form" method="POST" action="./">
                        <h1 class=>ログイン</h1>
						<input type="hidden" name="token" value="{{$token}}" >
                        <div class="form-group">
                            メールアドレス
							<input id="name"   class="form-control" autocomplete="on" placeholder="メールアドレス" name="mail_address" type="email" required>
                        </div>
                        <div class="form-group">
                            パスワード
							<input type="password" class="form-control" placeholder="パスワード" name="password" required>
                        </div>

						<button type="submit" class="btn btn-outline-primary btn-block">ログイン</button>
					</form>

						{{$err_message}}
            </div>
        </div>

</section>
{{include file ='parts/Footer.tpl'}}
	</body>
</html>