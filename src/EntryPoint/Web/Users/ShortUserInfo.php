<?php

declare(strict_types=1);

namespace App\EntryPoint\Web\Users;

use App\User\Domain\User;
use Yiisoft\Html\Html;
use Yiisoft\Widget\Widget;

final class ShortUserInfo extends Widget
{
    public function __construct(
        private readonly User $user,
    ) {}

    public function render(): string
    {
        $id = Html::encode($this->user->id);
        $login = Html::encode($this->user->login);
        return <<<HTML
            <table class="table table-bordered mb-4">
                <thead class="table-light">
                    <tr>
                        <th colspan="2">User Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>ID</th>
                        <td>$id</td>
                    </tr>
                    <tr>
                        <th>Login</th>
                        <td>$login</td>
                    </tr>
                </tbody>
            </table>
            HTML;
    }
}
