<x-mail::message>

{{ $inquiry->name }}様<br>

この度はお問い合わせいただき誠にありがとうございます。


担当者よりお問い合わせ頂いた内容に基づき下記内容でご回答致しますので

ご確認の程宜しくお願い致します。


<hr>

回答内容

{{ $inquiry->reply }}



@include('emails.status_update_footer')

</x-mail::message>