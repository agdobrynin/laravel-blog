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

class CommentPostedOnWatchedCommentable extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly User $user,
        public readonly Comment $comment,
        public readonly LocaleEnums $localeEnums
    )
    {
    }

    public function envelope(): Envelope
    {
        $subject = match ($this->comment->commentable_type) {
            User::class => trans(
                    'Новый комментарий под профилем :user в котором Вы оставили свой комментарий',
                    ['user' => $this->comment->commentable->name],
                    $this->localeEnums->value
                    ),
            BlogPost::class => trans(
                'Добавлен новый комментарий к публикации на которую Вы оставили комментарий',
                    locale: $this->localeEnums->value
                ),
        };

        return new Envelope(
            subject: $subject,
            tags: ['new', 'comment', 'watched', 'subscription']
        );
    }

    public function content(): Content
    {
        $markdown = match ($this->comment->commentable_type) {
            User::class => 'emails.comment.'.$this->localeEnums->value.'.new-watched-profile-markdown',
            BlogPost::class => 'emails.comment.'.$this->localeEnums->value.'.new-watched-post-markdown',
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
