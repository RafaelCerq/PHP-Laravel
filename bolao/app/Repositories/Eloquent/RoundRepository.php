<?php

namespace App\Repositories\Eloquent;

use App\Round;
use App\Repositories\Contracts\RoundRepositoryInterface;

class RoundRepository extends AbstractRepository implements RoundRepositoryInterface
{

    protected $model = Round::class;

}
