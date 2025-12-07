<x-mail::message>

（このメールは自動応答メールです。）

お問い合わせいただき誠にありがとうございます。


以下の内容のお問い合わせを受け付けました。

担当者より折り返しご連絡させていただきます。

尚、お問い合わせ内容によっては、ご返事までにお時間をいただく場合もございます。

あらかじめご了承ください。

<hr>

ご入力内容

お名前 <br>
{{ $inquiry->name }}

件名 <br>
{{ $inquiry->inquiryType->name }}

内容 <br>
{{ $inquiry->body }}

メールアドレス<br>
{{ $inquiry->email }}

@include('emails.status_update_footer')
</x-mail::message>
