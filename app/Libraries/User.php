<?php declare(strict_types = 1);

namespace App\Libraries;

use App\Entities\User as EntitiesUser;

class User
{
    public function getRowTemplate() : string
    {
        return Templates::wrapHtml($this->toRow(new EntitiesUser()));
    }

    public function toRow(EntitiesUser $user, int $index = 0) : string
    {
        return view(
            'components/user_as_row',
            ['user' => $user, 'showButtons' => true, 'index' => $index]
        );
    }
}
