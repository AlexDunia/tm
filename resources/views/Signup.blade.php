<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name= "viewport" content="width=device-width, initial-scale=1">

<title> Sign up </title>
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="assets/img/favicon.ico" rel="icon">

</head>
<style>

    body{
        padding:0;
        margin:0;
        color: white;
    }

    .fstyle .inputerror{
        color:red;
        font-weight:600;
    }

  .inputerror{
        color:red;
        font-weight:600;
    }

    /* .tyaround{
        width:80%;
        margin:auto;
        margin-top:100px;
    }

    .tybg{
        color: #a0aec0;
        padding-bottom:150px;
        padding-top:40px;
    }

    .tybg p {
        font-size:15px;
        color: #a0aec0;
        margin-bottom:15px;
    }

    .tybg h1{
        font-size:35px;
        margin:auto;
        text-align:center;
        margin-bottom:20px;
    }

    .tybg a{
        color: #C04888;
        font-size:15px;
        margin-top:5px;
    }

    .pty{
        color: #a0aec0;
    } */

    input {
    font-size:16px;
  color: white;
}


    .createact a{
        color:#C04888;
        font-weight:600;
        margin-left:10px;
        font-size:18px;
        text-decoration:none;
    }

    .createact a:hover{
        color:blue;
        cursor:pointer;
        font-weight:600;
        margin-left:10px;
        font-size:18px;
        text-decoration:underline;
    }

    .tyaround {
        width: 85%;
        /* max-width: 400px;  */
        margin: auto;
    }


    .tyaround{
        margin:auto;
        align-items: center;
    }

    .tyaround h1{
        /* text-align:center; */
        font-size:40px;
        margin-top:30px;
        margin-bottom:10px;
    }

    .tyaround p{
        font-weight:200;
        /* font-size:15px; */
        /* font-size:40px;
        margin-top:20px;
        margin-bottom:10px; */
    }

    .tyaround form label {
        padding-top:300px;
        margin-top:300px;
        font-size: 20px;
    }

    form input,
    form textarea {
        width: 100%;
        padding: 20px;
        background: rgba(37, 36, 50, .8);
        border: none;
        margin-top: 20px;
        box-sizing: border-box; /* Add this property to include padding and border in the width calculation */
    }

    .btncontact {
        min-width: 100%;
        padding: 20px;
        background-color: #C04888;
        color: white;
        font-size: 20px;
        font-weight: 600;
        border: none;
        margin-top: 20px;
    }

    .lab{
        margin-top:20px;
    }

    @media (max-width: 700px) {

        .tyaround h1{
        font-size:30px;
    }


        .tybg{
        background: rgba(37, 36, 50, .8);
        color: #a0aec0;
        padding-bottom:50px;
        padding-top:40px;
        padding-left:10px;
    }

    .tybg p {
        font-size:14px;
        color: #a0aec0;
        margin-bottom:10px;
    }


    .tybg h1{
        font-size:23px;
    }
    }

</style>
<body>
    @include('_nav')
    <br/>
    <br/>

    {{-- <div class="tyaround"> --}}
    {{-- <div class="tybg"> --}}
        <div class="tyaround">
        <h1> Create Your Account </h1>
        <p> Enter your personal details to get started </p>

            <br/>
            <br/>
            <br/>

       <form method="post" class="fstyle" action="/createnewadmin" enctype="multipart/form-data">

        @csrf
        <label for="firstname"> First name: </label>
        <input class="nlstyle" type="text" name="firstname" placeholder="First Name" value="{{ old('firstname') }}">
        @error('firstname')
        <p class="inputerror"> {{$message}} </p>
        @enderror


        <br/>
        <br/>
        <label for="lastname"> Last name: </label>
        <input class="nlstyle" type="text" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}">
        @error('lastname')
        <p class="inputerror"> {{$message}} </p>
        @enderror

        <br/>
        <br/>
        <label for="lastname"> Email: </label>
        <input class="nlstyle" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
        @error('email')
        <p class="inputerror"> {{$message}} </p>
        @enderror

        <br/>
        <br/>
        <label for="lastname"> Password: </label>
        <input class="nlstyle" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
        @error('password')
        <p class="inputerror"> {{$message}} </p>
        @enderror

        <br/>
        <br/>
        <br/>

        <a> Forgot Password </a>

          <div>
        <button class="btncontact" type="submit"> Sign up </button>
          </div>

    </form>
    <br/>
    <br/>
    <p class="createact">  Already have an account? <a href="/login"> Log in </a> </p>

    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>


{{-- </div> --}}
</body>
</html>

{{-- <html>
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
       <a href="{{ route('logg') }}">  <div class="xmark"><i class="fa-solid fa-xmark"></i></div>
       </a>


        <div class="newsform">
            <h1 class="newsletterhead"> Create Your Account </h1>
            <p> Enter your personal details to get started </p>
            <br/>
            <form method="post" class="fstyle" action="/createnewadmin" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div>
                    <input class="nlstyle" type="text" name="firstname" placeholder="First Name" value="{{ old('firstname') }}">
                    @error('firstname')
                    <p class="inputerror"> {{$message}} </p>
                    @enderror

            </div>
            <div>
                <input class="nlstyle" type="text" name="lastname" placeholder="Last Name" value="{{ old('lastname') }}">
                @error('lastname')
                <p class="inputeerror"> {{$message}} </p>
                @enderror

        </div>
                <div>
                    <input class="nlstyle" type="email" name="email" placeholder="Email" value="{{ old('email') }}">
                    @error('email')
                    <p class="inputeerror"> {{$message}} </p>
                    @enderror

                </div>

                <div>
                    <input class="nlstyle" type="password" name="password" placeholder="Password" value="{{ old('password') }}">
                    @error('password')
                    <p class="inputeerror"> {{$message}} </p>
                    @enderror
                </div>
                      <div>
                    <button class="newsbtn" type="submit">Sign up</button>
                      </div>
                    </form>
                    <p class="createact"> Already have an account? Log in </p>


    </div>
</div>

</body>
</html> --}}




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
