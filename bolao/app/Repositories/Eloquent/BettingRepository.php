<?php

namespace App\Repositories\Eloquent;

use App\Betting;
use App\Repositories\Contracts\BettingRepositoryInterface;

class BettingRepository extends AbstractRepository implements BettingRepositoryInterface
{

    protected $model = Betting::class;

    public function create(array $data):Bool
    {
        //usuario que está logado
        $user = Auth()->user();
        $data['user_id'] = $user->id;
        return (bool) $this->model->create($data);
    }

    public function update(array $data, int $id):Bool
    {
        $register = $this->find($id);
        if ($register) {
            $user = Auth()->user();
            $data['user_id'] = $user->id;
            return (bool) $register->update($data);
        } else {
            return false;
        }
    }

}
