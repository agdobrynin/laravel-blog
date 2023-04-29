<?php

namespace App\Mail;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentPostedOnWatchedPost extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly User $user, public readonly Comment $comment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('Добавлен новый комментарий к публикации на которую Вы оставили комментарий'),
            tags: ['new', 'comment', 'watched', 'subscription']
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.comment.ru.new-watched-markdown',
        );
    }

    /**
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
