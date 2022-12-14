<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PermissionRepositoryInterface;
use Validator;

class PermissionController extends Controller
{

    private $route = 'permissions';
    private $paginate = 7;
    private $search = ['name','description'];
    private $model;

    public function __construct(PermissionRepositoryInterface $model)
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
        $columnList = ['id'=>'#','name'=>trans('bolao.name'),'description'=>trans('bolao.description')];
        $page = trans('bolao.permission_list');

        $search = "";
        if(isset($request->search)){
          $search = $request->search;
          $list = $this->model->findWhereLike($this->search,$search,'id','DESC');
        }else{
          $list = $this->model->paginate($this->paginate,'id','DESC');
        }

        $routeName = $this->route;

        //$request->session()->flash('msg', 'Olá Alert');
        //$request->session()->flash('status', 'success'); // success error notification

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
        $page = trans('bolao.permission_list');
        $page_create = trans('bolao.permission');

        $routeName = $this->route;

        $breadcrumb = [
            (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
            (Object)['url'=>route($routeName.".index"), 'title'=>trans('bolao.list', ['page'=>$page])],
            (Object)['url'=>'', 'title'=>trans('bolao.create_crud',['page'=>$page_create])],
        ];

        return view('admin.'.$routeName.'.create',compact('page', 'page_create', 'routeName', 'breadcrumb'));
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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:355',
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
            $page = trans('bolao.permission_list');
            $page2 = trans('bolao.permission');

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

            $page = trans('bolao.permission_list');
            $page2 = trans('bolao.permission');

            $breadcrumb = [
                (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
                (Object)['url'=>route($routeName.".index"), 'title'=>trans('bolao.list', ['page'=>$page])],
                (Object)['url'=>'', 'title'=>trans('bolao.edit_crud',['page'=>$page2])],
            ];

            return view('admin.'.$routeName.'.edit',compact('register', 'page', 'page2', 'routeName', 'breadcrumb'));

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
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:355',
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
