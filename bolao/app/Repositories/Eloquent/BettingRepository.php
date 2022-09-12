<?php

namespace App\Repositories\Eloquent;

use App\Betting;
use App\Repositories\Contracts\BettingRepositoryInterface;

class BettingRepository extends AbstractRepository implements BettingRepositoryInterface
{

    protected $model = Betting::class;

}
