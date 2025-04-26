@extends('layouts.app')

@section('content')
<div class="main-container">
  <div class="content-wrapper">
    <div class="snf">
      <h1> Results for <span class="pink" >"{{$si}}"</span></h1>
      <br/>
      <div class="t_grids">
        @cache('cache_key', 60)
        @foreach($products as $onewelcome)
        <div class="one_e">
          <ul>
            <li class="mypimage"><img src="{{asset('storage/' . $onewelcome->image)}}"></li>
            <li class="noe">{{$onewelcome['name']}}</li>
            <div class="toe"><i class="fa-solid fa-location-dot"></i> {{$onewelcome['location']}}</div>
            <div class="toe"><i class="fa-solid fa-calendar-days"></i> {{$onewelcome['date']}}</div>
            <div class="toe"><i class="fa-solid fa-ticket"></i> Starting @5000</div>
            <button class="b_t"><a href="/events/{{$onewelcome['name']}}">View Event</a></button>
          </ul>
        </div>
        @endforeach
        @endcache
      </div>
    </div>
  </div>
</div>
@endsection
