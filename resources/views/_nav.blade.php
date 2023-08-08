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
    @auth
{{-- <h1>{{ auth()->user()->name }}</h1> --}}


  {{-- <div class="ctna">

    <h1>{{ auth()->user()->name }}</h1>
    <span aria-hidden="true" data-testid="cart-badge" class="desktop-header-module--dropdown-counter-badge--lJt8q notification-badge--ud-notification-badge--1Ofoo notification-badge--ud-notification-counter--2rj4x" title="2 items in the cart">2</span>


<div class="circle-clip">
  <img src="{{ asset( 'storage/' . auth()->user()->profilepic ) }}">
</div>


<div aria-hidden="true" class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials" style="width: 3.2rem; height: 3.2rem;">
<span class="initials"> {{ strtoupper(substr(auth()->user()->name, 0, 1)) }} </span>
<span class="initials"> {{ strtoupper(substr(auth()->user()->email, 0, 1)) }} </span>
</div> --}}

{{-- <img src="{{ asset('storage/' . Auth::user()->profilepic) }}" alt="Profile Picture">
<h1>{{ auth()->user()->name }}</h1>
<img src="{{ URL::to('/') }}/storage/{{ Auth::user()->profilepic }}" alt="Profile Picture"> --}}

<div class="lisu">
    @auth
    <form method="POST" action="/logout">
@csrf
<button>
    <a>
  Log out
    </a>
</button>
</form>
</br>
<h3> <a> Sign up </a>  </h3>
@else
<h3> <a> Log in </a> </h3>
<h3> <a> Sign up </a>  </h3>
@endauth
</div>

<div class="ctna">
<div class="navflex">
<div class="flogow">
<img src="/images/tdlogo.png" alt="Logo"/>
</div>

{{-- <div class="selectandsearch">
<form class="example">
  <input type="text" placeholder="Search.." name="search" class="custom-input">
  <i class="fas fa-search search-icon"></i>
</form>
</div> --}}

{{-- <form method="POST" action="/logout">
@csrf
<button>
  Log out
</button>
</form> --}}

<div class="navlinks">
<ul>
  <li><a> Home </a> </li>
  <li><a> Music </a></li>
  <li><a> Movies </a></li>
  <li><a> Contact us </a></li>
</ul>
</div>




  {{-- <div class="circle">
    <span class="number">13</span>
    <i class="fa-solid fa-cart-shopping"></i>
  </div> --}}

  @if(auth()->user()->profilepic)
  {{-- class="user" id="usericonid" --}}
  <div id="usericonid" >
<div class="circle-clip " id="usericonid">
<img src="{{ asset( 'storage/' . auth()->user()->profilepic ) }}">
</div>
</div>
@else
<div id="usericonid" >
<div aria-hidden="true"  class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials"  style="width: 2.5rem; height: 2.5rem;">
<span class="initials"> {{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }} </span>
<span class="initials"> {{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }} </span>
</div>
</div>
<div id="cancelpop" >
    <div aria-hidden="true"  class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials"  style="width: 2.5rem; height: 2.5rem;">
    <span class="initials"> {{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }} </span>
    <span class="initials"> {{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }} </span>
    </div>
    </div>
@endif


  {{-- <div class="userandcart">

  <div class="user" id="usericonid">
     <i class="fa fa-circle-user"></i>
  </div>

  <div class="user" id="cancelpop">
    <i class="fa fa-circle-user"></i>
 </div>

</div> --}}
</div>
</div>
</div>

@else
{{-- Guest  --}}

<div class="ctna">
<div class="navflex">
  <div class="flogow">
    <img src="/images/tdlogo.png" alt="Logo"/>
  </div>
  <div class="navlinks">
    <ul>
      <li><a> Home </a> </li>
      <li><a> Music </a></li>
      <li><a> Movies </a></li>
      <li><a> Theatre/Comedy</a></li>
      <li><a> Sports </a></li>
      <li><a> Contact us </a></li>
    </ul>
  </div>

  <div class="userandcart">
      {{-- <div class="circle">
        <span class="number">1</span>
        <i class="fa-solid fa-cart-shopping"></i>
      </div> --}}

      {{-- <svg aria-hidden="true" focusable="false" data-prefix="far" data-icon="circle-user" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-circle-user fa-xl"><path fill="currentColor" d="M406.5 399.6C387.4 352.9 341.5 320 288 320H224c-53.5 0-99.4 32.9-118.5 79.6C69.9 362.2 48 311.7 48 256C48 141.1 141.1 48 256 48s208 93.1 208 208c0 55.7-21.9 106.2-57.5 143.6zm-40.1 32.7C334.4 452.4 296.6 464 256 464s-78.4-11.6-110.5-31.7c7.3-36.7 39.7-64.3 78.5-64.3h64c38.8 0 71.2 27.6 78.5 64.3zM256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-272a40 40 0 1 1 0-80 40 40 0 1 1 0 80zm-88-40a88 88 0 1 0 176 0 88 88 0 1 0 -176 0z" class=""></path></svg> --}}

      <div class="user" id="usericonid">
         <i class="fa fa-circle-user"></i>
      </div>

      <div class="user" id="cancelpop">
        <i class="fa fa-circle-user"></i>
     </div>

    {{-- <button class="btn">
      My cart
    </button> --}}
  </div>
  <div class="lisu">
    <h3> <a href="/login"> Log in </a> </h3>
</br>
    <h3> <a href="/signup"> Sign up </a>  </h3>
</div>
</div>
</div>
</div>
@endauth

{{-- End of Nav bar --}}

{{-- End of Guests --}}

</body>
<script>

// Cancel pop up
const cpop = document.getElementById("cancelpop");
const uid = document.getElementById("usericonid");
const lisu = document.querySelector(".lisu");

cpop.addEventListener("click", function() {
    lisu.style.display = "none";
    uid.style.display = "block";
    cpop.style.display = "none";
});

console.log("Script is running"); // Add this line
uid.addEventListener("click", function() {
    console.log("enter");
    lisu.style.display = "block";
    uid.style.display = "none";
    cpop.style.display = "block";
});
</script>
