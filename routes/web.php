<?php

use App\Mail\InquiryReply;
use App\Mail\RequestLoginIdMail;
use App\Modules\Admin\Inquiry\Models\Inquiry;
use App\Modules\Articles\Models\Article;
use App\Modules\Articles\Models\RemandArticle;
use App\Modules\User\Models\User;
use App\Notifications\ApprovedArticleNotification;
use App\Notifications\ChangePasswordNotification;
use App\Notifications\ContributorApplicationApproved;
use App\Notifications\DeleteApprovedNotification;
use App\Notifications\EditApprovedNotification;
use App\Notifications\NewUserNotification;
use App\Notifications\RemandArticleNotification;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('notifications', function() {
    $article = Inquiry::first();
    // return $article;
    return (new InquiryReply( $article ));
    ;
});