<head>

    <link
        rel="stylesheet"
        href="\css\signup.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>


        <div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
    <div class="popupwithdraww" id="popupwhitewithdraww">
       <a href="{{ route('login') }}">  <div class="xmark"><i class="fa-solid fa-xmark"></i></div>
       </a>


        <div class="newsform">
            <h1 class="newsletterhead"> Create Your Account </h1>
            <p> Enter your personal details to get started </p>
            <br/>
            <form method="post" class="fstyle" action="/authenticated" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div>
                    <input id="email" class="nlstyle" type="email" name="email" placeholder="Your Email">
                </div>

                <div>
                    <input id="email" class="nlstyle" type="password" name="password" placeholder="Your Password">
                    <a> Forgot Password </a>
                </div>
                      <div>
                    <button class="newsbtn" type="submit">Sign up</button>
                      </div>
                    </form>
                    <p class="createact"> Already have an account? Log in </p>


    </div>
</div>

</body>




            {{-- <form method="post" class="fstyle" action="/createnewadmin" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="forminner">

        <input
            type="text"
            name="name"
        />
        @error('name')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="Email"
            name="email"

        />
        @error('email')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="file"
            name="profilepic"

        />
        @error('profilepic')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="password"
            name="password"
        />
        @error('password')
        <p> {{$message}} </p>
        @enderror
    </div>


    <br/>
    <br/>

    <div class="forminner">
        <button
            type="submit"
        >
        Create Admin
        </button>
    </div>

</form> --}}