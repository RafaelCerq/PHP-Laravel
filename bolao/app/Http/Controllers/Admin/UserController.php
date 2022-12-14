<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UserRepositoryInterface;
use Validator;
use Illuminate\Validation\Rule;
use App\Repositories\Contracts\RoleRepositoryInterface;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{

    private $route = 'users';
    private $paginate = 7;
    private $search = ['name','email'];
    private $model;
    private $modelRole;

    public function __construct(UserRepositoryInterface $model, RoleRepositoryInterface $modelRole)
    {
        $this->model = $model;
        $this->modelRole = $modelRole;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $this->authorize('list-user');

        if (Gate::denies('list-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

        $columnList = ['id'=>'#','name'=>trans('bolao.name'),'email'=>trans('bolao.email')];
        $page = trans('bolao.user_list');

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
        if (Gate::denies('create-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

        $page = trans('bolao.user_list');
        $page_create = trans('bolao.user');

        $routeName = $this->route;

        $roles = $this->modelRole->all('name', 'ASC');

        $breadcrumb = [
            (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
            (Object)['url'=>route($routeName.".index"), 'title'=>trans('bolao.list', ['page'=>$page])],
            (Object)['url'=>'', 'title'=>trans('bolao.create_crud',['page'=>$page_create])],
        ];

        return view('admin.'.$routeName.'.create',compact('page', 'page_create', 'routeName', 'breadcrumb', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Gate::denies('create-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

        $data = $request->all();

        Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
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
        if (Gate::denies('show-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

        $routeName = $this->route;
        $register = $this->model->find($id);
        if($register){
            $page = trans('bolao.user_list');
            $page2 = trans('bolao.user');

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
        if (Gate::denies('edit-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

        $routeName = $this->route;
        $register = $this->model->find($id);
        if ($register) {

            $page = trans('bolao.user_list');
            $page2 = trans('bolao.user');

            $roles = $this->modelRole->all('name', 'ASC');

            $breadcrumb = [
                (Object)['url'=>route('home'), 'title'=>trans('bolao.home')],
                (Object)['url'=>route($routeName.".index"), 'title'=>trans('bolao.list', ['page'=>$page])],
                (Object)['url'=>'', 'title'=>trans('bolao.edit_crud',['page'=>$page2])],
            ];

            return view('admin.'.$routeName.'.edit',compact('register', 'page', 'page2', 'routeName', 'breadcrumb', 'roles'));

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
        if (Gate::denies('edit-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

        $data = $request->all();

        if (!$data['password']) {
            // remove da lista
            unset($data['password']);
        }

        Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => ['required','string','email','max:255',Rule::unique('users')->ignore($id)],
            'password' => 'sometimes|required|string|min:6|confirmed',
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
        if (Gate::denies('delete-user')) {
            session()->flash('msg', trans('bolao.access_denied'));
            session()->flash('status', 'error'); // success error notification
            return redirect()->route('home');
        }

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
