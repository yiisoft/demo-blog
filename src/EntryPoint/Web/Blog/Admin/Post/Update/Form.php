<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Update;

use App\Blog\Application\UpdatePost\Command;
use App\Blog\Domain\Post\Post;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\User\Domain\UserId;
use DateTimeImmutable;
use Yiisoft\FormModel\Attribute\Safe;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Hydrator\Attribute\Parameter\ToDateTime;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

use function sprintf;

final class Form extends FormModel
{
    #[Required]
    #[Length(max: PostTitle::LENGTH_LIMIT)]
    public string $title;

    #[Safe]
    public string $body;

    #[Safe]
    public PostStatus $status;

    #[Safe]
    #[ToDateTime(format: 'php:Y-m-d')]
    #[Callback(method: 'validatePublicationDate')]
    public ?DateTimeImmutable $publicationDate;

    public function __construct(
        public readonly Post $post,
    ) {
        $this->title = (string) $this->post->title;
        $this->body = $this->post->body;
        $this->status = $this->post->status;
        $this->publicationDate = $this->post->publicationDate;
    }

    public function validatePublicationDate(mixed $value): Result
    {
        if ($this->status === PostStatus::Published && $value === null) {
            return new Result()->addError(
                sprintf('Publication date is required when status is "%s".', PostStatus::Published->label()),
            );
        }
        return new Result();
    }

    /**
     * @psalm-suppress ArgumentTypeCoercion
     */
    public function createCommand(UserId $updatedBy): Command
    {
        return new Command(
            id: $this->post->id,
            title: new PostTitle($this->title),
            body: $this->body,
            status: $this->status,
            publicationDate: $this->publicationDate,
            updatedBy: $updatedBy,
        );
    }
}
