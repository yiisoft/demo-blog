<?php

declare(strict_types=1);

namespace App\EndPoint\Console\FakeData;

use App\Blog\Domain\Category\Category;
use App\Blog\Domain\Category\CategoryId;
use App\Blog\Domain\Category\CategoryName;
use App\Blog\Domain\Category\CategoryRepositoryInterface;
use App\Blog\Domain\Category\CategorySlug;
use App\Blog\Domain\Post\Post;
use App\Blog\Domain\Post\PostId;
use App\Blog\Domain\Post\PostRepositoryInterface;
use App\Blog\Domain\Post\PostSlug;
use App\Blog\Domain\Post\PostTitle;
use App\EndPoint\Site\Shared\Access\RbacManager;
use App\EndPoint\Site\Shared\Access\Role;
use App\Shared\Infrastructure\Database\Table;
use App\User\Domain\AuthKeyGeneratorInterface;
use App\User\Domain\Login;
use App\User\Domain\Password;
use App\User\Domain\PasswordHasherInterface;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserName;
use App\User\Domain\UserRepositoryInterface;
use DateTimeImmutable;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Db\Connection\ConnectionInterface;
use Yiisoft\Yii\Console\ExitCode;

#[AsCommand(
    name: 'fake-data',
    description: 'Fill database with fake data',
)]
final class Action extends Command
{
    private const ADMIN_LOGIN = 'admin';
    private const ADMIN_PASSWORD = 'q1w2e3r4';
    private const USERS_COUNT = 5;
    private const CATEGORIES_COUNT = 10;
    private const POSTS_COUNT = 23;

    private Generator $faker;

    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly CategoryRepositoryInterface $categoryRepository,
        private readonly PostRepositoryInterface $postRepository,
        private readonly PasswordHasherInterface $passwordHasher,
        private readonly AuthKeyGeneratorInterface $authKeyGenerator,
        private readonly ConnectionInterface $db,
        private readonly RbacManager $rbacManager,
    ) {
        parent::__construct();
        $this->faker = Factory::create();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->info('Clearing existing data...');
        $this->clearData();

        $io->info('Creating admin user...');
        $adminUser = $this->createAdministrator();

        $io->info('Creating ' . self::USERS_COUNT . ' fake users...');
        $users = $this->createFakeUsers();
        $users[] = $adminUser;

        $io->info('Creating ' . self::CATEGORIES_COUNT . ' fake categories...');
        $categories = $this->createFakeCategories();

        $io->info('Creating ' . self::POSTS_COUNT . ' fake posts...');
        $this->createFakePosts($users, $categories);

        $io->success('Fake data has been created successfully!');
        $io->table(['Type', 'Count'], [
            ['Administrator', '1 (login: ' . self::ADMIN_LOGIN . ', password: ' . self::ADMIN_PASSWORD . ')'],
            ['Editors', (string) self::USERS_COUNT],
            ['Categories', (string) self::CATEGORIES_COUNT],
            ['Posts', (string) self::POSTS_COUNT],
        ]);

        return ExitCode::OK;
    }

    private function clearData(): void
    {
        $this->db->createCommand()->delete(Table::POST_CATEGORY)->execute();
        $this->db->createCommand()->delete(Table::POST)->execute();
        $this->db->createCommand()->delete(Table::CATEGORY)->execute();
        $this->db->createCommand()->delete(Table::USER)->execute();
    }

    private function createAdministrator(): User
    {
        $user = new User(
            id: new UserId(Uuid::uuid4()),
            login: new Login('admin'),
            name: new UserName('Administrator'),
            password: new Password('q1w2e3r4'),
            passwordHasher: $this->passwordHasher,
            authKeyGenerator: $this->authKeyGenerator,
        );

        $this->userRepository->add($user);
        $this->rbacManager->changeRole($user->id, Role::Admin);

        return $user;
    }

    /**
     * @return list<User>
     */
    private function createFakeUsers(): array
    {
        $users = [];
        for ($i = 0; $i < self::USERS_COUNT; $i++) {
            /** @var non-empty-string $login */
            $login = $this->faker->unique()->userName;
            /** @var non-empty-string $name */
            $name = $this->faker->name;
            /** @var non-empty-string $password */
            $password = $this->faker->password(8, 12);
            $user = new User(
                id: new UserId(Uuid::uuid4()),
                login: new Login($login),
                name: new UserName($name),
                password: new Password($password),
                passwordHasher: $this->passwordHasher,
                authKeyGenerator: $this->authKeyGenerator,
            );

            $this->userRepository->add($user);
            $users[] = $user;
        }

        return $users;
    }

    /**
     * @return list<Category>
     */
    private function createFakeCategories(): array
    {
        $categories = [];
        for ($i = 0; $i < self::CATEGORIES_COUNT; $i++) {
            /** @var non-empty-string $name */
            $name = $this->faker->unique()->words(2, true);
            $slug = $this->createSlug($name);

            $category = new Category(
                id: new CategoryId(Uuid::uuid4()),
                name: new CategoryName($name),
                description: $this->faker->sentence(10),
                slug: new CategorySlug($slug),
            );

            $this->categoryRepository->add($category);
            $categories[] = $category;
        }

        return $categories;
    }

    /**
     * @param list<User> $users
     * @param list<Category> $categories
     */
    private function createFakePosts(array $users, array $categories): void
    {
        for ($i = 0; $i < self::POSTS_COUNT; $i++) {
            /** @var non-empty-string $title */
            $title = $this->faker->sentence(6, false);
            $slug = $this->createSlug($title);
            $user = $users[array_rand($users)];

            /** @var list<Category> $selectedCategories */
            $selectedCategories = $this->faker->randomElements(
                $categories,
                $this->faker->numberBetween(1, 3),
            );
            $categoryIds = array_map(
                static fn(Category $category) => $category->id,
                $selectedCategories,
            );

            /** @var string $body */
            $body = $this->faker->paragraphs(5, true);

            $post = new Post(
                id: new PostId(Uuid::uuid4()),
                title: new PostTitle($title),
                body: $body,
                slug: new PostSlug($slug),
                publicationDate: $this->faker->boolean(80) ? new DateTimeImmutable() : null,
                createdBy: $user->id,
                categoryIds: $categoryIds,
            );

            if ($post->publicationDate !== null) {
                $post->publish();
            }

            $this->postRepository->add($post);
        }
    }

    /**
     * @return non-empty-string
     */
    private function createSlug(string $text): string
    {
        /** @var non-empty-string */
        return strtolower(
            substr(
                trim((string) preg_replace('/[^A-Za-z0-9-]+/', '-', $text), '-'),
                0,
                50,
            ),
        );
    }
}
