<?php

namespace App\Filament\Resources\Teachers\Pages;

use App\Filament\Resources\Teachers\TeacherResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeacher extends EditRecord
{
    protected static string $resource = TeacherResource::class;

    protected array $userData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = $this->record->user;

        if ($user) {
            $data['user_email'] = $user->email;
            $data['user_name'] = $user->name;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->userData = [
            'email' => $data['user_email'],
            'name' => $data['user_name'] ?? null,
            'password' => $data['user_password'] ?? null,
        ];

        unset($data['user_email'], $data['user_name'], $data['user_password'], $data['user_password_confirmation']);

        return $data;
    }

    protected function afterSave(): void
    {
        $user = $this->record->user;

        if (! $user) {
            User::create([
                'name' => $this->userData['name'] ?: $this->record->name,
                'email' => $this->userData['email'],
                'password' => $this->userData['password'],
                'teacher_id' => $this->record->id,
            ]);
            return;
        }

        $payload = [
            'name' => $this->userData['name'] ?: $this->record->name,
            'email' => $this->userData['email'],
        ];

        if ($this->userData['password']) {
            $payload['password'] = $this->userData['password'];
        }

        $user->update($payload);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
