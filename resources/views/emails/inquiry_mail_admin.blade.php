<x-mail::message>

下記ユーザーよりお問い合わせが御座いました。

以下の内容を確認し、対応をお願いします。


<hr>

入力内容

お名前 <br>
{{ $inquiry->name }}<br>

件名 <br>
{{ $inquiry->inquiryType->name }}<br>

内容<br>
{{ $inquiry->body }}<br>

メールアドレス<br>
{{ $inquiry->email }}<br><br>




以上

</x-mail::message>