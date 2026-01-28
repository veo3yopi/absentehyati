<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;

class CreateTeacher extends CreateRecord
{
    protected static string $resource = TeacherResource::class;

    protected array $userData = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->userData = [
            'email' => $data['user_email'],
            'name' => $data['user_name'] ?? null,
            'password' => $data['user_password'],
        ];

        unset($data['user_email'], $data['user_name'], $data['user_password'], $data['user_password_confirmation']);

        return $data;
    }

    protected function afterCreate(): void
    {
        User::create([
            'name' => $this->userData['name'] ?: $this->record->name,
            'email' => $this->userData['email'],
            'password' => $this->userData['password'],
            'teacher_id' => $this->record->id,
        ]);
    }
}
