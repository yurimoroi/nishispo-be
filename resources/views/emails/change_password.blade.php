<x-mail::message>
お客様よりパスワードの変更がございました。  

お客様の新しいパスワードは以下となります。  

【パスワード】

{{ $password }} 

@include('emails.status_update_footer')

</x-mail::message>
