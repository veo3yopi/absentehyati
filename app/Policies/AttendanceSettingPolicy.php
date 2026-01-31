<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AttendanceSetting;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttendanceSettingPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AttendanceSetting');
    }

    public function view(AuthUser $authUser, AttendanceSetting $attendanceSetting): bool
    {
        return $authUser->can('View:AttendanceSetting');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AttendanceSetting');
    }

    public function update(AuthUser $authUser, AttendanceSetting $attendanceSetting): bool
    {
        return $authUser->can('Update:AttendanceSetting');
    }

    public function delete(AuthUser $authUser, AttendanceSetting $attendanceSetting): bool
    {
        return $authUser->can('Delete:AttendanceSetting');
    }

    public function restore(AuthUser $authUser, AttendanceSetting $attendanceSetting): bool
    {
        return $authUser->can('Restore:AttendanceSetting');
    }

    public function forceDelete(AuthUser $authUser, AttendanceSetting $attendanceSetting): bool
    {
        return $authUser->can('ForceDelete:AttendanceSetting');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AttendanceSetting');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AttendanceSetting');
    }

    public function replicate(AuthUser $authUser, AttendanceSetting $attendanceSetting): bool
    {
        return $authUser->can('Replicate:AttendanceSetting');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AttendanceSetting');
    }

}