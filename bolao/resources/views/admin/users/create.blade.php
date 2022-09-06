@extends('layouts.app')

@section('content')

    @page_component(['col'=>12, 'page'=>__('bolao.create_crud',['page'=>$page_create])])

        @alert_component(['msg'=>session('msg'), 'status'=>session('status')])
        @endalert_component

        @breadcrumb_component(['page'=>$page, 'items'=>$breadcrumb ?? []])
        @endbreadcrumb_component



        @form_component(['action'=>$routeName.".store", 'method'=>"POST"])
            <div class="row">
                <div class="form-group col-6">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" value="">
                </div>
                <div class="form-group col-6">
                    <label for="email">E-Mail</label>
                    <input type="mail" class="form-control" name="email" value="">
                </div>
                <div class="form-group col-6">
                    <label for="password">Senha</label>
                    <input type="password" class="form-control" name="password" value="">
                </div>
                <div class="form-group col-6">
                    <label for="password_confirmation">Confirmar Senha</label>
                    <input type="password" class="form-control" name="password_confirmation" value="">
                </div>
            </div>
            <button class="btn btn-primary btn-md float-right">Adicionar</button>
        @endform_component

    @endpage_component
@endsection
