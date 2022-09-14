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

    public function rounds($betting_id, BettingRepositoryInterface $bettingRepository)
    {
        $columnList = ['id'=>'#',
            'title'=>trans('bolao.title'),
            'betting_title'=>trans('bolao.bet'),
            'date_start_site'=>trans('bolao.date_start'),
            'date_end_site'=>trans('bolao.date_end')
        ];
        $page = trans('bolao.round_list');

        $list = $bettingRepository->rounds($betting_id);

        if (!$list) {
            return redirect(route('principal').'#portfolio');
        }

        $breadcrumb = [
            (object)['url'=>route('principal').'#portfolio','title'=>trans('bolao.betting_list')],
            (object)['url'=>'','title'=>trans('bolao.list',['page'=>$page])],
        ];

        return view('site.rounds',compact('list','page','columnList','breadcrumb'));
    }
}
