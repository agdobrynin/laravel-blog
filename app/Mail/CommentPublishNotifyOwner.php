<?php

namespace App\Mail;

use App\Enums\LocaleEnums;
use App\Models\BlogPost;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CommentPublishNotifyOwner extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    public function __construct(public readonly Comment $comment, public readonly LocaleEnums $localeEnums)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('Добавлен новый комментарий', locale: $this->localeEnums->value),
            tags: ['new', 'comment']
        );
    }

    public function content(): Content
    {
        $markdown = match ($this->comment->commentable_type) {
            BlogPost::class => 'emails.comment.'.$this->localeEnums->value.'.new-post-markdown',
            User::class => 'emails.comment.'.$this->localeEnums->value.'.new-profile-markdown'
        };

        return new Content(
            markdown: $markdown,
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
