@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="content-wrapper">
        <div class="blurcontainer">
            <div class="bcflex">
                <div class="bctext">
                    <p class="cat">Category: {{$listonee['category']}}</p>
                    <h1>{{$listonee['name']}}</h1>
                    <p class="infoticket">{{$listonee['description']}}</p>
                    <div id="countdown"></div>
                    <p class="infodate"><i class="fa-solid fa-calendar-day"></i> {{$listonee['location']}}</p>
                </div>

                <div class="bcimg">
                    <img src="{{ str_starts_with($listonee->image, 'http') ? $listonee->image : asset('storage/' . $listonee->image) }}">
                </div>
            </div>
        </div>

        <div class="cdbg">
            <div class="expired">
                <h1 id="days">Expired!</h1>
            </div>
        </div>
    </div>
</div>
@endsection
