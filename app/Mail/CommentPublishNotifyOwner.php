<?php

namespace App\Mail;

use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentPublishNotifyOwner extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly Comment $comment)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('Добавлен новый комментарий'),
            tags: ['new', 'comment']
        );
    }

    public function content(): Content
    {
        $markdown = match ($this->comment->commentable_type) {
            BlogPost::class => 'emails.comment.ru.new-post-markdown',
            User::class => 'emails.comment.ru.new-profile-markdown'
        };

        return new Content(
            markdown: $markdown,
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
