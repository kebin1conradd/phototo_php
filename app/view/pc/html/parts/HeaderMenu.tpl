
<div>
    <a href="{{$base_url}}">top</a>
    {{if !$header.is_login}}<a href="{{$base_url}}signin/">ログイン</a>
        <a  href="{{$base_url}}user/signup/">新規登録</a>
    {{elseif $header.is_login}}
        <a  href="{{$base_url}}upload/">アップロード</a>
    {{/if}}

    {{if $header.is_login}}<a href="{{$base_url}}signout/">ログアウト</a>{{/if}}
</div>
