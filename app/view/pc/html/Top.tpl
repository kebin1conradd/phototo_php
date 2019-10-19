
<html>
<head>

    <title>フォトット</title>
    {{include file ='parts/Head.tpl'}}


</head>
<body>
{{include file ='parts/HeaderMenu.tpl'}}
<br>
<section class="container-fluid wide">
    <div class="row">

            {{foreach from=$top_photo_list item=top_photo_listi}}
              <div class="col-xs-2">
                  <p>{{$top_photo_listi.title}}</p>
                  <img width ="200" src="{{$top_photo_listi.img_url}}">

               </div>
            {{/foreach}}

    </div>
</section>

{{include file ='parts/Footer.tpl'}}
</body>
</html>