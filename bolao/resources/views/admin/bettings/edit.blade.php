@extends('layouts.app')

@section('content')

    @page_component(['col'=>12, 'page'=>__('bolao.edit_crud',['page'=>$page2])])

        @alert_component(['msg'=>session('msg'), 'status'=>session('status')])
        @endalert_component

        @breadcrumb_component(['page'=>$page, 'items'=>$breadcrumb ?? []])
        @endbreadcrumb_component



        @form_component(['action'=>route($routeName.".update",$register->id), 'method'=>"PUT"])
            @include('admin.'.$routeName.'.form')
            <button class="btn btn-primary btn-md float-right">@lang('bolao.edit')</button>
        @endform_component

    @endpage_component
@endsection
