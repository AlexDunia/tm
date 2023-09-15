<head>

    <link
        rel="stylesheet"
        href="\css\checkout.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>


{{-- <body>
    <form id="paymentForm">
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email-address" required />
        </div>
        <div class="form-group">
          <label for="amount">Amount</label>
          <input type="tel" id="amount" required />
        </div>
        <div class="form-group">
          <label for="first-name">First Name</label>
          <input type="text" id="first-name" />
        </div>
        <div class="form-group">
          <label for="last-name">Last Name</label>
          <input type="text" id="last-name" />
        </div>
        <div class="form-submit">
          <button type="submit" onclick="payWithPaystack(event)"> Pay </button>
        </div>
      </form>
      <script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</body> --}}


<body>



    @auth

    <?php
    // Where A stands for authenticated User
    $atotalPrice = 0; // Initialize the total price variable
    ?>

    {{-- <div class="tabledetails">
        <p> {{ $mycart->cprice }} </p>
    </div> --}}
    <?php
    // where a stands for authenticated user.
    $atotalPrice += $mycart->ctotalprice;
    ?>

     <div class="checkoutimage">
     <img  width="250px" src="{{asset('storage/' . $mycart->cdescription)}}">
     </div>

     <div class="checkoutname">
      <h1> {{$mycart->cname}} </h1>
     </div>

    <div class="ft">

    <div class="form">
        <div class="formhead">
            <h3> Buyer Information. </h3>
        </div>
            <form id="paymentForm" class="coformbg">
                <div class="form-group">
                  <label for="email">Email Address</label>
                  <input type="email" id="email-address" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="amount"  value="{{$atotalPrice}}" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="cn"  value="{{$mycart->cname}}" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="cq"  value="{{$mycart->cquantity}}" required />
                </div>
                <div class="form-group">
                  {{-- <label for="amount">Amount</label> --}}
                  <input type="hidden" id="en"  value="{{$mycart->eventname}}" required />
                </div>

                <div class="form-group">
                  <label for="first-name">First Name</label>
                  <input type="text" id="first-name" />
                </div>
                <div class="form-group">
                  <label for="last-name">Last Name</label>
                  <input type="text" id="last-name" />
                </div>
                <div class="form-submit">
                  <button type="submit" onclick="payWithPaystack(event)" class="btncontact"> Pay </button>
                </div>
              </form>

        </div>



         {{-- <h1> Cart page </h1>
        @foreach($mycart as $onewelcome)
        <a> {{$onewelcome['cname']}} </a>
        <a href="{{url('/delete', $onewelcome->id)}}""> Delete me </a>
        <br/>
        @endforeach --}}

        <div class="tabledetailsbg">

            <div class="tabledetailsbghead">
            <h2> Ticket Information </h2>
            </div>

            {{-- <ul>
                <li>Product Name: {{ $mycart->cname }}</li>
                <li>Product Price: {{ $mycart->cprice }}</li>
                <!-- Add more properties as needed -->
            </ul> --}}

            <?php
            // Where A stands for authenticated User
            $atotalPrice = 0; // Initialize the total price variable
            ?>


        <div class="tabledetailsflex">


        <div class="tabledetails">
            {{-- <p> Table for 10 </p> --}}
            <p> {{ $mycart->cname }} X  {{ $mycart->cquantity }} </p>
        </div>

        <div class="tabledetails">
            <p> {{ $mycart->cprice }} </p>
        </div>
        <?php
        // where a stands for authenticated user.
        $atotalPrice += $mycart->ctotalprice;
        ?>

       {{-- <a href="{{url('/delete', $onewelcome->id)}}"> Remove </a> --}}
       </div>


       <div class="tabledetailsflex">

        <div class="tabledetails">
            <p> Total </p>
        </div>

        <div class="tabledetails">
             <p> {{$atotalPrice}} </p>
        </div>

       </div>



    </div>

     </div>

        @else


        <div class="checkoutimage">
          <img  width="250px" src="{{asset('storage/' . $timage)}}">
          </div>

          <div class="checkoutname">
           <h1> {{$eventname}} </h1>
          </div>

        <div class="ft">

        <div class="form">
            <div class="formhead">
                <h3> Buyer Information </h3>
            </div>
        {{-- <form method="post" class="coformbg" action="/" enctype="multipart/form-data"> --}}
            {{-- <form method="post" class="coformbg" action="/" enctype="multipart/form-data"> --}}
             {{-- <h1> {{$atotalPrice}} </h1> --}}
                <form id="paymentForm" class="coformbg">
                    <div class="form-group">
                      <label for="email">Email Address</label>
                      <input type="email" id="email-address" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="amount"  value="{{$totalprice}}" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="cn"  value="{{$tname}}" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="cq"  value="{{$tquantity}}" required />
                    </div>
                    <div class="form-group">
                      {{-- <label for="amount">Amount</label> --}}
                      <input type="hidden" id="en"  value="{{$eventname}}" required />
                    </div>

                    <div class="form-group">
                      <label for="first-name">First Name</label>
                      <input type="text" id="first-name" />
                    </div>
                    <div class="form-group">
                      <label for="last-name">Last Name</label>
                      <input type="text" id="last-name" />
                    </div>
                    <div class="form-submit">
                      <button type="submit" onclick="payWithPaystack(event)" class="btncontact"> Pay </button>
                    </div>
                  </form>

            </div>


    <div class="tabledetailsbg">

      <div class="tabledetailsbghead">
      <h2> Ticket Information </h2>
      </div>

  <div class="tabledetailsflex">

  <div class="tabledetails">
      {{-- <p> Table for 10 </p> --}}
      <p> {{$tname}}  X  {{$tquantity}} </p>
  </div>

  <div class="tabledetails">
      <p> {{$tprice}} </p>
  </div>

 </div>

 <div class="tabledetailsflex">

  <div class="tabledetails">
      <p> Total </p>
  </div>

  <div class="tabledetails">
      <p> {{$totalprice}} </p>
  </div>




           {{-- <div class="tabledetailsflex">

            <div class="tabledetails">
                <p> Total </p>
            </div>

            <div class="tabledetails">
                 <p> {{$atotalPrice}} </p>
            </div>

           </div> --}}



        </div>

         </div>


     </div>

     @endauth

     <br/>
     <br/>
     <br/>
     <br/>
     <br/>

    {{-- end of circular bg --}}

