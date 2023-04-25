<?php

namespace App\Mail;

use App\Enums\QueueNamesEnum;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentPostedOnWatchedPost extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    /**
     * Create a new message instance.
     */
    public function __construct(public readonly User $user, public readonly Comment $comment)
    {
        $this->onQueue(QueueNamesEnum::LOW->value);
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('Добавлен новый комментарий к публикации на которую Вы оставили комментарий'),
            tags: ['new', 'comment', 'watched', 'subscription']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.comment.ru.new-watched-markdown',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
