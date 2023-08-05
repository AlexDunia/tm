<head>

    <link
        rel="stylesheet"
        href="\css\listone.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>

{{-- <h1> {{$listonee['name']}} </h1>
<h1> {{$listonee['location']}} </h1>
<form action="{{url('/addtocart', $listonee->id)}}" method="POST">
    @csrf <!-- Add this line to include the CSRF token -->
    <input type="submit" value="Add to cart!"/>
</form> --}}


{{-- <a href="/event/{{$listonee['id']}}/ticket"> Edit </a> --}}

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


<table class="custom-table">

    <tr>
    <th> Name </th>
    <th> Type </th>
    <th> Quantity </th>
    <th> Price </th>

    </tr>

    @php
    $tableNames = ['startingprice', 'earlybirds', 'tableone', 'tabletwo', 'tablethree', 'tablefour', 'tablefive', 'tablesix', 'tableseven', 'tableeight'];
@endphp

@foreach ($tableNames as $tableName)
    @if (!empty($listonee[$tableName]))
        <tr>
            @foreach (explode(',', $listonee[$tableName]) as $part)
                <td>{{ trim($part) }}</td>
            @endforeach

            <form action="{{ url('/addtocart', $listonee->id) }}" method="POST">
                @csrf
                <td>
                    <input type="number" value="1" min="1" style="width:100px" name="quantity">
                </td>
                <td>
                    <input type="submit" value="Add to cart!">
                </td>
            </form>
        </tr>
    @endif
@endforeach


    {{-- <form action="{{url('/addtocart', $listonee->id)}}" method="POST">
        @csrf <!-- Add this line to include the CSRF token -->
        <input type="submit" value="Add to cart!"/>
    </form> --}}

    {{-- <tr>
    <td class="timg">  <img src="/images/kizz.png"/> </td>
    <td> Omah lay live liechester Athena city
    live metaverse concert. </td>
    <td> Regular </td>
    <td> 4 </td>
    <td> N30,0000 </td>
    <td> X </td>
    </tr> --}}

    </table>

</div>




{{-- @verbatim
    <div id="apk"></div>
@endverbatim --}}

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

