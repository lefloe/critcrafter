<?php

namespace App\Filament\Auth\Player;

use Filament\Auth\Pages\Register as BaseRegister;

class Register extends BaseRegister
{
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        $data['role'] = 'user';
        return $data;

    }

}
