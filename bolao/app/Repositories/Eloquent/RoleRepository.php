<?php

namespace App\Repositories\Eloquent;

use App\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{

    protected $model = Role::class;

}
