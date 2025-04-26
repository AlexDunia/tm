@extends('layouts.app')

@section('content')
<div class="main-container">
  <div class="content-wrapper">
    <div class="snf">
      <h1>Events > <span class="pink">{{$cat}}</span></h1>
      <br/>
      <div class="t_grids">
        @foreach($cat_result as $onewelcome)
        <div class="one_e">
          <ul>
            <li class="mypimage"><img src="{{ str_starts_with($onewelcome->image, 'http') ? $onewelcome->image : asset('storage/' . $onewelcome->image) }}"></li>
            <li class="noe">{{$onewelcome->name}}</li>
            <div class="toe"><i class="fa-solid fa-location-dot"></i> {{$onewelcome->location}}</div>
            <div class="toe"><i class="fa-solid fa-calendar-days"></i> {{ date('n/j/Y', strtotime($onewelcome->date)) }}</div>
            <div class="toe"><i class="fa-solid fa-ticket"></i> Starting @5000</div>
            <button class="b_t"><a href="/events/{{$onewelcome->name}}">View Event</a></button>
          </ul>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>
@endsection


