<?php

namespace App\Mail;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentPublish extends Mailable
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
        return new Content(
            markdown: 'emails.comment.ru.new-post-markdown',
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
