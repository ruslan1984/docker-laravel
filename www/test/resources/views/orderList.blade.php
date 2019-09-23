@extends('layouts.index')
@section('content')
    <div class="page">
        <div class="orderList container">
            <h1>Список заказов</h1>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Имя</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">Откуда</th>
                    <th scope="col">Куда</th>
                </tr>
                </thead>
            @foreach($list as $key=>$item)
                    <tr>
                        <th scope="row">{{$key}}</th>
                        <td>{{$item->name}}</td>
                        <td>{{$item->phone}}</td>
                        <td>{{$item->address_from}} {{$item->coordinares_from}} </td>
                        <td>{{$item->address_to}} {{$item->coordinares_to}}</td>
                    </tr>
            @endforeach
            </table>
            <hr>
            <a class="btn btn-block btn-info" href="/">Страница заказа</a>
        </div>

    </div>

@endsection