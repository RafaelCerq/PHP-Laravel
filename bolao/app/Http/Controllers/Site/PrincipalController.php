<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\BettingRepositoryInterface;


class PrincipalController extends Controller
{

    public function index(BettingRepositoryInterface $bettingRepository)
    {
        $list = $bettingRepository->list();
        return view('site.index', compact('list'));
    }

    public function sign($id, BettingRepositoryInterface $bettingRepository)
    {
        $bettingRepository->BettingUser($id);
        return redirect(route('principal').'#portfolio');
    }

    public function signNoLogin($id)
    {
        return redirect(route('principal').'#portfolio');
    }
}