<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
</body>

<script>
  const input = document.getElementById("cn").value;
  const words = input.split(' ');
  const fl = words.map(word => word[0]).filter(Boolean).join('');
  const fll = document.getElementById("en").value;
  const flsecond = fll.replace(/\s/g, '');
  const cr = fl + flsecond;
    const paymentForm = document.getElementById('paymentForm');
paymentForm.addEventListener("submit", payWithPaystack, false);

function payWithPaystack(e) {
  e.preventDefault();

  let handler = PaystackPop.setup({
    key: 'pk_test_a23671022344a4de4ca87e5b42f68b3f5d84bfd9', // Replace with your public key
    email: document.getElementById("email-address").value,
    amount: document.getElementById("amount").value * 100,
    "metadata": {
    "custom_fields": [
      {
        "display_name": "Event-name",
        "variable_name": "Event-name",
        "value": document.getElementById("cn").value,
      },
      {
        "display_name": "Quantity",
        "variable_name": "Quantity",
        "value": document.getElementById("cq").value,
      },
      {
        "display_name": "Eventname",
        "variable_name": "eventname",
        "value": fl,
      }
    ]
  },
    ref: 'TD' + cr +Math.floor((Math.random() * 1000000000) + 1), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
    // label: "Optional string that replaces customer email"
    onClose: function(){
      alert('Window closed.');
    },
    callback: function(response){
  //   let message = 'Payment complete! Reference: ' + response.reference;
    let reference = response.reference
    fetch("{{URL::to('verifypayment')}}/" + reference)
  .then(response => response.json())
  .then(data => {
      // console.log(data);
      window.location.href = "{{URL::to('success')}}";
  })
  .catch(error => {
      // console.error("Error:", error);
  });


//       $.ajax({
//     type: "GET",
//     url: "{{URL::to('verifypayment')}}/" + reference,
//     success: function(response) {
//         console.log(response);
//     }
// });

    //   console.log(reference);
    }
  });

  handler.openIframe();
}
</script>