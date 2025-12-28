<?php

declare(strict_types=1);

namespace App\Presentation\Console\CreateAdmin;

use App\Application\User\CreateUser\Handler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Yii\Console\ExitCode;

#[AsCommand(
    name: 'user:create-admin',
    description: 'Create a new administrator',
)]
final class Action extends Command
{
    public function __construct(
        private readonly Handler $commandHandler,
        private readonly ValidatorInterface $validator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('login', InputArgument::REQUIRED)
            ->addArgument('password', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $model = new Model($input);

        $errors = $this->validator->validate($model)->getErrorMessages();
        if (!empty($errors)) {
            foreach ($errors as $message) {
                $io->error($message);
            }
            return ExitCode::DATAERR;
        }

        $result = $this->commandHandler->handle($model->createCommand());

        $io->success('Created user "' . $result->id . '".');
        return ExitCode::OK;
    }
}
