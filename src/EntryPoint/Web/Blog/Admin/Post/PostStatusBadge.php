<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post;

use App\Blog\Domain\Post\PostStatus;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class PostStatusBadge extends Widget
{
    public function __construct(
        private readonly PostStatus $status,
    ) {}

    public function render(): string
    {
        $badgeClass = match ($this->status) {
            PostStatus::Draft,
            PostStatus::Archived => 'bg-secondary',
            PostStatus::Published => 'bg-success',
        };
        return Html::span($this->status->label(), ['class' => ['badge', $badgeClass]])->render();
    }
}
