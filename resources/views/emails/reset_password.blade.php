<x-mail::message>
お客様よりパスワード再発行の申請がございました。

下記URLよりアクセス頂き、パスワードの再設定を行ってください。

<a href="{{ $url }}">{{ $url }}</a>

@include('emails.status_update_footer')
</x-mail::message>