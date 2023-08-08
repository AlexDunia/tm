<head>

    <link
        rel="stylesheet"
        href="\css\listone.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>
    @include('_nav')

<div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
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

<div class="cdflex">

<div class="dtgflex">

<div class="nodtg">
    <h1 id="days">0</h1>
</div>

<div class="dtg">
 <h2> Days </h2>
 <h2> To Go </h2>
</div>

</div>

<!-- Main -->

<div class="maincdtextflex">

{{-- <div class="maincdtext">
    <h1 id="days">0</h1>
<h3> Days </h3>
</div> --}}

<div class="maincdtext">
    <h1 id="hours">0</h1>
<h3> Hours </h3>
</div>

<div class="maincdtext">
    <h1 id="minutes">0</h1>
<h3> Minutes </h3>
</div>

<div class="maincdtext">
    <h1 id="seconds">0</h1>
<h3> Minutes </h3>
</div>

</div>

</div>

</div>



<form action="{{ url('/addtocart') }}" method="POST">
    @csrf
    <table class="custom-table">
        <tr>
            <th> Pricing </th>
            <th> Quantity </th>
        </tr>
        @php
            $tableNames = ['startingprice', 'earlybirds', 'tableone', 'tabletwo', 'tablethree', 'tablefour', 'tablefive', 'tablesix', 'tableseven', 'tableeight'];
        @endphp
        @foreach ($tableNames as $tableName)
            @if (!empty($listonee[$tableName]))
                <tr>
                    <td class="pricedata">
                        {{ explode('.', trim($listonee[$tableName]))[0] }}
                        <input type="hidden" name="product_ids[]" value="{{ $listonee->id }}">
                        <input type="hidden" name="table_names[]" value="{{ explode('.', $listonee[$tableName])[0] }} ">
                    </td>
                    <td class="pricequantity">
                    @if (strpos($listonee[$tableName], '.') !== false)
                    {{-- Text contains a period --}}
                    {{-- {{ "Sold out " . explode('.', $yourVariable)[1] }} --}}
                  <h3 class="soldout">  {{ explode('.', $listonee[$tableName])[1] }} </h3>
                    @else
                   {{-- Text does not contain a period --}}
                   <input type="number" value="0" min="0" style="width:50px" name="quantities[]">
                  @endif
                    </td>
                    {{-- <td class="pricequantity">
                        <input type="number" value="0" min="0" style="width:50px" name="quantities[]">
                    </td> --}}
                </tr>
            @endif
        @endforeach
    </table>
    <input class="checkoutt" type="submit" value="Add Selected Items to Cart">
</form>

<div class="eventinfo">
<h1> Event information </h1>
<hr/>

<div class="eventinfoflex">

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> 19/20/21 </h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> 19/20/21 </h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> 19/20/21 </h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> 19/20/21 </h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> 19/20/21 </h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> 19/20/21 </h3>
</div>

</div>

</div>
</div>

</body>

<script>
    // Get the target date and time from the Blade template
    const targetDate = new Date("{{$listonee['date']}}");

    // Update the countdown every second
    const daysElement = document.getElementById("days");
    const hoursElement = document.getElementById("hours");
    const minutesElement = document.getElementById("minutes");
    const secondsElement = document.getElementById("seconds");

    const countdownInterval = setInterval(() => {
        const currentDate = new Date();
        const timeRemaining = targetDate - currentDate;

        if (timeRemaining > 0) {
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            daysElement.innerText = days;
            hoursElement.innerText = hours;
            minutesElement.innerText = minutes;
            secondsElement.innerText = seconds;
        } else {
            daysElement.innerText = "0";
            hoursElement.innerText = "0";
            minutesElement.innerText = "0";
            clearInterval(countdownInterval);
        }
        // else {
        //     countdownElement.innerHTML = "Countdown expired";
        //     clearInterval(countdownInterval);
        // }
    }, 1000); // Update every second
</script>

