<?php

namespace App\Mail;

use App\Enums\LocaleEnums;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyBlogPostAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    public function __construct(
        public readonly BlogPost    $post,
        public readonly LocaleEnums $localeEnums,
        public readonly User        $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans(
                'Добавлен новый пост от пользователя :user',
                ['user' => $this->post->user->name],
                $this->localeEnums->value
            ),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.post.' . $this->localeEnums->value . '.post-added-markdown',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
