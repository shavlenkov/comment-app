<?php

namespace App\Listeners;

use App\Events\CommentReplied;
use App\Mail\CommentRepliedMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;



class SendMailCommentRepliedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(CommentReplied $event): void
    {
        Mail::to($event->answer->comment->user->email)->send(new CommentRepliedMail($event->answer));
    }
}
