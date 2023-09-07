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
       <div class="circularbgg">

       {{-- <div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');"> --}}
       <div class="view">
      <div class="latest"> <h1> Trending in <span class="pink"> {{ $category }} </span>  </h1> </div>


        <div class="t_grids">
            @foreach($posts as $onewelcome)
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

      {{-- <div class="one_e">
        <ul>
        <li class="mypimage">  <img src="/images/asake.jpg" alt="Logo"/> </li>
          <li class="noe"> WIZKID LIVE IN CONCERT </li>
            <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

           <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>

           <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>

          <button class="b_t"> Buy Ticket </button>
          </ul>
        </div>

        <div class="one_e">
            <ul>
            <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
              <li class="noe"> WIZKID LIVE IN CONCERT </li>
                <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

               <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
               <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>

              <button class="b_t"> Buy Ticket </button>
              </ul>
            </div>

            <div class="one_e">
                <ul>
                <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                  <li class="noe"> WIZKID LIVE IN CONCERT </li>
                    <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                   <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                   <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>
                  <button class="b_t"> Buy Ticket </button>
                  </ul>
                </div>

                <div class="one_e">
                    <ul>
                    <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                      <li class="noe"> WIZKID LIVE IN CONCERT </li>
                        <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                       <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                       <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>

                      <button class="b_t"> Buy Ticket </button>
                      </ul>
                    </div> --}}

     </div>

     </div>

     <br/>
     <br/>
     <br/>
     <br/>
     <br/>
     <br/>
     <br/>
     {{-- <div class="pag">
     @include('_paginate')
     {{$welcome->links()}}
     </div>

       </div> --}}



       @include('_footer')
    </div>

    {{-- @include('_footer') --}}

  </div>
</div>

  {{-- Footer --}}

  <br/>
  <br/>
  <br/>
  <br/>
  <br/>
  <br/>


</body>
<script>

</script>



{{-- @foreach($welcome as $Onedata)
<p> {{$Onedata['name']}} </p>
@endforeach --}}

{{-- <h1> {{$heading}} </h1>
@foreach($welcome as $onewelcome)
<a href="/events/{{$onewelcome['id']}}"> {{$onewelcome['name']}} </a>
<br/>
@endforeach --}}


{{-- <!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<script type="module" src="{{ mix('resources/js/app.js') }}"></script>
</head>
<body> --}}
	{{-- @if (Auth::guard('admin')) { --}}
    {{-- @auth --}}

		{{-- <header></header> --}}


        {{-- Old Stuff --}}

    {{-- <h1> {{$heading}} </h1>
    <p>Cart Item Count: {{ $cartItemCount }}</p>
    @foreach($welcome as $onewelcome)
    <img  src="{{asset('storage/' . $onewelcome->heroimage)}}">
    <a href="/events/{{$onewelcome['id']}}"> {{$onewelcome['herolink']}} </a>
    <br/>
    @endforeach


    @foreach($welcome as $onewelcome)
        <a href="/events/{{$onewelcome['id']}}"> {{$onewelcome['name']}} </a>
        <form action="{{url('/addtocart', $onewelcome->id)}}" method="POST">
            @csrf
            <input type="submit" value="Add to cart!"/>
        </form>
        <br/>
    @endforeach --}}

    {{-- End old stuff --}}

    {{-- <div id="app"></div> --}}

{{-- @endauth --}}


{{-- <div id="app-a">
    <Component-a></Component-a>
</div> --}}


