<?php

namespace App\Mail;

use App\Models\BlogPost;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyBlogPostAddedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $theme = 'default-with-avatar';

    public function __construct(public readonly BlogPost $post)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: trans('Добавлен новый пост от пользователя :user', ['user' => $this->post->user->name]),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.post.ru.post-added-markdown',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
