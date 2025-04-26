<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name= "viewport" content="width=device-width, initial-scale=1">
<title> Log in </title>
<link rel="stylesheet" href="css/style.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="assets/img/favicon.ico" rel="icon">
<script src="//unpkg.com/alpinejs" defer></script>

</head>
<style>

    body{
        padding:0;
        margin:0;
        color: white;
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
        margin-bottom:60px;
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

    .fstyle .inputerror{
        color:red;
        font-weight:600;
    }

    input {
    font-size:16px;
  color: white;
}

  .inputerror{
        color:red;
        font-weight:600;
    }

    .qerror {
    background: #C04888;
    padding: 10px;
    color: white;
    font-weight: 600;
    font-size: 15px;
    position: fixed;
    top: 180;
    z-index: 2;
    right: 20;
    max-width: 50%;
    box-sizing: border-box;
}

.fstyle a{
            color:#C04888;
            font-weight:600;
            margin-left:10px;
            font-size:18px;
            text-decoration:none;
        }

        .fstyle a:hover{
            color:blue;
            cursor:pointer;
            font-weight:600;
            margin-left:10px;
            font-size:18px;
            text-decoration:underline;
        }

    .lab{
        margin-top:20px;
    }

    @media (max-width: 700px) {

        .tyaround h1{
        font-size:30px;
    }

    .qerror {
    background: #C04888;
    width: 100%;
    font-weight: 300;
    font-size: 10px;
    max-width: 100%;
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


        @if(session()->Has("message"))
            <div class="qerror" x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show">
                <p> Password Reset Link Sent To Your Email! Click to Change Now. </p>
                </div>
        @endif



        {{-- <div class="qerror" >
            <p> AT LEAST ONE QUANTITY MUST BE ADDED BEFORE PROCEEDING! </p>
         </div> --}}

        <div class="tyaround">
        <h1> Login to your account. </h1>
        <form method="post" class="fstyle" action="/authenticated">
        @csrf
        <label for="email"> Email: </label>
        <input id="email" class="nlstyle" type="email" name="email" placeholder="Your Email">
        @error('email')
        <p class="inputeerror" style="color:rgb(252, 80, 80)"> {{$message}} </p>
        @enderror </br>

        <br/>
        <br/>
        <label for="password">Password:</label>
        <input id="email" class="nlstyle" type="password" name="password" placeholder="Your Password">
        @error('password')
        <p class="inputeerror" style="color:rgb(252, 80, 80)"> {{$message}} </p>
        @enderror


        <br/>
        <br/>
        <br/>
        <br/>
        <a href="{{ route('fp') }}"> Forgot Password </a> </p>
        <br/>

          <div>
        <button class="btncontact" type="submit"> Log In </button>
          </div>


    </form>

    <br/>
    <br/>
    <br/>
    <br/>
    <br/>
    <br/>


{{-- </div> --}}
</body>
</html>

{{-- <head>

    <link
        rel="stylesheet"
        href="\css\signup.css"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>


        <div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
    <div class="popupwithdraww" id="popupwhitewithdraww">
       <a href="{{ route('logg') }}">  <div class="xmark"><i class="fa-solid fa-xmark"></i></div>
       </a>


        <div class="newsform">
            <h1 class="newsletterhead"> Log in</h1>
            <p> Enter your Login details </p>
            <br/>
            <form method="post" class="fstyle" action="/authenticated" enctype="multipart/form-data">

                {{ csrf_field() }}
                <div>
                    <input id="email" class="nlstyle" type="email" name="email" placeholder="Your Email">
                    @error('email')
                    <p class="inputeerror" style="color:rgb(252, 80, 80)"> {{$message}} </p>
                    @enderror

                </div>

                <div>
                    <input id="email" class="nlstyle" type="password" name="password" placeholder="Your Password">
                    @error('password')
                    <p class="inputeerror" style="color:rgb(252, 80, 80)"> {{$message}} </p>
                    @enderror

                    <a> Forgot Password </a>
                </div>
                      <div>
                    <button class="newsbtn" type="submit"> Log In </button>
                      </div>

                    </form>
                    <p class="createact"> Already have an account? Log in </p>


    </div>
</div>

</body> --}}