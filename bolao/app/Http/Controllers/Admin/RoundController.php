<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\RoundRepositoryInterface;
use Validator;

class RoundController extends Controller
{

    private $route = 'rounds';
    private $paginate = 7;
    private $search = ['title'];
    private $model;

    public function __construct(RoundRepositoryInterface $model)
    {
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columnList = [
            'id'=>'#',
            'title'=>trans('bolao.title'),
            'betting_title'=>trans('bolao.betting_title'),
            'date_start_site'=>trans('bolao.date_start'),
            'date_end_site'=>trans('bolao.date_end')
        ];
        $page = trans('bolao.round_list');

        $search = "";
        if(isset($request->search)){
          $search = $request->search;
          $list = $this->model->findWhereLike($this->search,$search,'id','DESC');
        }else{
          $list = $this->model->paginate($this->paginate,'id','DESC');
        }

        $routeName = $this->route;

        $breadcrumb = [
            (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
            (Object)['url'=>'', 'title'=>trans('bolao.list', ['page'=>$page])],
        ];

        return view('admin.'.$routeName.'.index',compact('list','search','page','routeName','columnList', 'breadcrumb'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page = trans('bolao.round_list');
        $page_create = trans('bolao.round');

        $routeName = $this->route;

        $user = auth()->user();
        $listRel = $user->bettings()->orderBy('id','desc')->get();

        $breadcrumb = [
            (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
            (Object)['url'=>route($routeName.".index"), 'title'=>trans('bolao.list', ['page'=>$page])],
            (Object)['url'=>'', 'title'=>trans('bolao.create_crud',['page'=>$page_create])],
        ];

        return view('admin.'.$routeName.'.create',compact('page', 'page_create', 'routeName', 'breadcrumb', 'listRel'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|max:255',
            'betting_id' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ])->validate();

        if ($this->model->create($data)) {
            session()->flash('msg', trans('bolao.record_added_successfully'));
            session()->flash('status', 'success'); // success error notification
            return redirect()->back();
        } else {
            session()->flash('msg', trans('bolao.error_adding_registry'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->back();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $routeName = $this->route;
        $register = $this->model->find($id);
        if($register){
            $page = trans('bolao.round_list');
            $page2 = trans('bolao.round');

            $breadcrumb = [
                (object)['url'=>route('home'),'title'=>trans('bolao.home')],
                (object)['url'=>route($routeName.".index"),'title'=>trans('bolao.list',['page'=>$page])],
                (object)['url'=>'','title'=>trans('bolao.show_crud',['page'=>$page2])],
            ];
            $delete = false;
            if($request->delete ?? false){
                session()->flash('msg', trans('bolao.delete_this_record'));
                session()->flash('status', 'notification'); // success error notification
                $delete = true;
            }

            return view('admin.'.$routeName.'.show',compact('register','page','page2','routeName','breadcrumb','delete'));
        }

        return redirect()->route($routeName.'.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $routeName = $this->route;
        $register = $this->model->find($id);
        if ($register) {

            $page = trans('bolao.round_list');
            $page2 = trans('bolao.round');

            $user = auth()->user();
            $listRel = $user->bettings()->orderBy('id','desc')->get();
            $register_id = $register->betting_id;

            $breadcrumb = [
                (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
                (Object)['url'=>route($routeName.".index"), 'title'=>trans('bolao.list', ['page'=>$page])],
                (Object)['url'=>'', 'title'=>trans('bolao.edit_crud',['page'=>$page2])],
            ];

            return view('admin.'.$routeName.'.edit',compact('register', 'page', 'page2', 'routeName', 'breadcrumb', 'listRel', 'register_id'));

        }

        return redirect()->route($routeName.'.index');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        Validator::make($data, [
            'title' => 'required|string|max:255',
            'betting_id' => 'required',
            'date_start' => 'required',
            'date_end' => 'required',
        ])->validate();

        if ($this->model->update($data, $id)) {
            session()->flash('msg', trans('bolao.successfully_edited_record'));
            session()->flash('status', 'success');
            return redirect()->back();
        } else {
            session()->flash('msg', trans('bolao.error_editing_record'));
            session()->flash('status', 'error');
            return redirect()->back();
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        if ($this->model->delete($id)) {
            session()->flash('msg', trans('bolao.registration_deleted_successfully'));
            session()->flash('status', 'success'); // success error notification
        } else {
            session()->flash('msg', trans('bolao.error_deleting_record'));
            session()->flash('status', 'error'); // success error notification
        }

        $routeName = $this->route;
        return redirect()->route($routeName.'.index');
    }
}
