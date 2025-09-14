<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Blog\Admin\Post\Update;

use App\Blog\Application\UpdatePost\Command;
use App\Blog\Domain\Post\Post;
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
    public string $title;

    #[Trim]
    #[Safe]
    public string $body;

    #[Trim]
    #[Length(max: PostSlug::LENGTH_LIMIT)]
    public string $slug;

    #[Safe]
    public PostStatus $status;

    #[Safe]
    #[ToDateTime(format: 'php:Y-m-d')]
    #[Callback(method: 'validatePublicationDate')]
    public ?DateTimeImmutable $publicationDate;

    public function __construct(
        public readonly Post $post,
    ) {
        $this->title = $this->post->title->toString();
        $this->body = $this->post->body;
        $this->slug = $this->post->slug->toString();
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
    public function createCommand(UserId $updatedBy, Inflector $inflector): Command
    {
        if ($this->slug === '') {
            $this->slug = mb_substr($inflector->toSlug($this->title), 0, PostSlug::LENGTH_LIMIT);
        }
        return new Command(
            id: $this->post->id,
            title: new PostTitle($this->title),
            body: $this->body,
            slug: new PostSlug($this->slug),
            status: $this->status,
            publicationDate: $this->publicationDate,
            updatedBy: $updatedBy,
        );
    }
}
