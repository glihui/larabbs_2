<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function saving(User $user)
    {
        // 这样写扩展性更高，只有空的时候才指定默认头像
        if (empty($user->avatar)) {
            $user->avatar = 'https://hello.guolh.com/uploads/images/avatars/201806/01/7_1527862791_EBum4btIS3.jpeg';
        }
    }
}