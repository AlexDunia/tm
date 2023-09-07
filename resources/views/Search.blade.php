<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="stylesheet"
        href="\css\admin.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>

  @include('_nav')

  <div class="snf">
  <h1> Results for <span class="pink" >  "{{$si}}" </span> </h1>
  <br/>
  <div class="t_grids">
    @cache('cache_key', 60)
    @foreach($products as $onewelcome)
    {{-- <a href="/events/{{$onewelcome['id']}}"> {{$onewelcome['name']}} </a>
    <form action="{{url('/addtocart', $onewelcome->id)}}" method="POST">
        @csrf
        <input type="submit" value="Add to cart!"/>
    </form>
    <br/> --}}
<div class="one_e">
<ul>
<li class="mypimage">  <img  src="{{asset('storage/' . $onewelcome->image)}}"> </li>
<li class="noe">   {{$onewelcome['name']}}  </li>
  <div class="toe"> <i class="fa-solid fa-location-dot"> </i> {{$onewelcome['location']}} </div>
  <div class="toe"> <i class="fa-solid fa-calendar-days"></i>  {{$onewelcome['date']}} </div>

 {{-- <div class="toe"> <i class="fa-solid fa-calendar-days"></i> 15 December, 6:30pm </div> --}}

 <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>

 <button class="b_t"> <a href="/events/{{$onewelcome['name']}}"> View Event </a>  </button>
</ul>
</div>

@endforeach
@endcache
</div>

  </div>
  <br/>
<br/>
  @include('_footer')


</body>