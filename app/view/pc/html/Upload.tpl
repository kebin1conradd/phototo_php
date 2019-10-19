<html>
	<head>
	<title>フォトット − 投稿</title>
        {{include file ='parts/Head.tpl'}}
	</head>
	<body>
{{include file ='parts/HeaderMenu.tpl'}}

				<h2>画像投稿</h2><br>

				<form action="./" method="post" name="edit" enctype="multipart/form-data">
					<input type="hidden" name="action" value="apply">
					<input type="hidden" name="token" value="{{$token}}" >
                    <input type="hidden" name="MAX_FILE_SIZE" value=" 10485760">
					<div>
						<input type="file" name="up_image" size="90"><br>
						jpg,jpeg,gif,png形式　最大10MB　
					</div>
					title
						<input id="name"  type="text" placeholder="タイトルを入力" name="title" maxlength="50" required>

					<button  type="submit">投稿する</button>
				</form>

{{include file ='parts/Footer.tpl'}}
</body>
</html>