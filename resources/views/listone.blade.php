<head>

    <link
        rel="stylesheet"
        href="\css\listone.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
        <script
        src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
        integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
        crossorigin="anonymous"
        referrerpolicy="no-referrer"
      ></script>
        {{-- <script type="text/javascript" src="{{ asset('qrcode.js') }}"></script> --}}

</head>
<body>

    @include('_nav')

{{-- <div class="circularr" style="background-image: url('{{ asset('images/crowd.jpg') }}');"> --}}

    @if(session()->Has("message")){
        <div class="qerror" x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
            <p> AT LEAST ONE QUANTITY MUST BE ADDED BEFORE PROCEEDING! </p>
            </div>
    }
    @endif

    <div class="blurcontainer">

    <div class="bcflex">

    <div class="bctext">
    <p class="cat"> Category:  {{$listonee['category']}}  </p>
    <h1> {{$listonee['name']}} </h1>
    <p class="infoticket"> {{$listonee['description']}} </p>
    <div id="countdown"></div>
    <p class="infodate"> <i class="fa-solid fa-calendar-day"> </i> {{$listonee['location']}} </p>

    <br/>

    <div class="qrwidth">
        <div id="qrcode"></div>
        </div>
    <button id="saveButton">Save QR Code</button>

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
<br/>
<br/>
<br/>
<input id="text" type="hidden" value="{{ URL::current() }}" />

{{-- <input id="text" type="text" value="https://hogangnono.com" style="background-color: white; width: 80%; color: black;" /><br /> --}}


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
                            <select name="quantities[]" class="quantity-select" onchange="updateQuantities(this)">
                                @for ($i = 0; $i <= 50; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>

                        @endif
                    </td>
                </tr>
            @endif
        @endforeach
    </table>
    <input class="checkoutt" type="submit" value="Add Selected Items to Cart">
</form>



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

<br/>
<br/>
<br/>
<br/>

{{-- New div --}}

<div class="eventinfo">
<h1> Event information </h1>
<hr/>

<div class="eventinfoflex">

<div class="eventinfotext">
    <h3 class="pinktext"> Start Date </h3>
    <h3 class="ntext"> {{ date('n/j/Y', strtotime($listonee['date'])) }}</h3>

</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Time </h3>
    <h3 class="ntext"> {{$listonee['time']}}</h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> End Date  </h3>
    <h3 class="ntext"> {{$listonee['enddate']}}</h3>
</div>

<div class="eventinfotext">
    <h3 class="pinktext"> Category  </h3>
    <h3 class="ntext"> {{$listonee['category']}}</h3>
</div>

{{-- <div class="eventinfotext">
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
</div> --}}

</div>

<br/>
<br/>

</div>
<br/>
<br/>
<br/>
<br/>
 {{-- @include('_footer') --}}

{{-- </div> --}}



</body>

<script>

function updateQuantities(selectedQuantityElement) {
        // Get the selected quantity value
        var selectedQuantity = selectedQuantityElement.value;

        // Get all quantity select elements
        var quantitySelects = document.querySelectorAll('.quantity-select');

        // Loop through all quantity select elements
        quantitySelects.forEach(function (quantitySelect) {
            // Set the value to zero for all other elements
            if (quantitySelect !== selectedQuantityElement) {
                quantitySelect.value = 0;
            }
        });
    }

// window.onload = function () {
//     location.reload();
//   };

var qrcode = new QRCode("qrcode");

function makeCode() {
  var elText = document.getElementById("text");

  if (!elText.value) {
    alert("Input a text");
    elText.focus();
    return;
  }

    // Generate the QR code with the specified dimensions
    qrcode.makeCode(elText.value);
}

makeCode();

var textInput = document.getElementById("text");

textInput.addEventListener("blur", function () {
  makeCode();
});

textInput.addEventListener("keydown", function (e) {
  if (e.key === "Enter") { // Use "key" property for modern browsers
    makeCode();
    e.preventDefault(); // Prevent the default Enter key behavior (e.g., form submission)
  }
});

function saveQRCode() {
        // Get the "qrcode" div element
        const qrcodeDiv = document.getElementById('qrcode');

        // Define the desired width and height for the image
        const desiredWidth = 400; // Change this to your preferred width
        const desiredHeight = 400; // Change this to your preferred height

        // Create a canvas element with the desired dimensions
        const canvas = document.createElement('canvas');
        canvas.width = desiredWidth;
        canvas.height = desiredHeight;

        // Get the canvas context
        const context = canvas.getContext('2d');

        // Draw the QR code on the canvas
        const img = new Image();
        img.src = qrcodeDiv.querySelector('img').src;
        img.onload = () => {
            // Calculate the scaling factors for width and height
            const scaleWidth = desiredWidth / img.width;
            const scaleHeight = desiredHeight / img.height;

            // Use the scaling factors to draw the image with the desired size
            context.drawImage(img, 0, 0, desiredWidth, desiredHeight);

            // Create a temporary link to trigger the download
            const a = document.createElement('a');
            a.href = canvas.toDataURL('image/png');
            a.download = 'qrcode.png';

            // Trigger a click event on the link
            a.click();
        };
    }

    // Add a click event listener to the "Save QR Code" button
    document.getElementById('saveButton').addEventListener('click', saveQRCode);
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

        if (timeRemaining > 0 ) {
            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            daysElement.innerText = days;
            hoursElement.innerText = hours;
            minutesElement.innerText = minutes;
            secondsElement.innerText = seconds;
        } else if(timeRemaining < 0){
            daysElement.innerText = "0";
            hoursElement.innerText = "0";
            minutesElement.innerText = "0";
            clearInterval(countdownInterval);
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

