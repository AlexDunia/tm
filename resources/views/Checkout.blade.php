<head>

    <link
        rel="stylesheet"
        href="\css\checkout.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>

  <div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">


    <div class="ft">

    <div class="form">
        <div class="formhead">
            <h3> Buyer Information </h3>
        </div>
    {{-- <form method="post" class="coformbg" action="/" enctype="multipart/form-data"> --}}
        <form method="post" class="coformbg" action="/" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div>
            <Label for="firstname" class="formlabel"> First Name </label>
            <input id="name" class="coform" type="text" name="firstname" placeholder="First Name">
    </div>
    <div>
        <Label for="lastname" class="formlabel"> Last Name </label>
        <input id="name" class="coform" type="text" name="lastname" placeholder="Last Name">
    </div>
        <div>
            <Label for="email" class="formlabel"> Email  </label>
            <input id="email" class="coform" type="email" name="email" placeholder="Your Email">
        </div>

        <div>
            <Label for="number" class="formlabel"> Phone Number </label>
            <input id="email" class="coform" type="password" name="password" placeholder="Your Password">
        </div>
              <div>
            <button class="newsbtn" type="submit">Sign up</button>
              </div>
            </form>

        </div>

        @auth

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

        <div class="tabledetailsbg">

            <div class="tabledetailsbghead">
            <h2> Ticket Information </h2>
            </div>

        <div class="tabledetailsflex">

        <div class="tabledetails">
            {{-- <p> Table for 10 </p> --}}
            <p> {{$tname}} </p>
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

       </div>

    </div>

     </div>

     @endauth
    {{-- end of circular bg --}}
</div>

</body>