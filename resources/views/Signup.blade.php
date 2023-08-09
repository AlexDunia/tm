<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <link
        rel="stylesheet"
        href="\css\signup.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>


        <div class="circularsign" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
    <div class="popupwithdraww" id="popupwhitewithdraww">
       <a href="{{ route('login') }}">  <div class="xmark"><i class="fa-solid fa-xmark"></i></div>
       </a>


        <div class="newsform">
            <h1 class="newsletterhead"> Create Your Account </h1>
            <p> Enter your personal details to get started </p>
            <br/>
            <form method="post" class="fstyle" action="/createnewadmin" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div>
                    <input class="nlstyle" type="text" name="firstname" placeholder="First Name" value="{{ old('firstname') }}">
                    {{-- <input class="nlstyle" type="text" name="firstname" placeholder="First Name"> --}}
            </div>
            <div>
                <input class="nlstyle" type="text" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}">

                {{-- <input class="nlstyle" type="text" name="lastname" placeholder="Last Name"> --}}
        </div>
                <div>
                    <input class="nlstyle" type="email" name="email" placeholder="Email" value="{{ old('email') }}">

                    {{-- <input  class="nlstyle" type="email" name="email" placeholder="Your Email"> --}}
                </div>

                <div>
                    <input class="nlstyle" type="password" name="password" placeholder="Password" value="{{ old('password') }}">

                    {{-- <input class="nlstyle" type="password" name="password" placeholder="Your Password"> --}}
                </div>
                      <div>
                    <button class="newsbtn" type="submit">Sign up</button>
                      </div>
                    </form>
                    <p class="createact"> Already have an account? Log in </p>


    </div>
</div>

</body>
</html>




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