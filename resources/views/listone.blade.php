<head>

    <link
        rel="stylesheet"
        href="\css\listone.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>

<h1> {{$listonee['name']}} </h1>
<h1> {{$listonee['location']}} </h1>
<form action="{{url('/addtocart', $listonee->id)}}" method="POST">
    @csrf <!-- Add this line to include the CSRF token -->
    <input type="submit" value="Add to cart!"/>
</form>
{{-- <a href="/event/{{$listonee['id']}}/ticket"> Edit </a> --}}

<div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">

    <div class="blurcontainer">

    <div class="bcflex">

    <div class="bctext">
    <p class="cat"> Category: Music / Show / Concert </p>
    <h1> {{$listonee['name']}} </h1>
    <p class="infoticket"> {{$listonee['description']}} </p>
    <p class="infodate"> <i class="fa-solid fa-calendar-day"> </i> {{$listonee['location']}} </p>
    </div>

    <div class="bcimg">
        <img  src="{{asset('storage/' . $listonee->image)}}">
    </div>

    </div>

    </div>

    <table class="custom-table">

<tr>
<th>  </th>
<th> Name </th>
<th> Type </th>
<th> Quantity </th>
<th> Price </th>
<th>  </th>
</tr>

<tr>
<td class="timg">  <img src="/images/kizz.png"/> </td>
<td> Omah lay live liechester Athena city
live metaverse concert. </td>
<td> Regular </td>
<td> 4 </td>
<td> N30,0000 </td>
<td> X </td>
</tr>

<tr>
<td class="timg">  <img src="/images/kizz.png"/> </td>
<td> Omah lay live liechester Athena city
live metaverse concert. </td>
<td> Regular </td>
<td> 4 </td>
<td> N30,0000 </td>
<td> X </td>
</tr>

</table>

<div class="checkout"><button> Total: N300,000 </button></div>
<div class="checkoutt"><button> CHECKOUT </button></div>

<div class="cdbg">

<div class="cdflex">

<div class="dtgflex">

<div class="nodtg">
 <h1> 5 </h1>
</div>

<div class="dtg">
 <h2> Days </h2>
 <h2> To Go </h2>
</div>

</div>

<!-- Main -->

<div class="maincdtextflex">

<div class="maincdtext">
<h1> 17 </h1>
<h3> Days </h3>
</div>

<div class="maincdtext">
<h1> 15 </h1>
<h3> Hours </h3>
</div>

<div class="maincdtext">
<h1> 10 </h1>
<h3> Minutes </h3>
</div>

</div>

</div>

</div>

</div>

{{-- @verbatim
    <div id="apk"></div>
@endverbatim --}}

</body>
