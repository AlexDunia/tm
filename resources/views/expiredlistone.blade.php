<head>

    <link
        rel="stylesheet"
        href="\css\exlistone.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>

    @include('_nav')

<div class="circularex" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
    <div class="blurcontainer">

    <div class="bcflex">

    <div class="bctext">
    <p class="cat"> Category: Music / Show / Concert </p>
    <h1> {{$listonee['name']}} </h1>
    <p class="infoticket"> {{$listonee['description']}} </p>
    <div id="countdown"></div>
    <p class="infodate"> <i class="fa-solid fa-calendar-day"> </i> {{$listonee['location']}} </p>
    </div>

    <div class="bcimg">
        <img  src="{{asset('storage/' . $listonee->image)}}">
    </div>

    </div>

    </div>
    {{-- <img src="/images/kizz.png"/> --}}

    {{-- <table class="custom-table">

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
<div class="checkoutt"><button> CHECKOUT </button></div> --}}

<div class="cdbg">

    <div class="expired">
        <h1 id="days"> Expired! </h1>
    </div>

</div>




</div>

</body>

<script>

</script>

