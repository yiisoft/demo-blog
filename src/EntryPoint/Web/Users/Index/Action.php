<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users\Index;

use App\User\Domain\Login;
use App\User\Domain\UserName;
use App\User\Domain\UserId;
use App\User\Domain\UserStatus;
use App\Web\ResponseFactory\ResponseFactory;
use Psr\Http\Message\ResponseInterface;
use Yiisoft\Data\Db\QueryDataReader;
use Yiisoft\Data\Reader\Sort;
use Yiisoft\Db\Connection\ConnectionInterface;

final readonly class Action
{
    public function __construct(
        private ConnectionInterface $db,
        private ResponseFactory $responseFactory,
    ) {}

    public function __invoke(): ResponseInterface
    {
        $dataReader = new QueryDataReader(
            $this->db->createQuery()
                ->select(['id', 'login', 'name', 'status'])
                ->from('user')
                ->resultCallback(
                    static function (array $rows): array {
                        /**
                         * @var non-empty-list<array{
                         *     id: string,
                         *     login: non-empty-string,
                         *     name: non-empty-string,
                         *     status: string,
                         * }> $rows
                         */
                        return array_map(
                            static fn(array $row) => new User(
                                id: UserId::fromString($row['id']),
                                login: new Login($row['login']),
                                name: new UserName($row['name']),
                                status: UserStatus::from((int) $row['status']),
                            ),
                            $rows,
                        );
                    },
                ),
            Sort::only(['id', 'login', 'name', 'status'])->withOrder(['id' => 'desc']),
        );

        return $this->responseFactory->render(
            __DIR__ . '/template.php',
            [
                'dataReader' => $dataReader,
            ],
        );
    }
}
