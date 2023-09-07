<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title> Contact us </title>
{{-- <link rel="stylesheet" href="css/style.css"> --}}
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="assets/img/favicon.ico" rel="icon">

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
        font-size:45px;
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
        <h1>Contact Us</h1>
    <form method="post" action="/submit">
        @csrf
        <br/>
        <br/>
        <label for="name" class="lab">Name:</label>
        <input type="text" id="name" name="name" required></br>

        <br/>
        <br/>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required></br>

        <br/>
        <br/>
        <label for="phone">Phone Number:</label>
        <input type="tel" id="phone" name="phone" required></br>

        <br/>
        <br/>
        <label for="comment">Comment:</label></br>
        <textarea id="comment" name="comment" rows="4" required></textarea></br>

        <button class="btncontact"> Submit </button>
    </form>
        {{-- <p class="pty"> There are currently no events for now, Kindly check back later or <a href="/"> Go Back to Home </a> </p> --}}


    </div>

{{-- </div> --}}
</body>
</html>
