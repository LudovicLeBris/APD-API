<?php

namespace App\Domain\AppUser\Entity;

class Role
{
    static $appUser = 'appUser';
    static $admin = 'admin';

    static $all = [];
}

Role::$all = [Role::$appUser, Role::$admin];