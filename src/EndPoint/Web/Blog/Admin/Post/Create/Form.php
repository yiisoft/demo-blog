<?php

declare(strict_types=1);

namespace App\EndPoint\Web\Blog\Admin\Post\Create;

use App\Blog\Application\CreatePost\Command;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostStatus;
use App\Blog\Domain\Post\PostTitle;
use App\User\Domain\UserId;
use DateTimeImmutable;
use Yiisoft\FormModel\Attribute\Safe;
use Yiisoft\FormModel\FormModel;
use Yiisoft\Hydrator\Attribute\Parameter\ToDateTime;
use Yiisoft\Hydrator\Attribute\Parameter\Trim;
use Yiisoft\Strings\Inflector;
use Yiisoft\Validator\Result;
use Yiisoft\Validator\Rule\Callback;
use Yiisoft\Validator\Rule\Length;
use Yiisoft\Validator\Rule\Required;

use function sprintf;

final class Form extends FormModel
{
    #[Trim]
    #[Required]
    #[Length(max: PostTitle::LENGTH_LIMIT)]
    public string $title = '';

    #[Trim]
    #[Safe]
    public string $body = '';

    #[Trim]
    #[Length(max: PostSlug::LENGTH_LIMIT)]
    public string $slug = '';

    #[Safe]
    public PostStatus $status = PostStatus::Draft;

    #[Safe]
    #[ToDateTime(format: 'php:Y-m-d')]
    #[Callback(method: 'validatePublicationDate')]
    public ?DateTimeImmutable $publicationDate = null;

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
    public function createCommand(UserId $currentUserId, Inflector $inflector): Command
    {
        if ($this->slug === '') {
            $this->slug = mb_substr($inflector->toSlug($this->title), 0, PostSlug::LENGTH_LIMIT);
        }
        return new Command(
            title: new PostTitle($this->title),
            body: $this->body,
            slug: new PostSlug($this->slug),
            status: $this->status,
            publicationDate: $this->publicationDate,
            createdBy: $currentUserId,
        );
    }
}
