@extends('layouts.index')
@section('content')
    <div class="page">
    <div class="order container">
        <h1>Ваш заказ</h1>
        <form class="orderForm" id="orderForm" action="{{asset('/')}}" method="POST">
            {{ csrf_field() }}
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Имя</label>
                    <input required name="name" class="form-control {{(!empty($errors['name'])) ? 'is-invalid' : '' }}" type="text" value="{{$data['name']}}">
                    <p class="errorText">{{$errors['name']}}</p>
                </div>
                <div class="form-group col-lg-4">
                    <label>Телефон</label>
                    <input required name="phone" id="phone1" class="form-control {{(!empty($errors['phone'])) ? 'is-invalid' : '' }}" type="text" value="{{$data['phone']}}">
                    <p class="errorText">{{$errors['phone']}}</p>
                </div>
                <div class="form-group  col-lg-4">
                    <label>Дата</label>
                    <input required class="form-control {{(!empty($errors['date'])) ? 'is-invalid' : '' }}" name="date" id="example-show-hide-callbacks" data-timepicker="true" type="text" readonly value="{{$data['date']}}">
                    <p class="errorText">{{$errors['date']}}</p>
                </div>
            </div>
            <div class="form-group">
                <label>Доп услуга</label>
                @php

                    if(isset($data['more_services'])){
                        $array=json_decode($data['more_services']);
                    }
                @endphp
                <select multiple form="orderForm" name="more_services[]" class="form-control">
                    @foreach($services as $item)
                        <option @if(isset($array)&&(in_array($item->code,$array))) selected @endif
                                value="{{$item->code}}">{{$item->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Адрес откуда</label>
                <div class="row">
                    <div class="col-sm-10">
                        <input required value="{{$data['address_from']}}" class="form-control {{(!empty($errors['address_from'])) ? 'is-invalid' : '' }}" name="address_from" type="text" id="address_from"  placeholder="Введите адрес"/>
                        <p id="noticeFrom" class="errorText">
                            {{$errors['adr_from']}}
                        </p>
                        <input hidden value="{{$data['coordinares_from']}}" id="coordinares_from" type="text" name="coordinares_from"/>
                    </div>
                    <div class="col-sm-2">
                        <div class="btn btn-primary buttonFrom">Проверить</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label>Адрес куда</label>
                <div class="row">
                    <div class="col-sm-10">
                        <input required value="{{$data['address_to']}}" class="form-control {{(!empty($errors['address_to'])) ? 'is-invalid' : '' }}" name ="address_to" type ="text" id="address_to" placeholder="Введите адрес">
                        <p id="noticeTo" class="errorText">{{$errors['adr_to']}}</p>
                        <input  hidden value="{{$data['coordinares_to']}}" id="coordinares_to" type="text" name="coordinares_to">
                    </div>
                    <div class="col-sm-2">
                        <div class="btn btn-primary buttonTo">Проверить</div>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="map" id="mapFrom"></div>
                    </div>
                    <div class="col-lg-6">
                        <div class="map" id="mapTo"></div>
                    </div>
                </div>
            </div>

            <input name="submit" class="btn btn-primary" type="submit" value="Заказать">
        </form>
        <hr>
        <a class="btn btn-block btn-info" href="/list">Список заказов</a>
    </div>
    </div>
@endsection