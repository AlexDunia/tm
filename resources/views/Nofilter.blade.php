<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name= "viewport" content="width=device-width, initial-scale=1">

<title> Nothing is found, check back later </title>
<link rel="stylesheet" href="css/admin.css">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="assets/img/favicon.ico" rel="icon">

</head>
<style>

    body{
        padding:0;
        margin:0;
    }

    .tyaround{
        width:80%;
        margin:auto;
        margin-top:100px;
        position:relative;
    }

    .tybg{
        background: rgba(37, 36, 50, .8);
        color: #a0aec0;
        padding-bottom:150px;
        padding-top:40px;
        padding-left:50px;
    }

    .tybg p {
        font-size:15px;
        color: #a0aec0;
        margin-bottom:15px;
    }

    .tybg h1{
        font-size:30px;
    }

    .tybg a{
        color: #C04888;
        font-size:15px;
        margin-top:5px;
    }

    .pty{
        color: #a0aec0;
    }

    @media (max-width: 700px) {
        .tybg{
        background: rgba(37, 36, 50, .8);
        color: #a0aec0;
        padding-bottom:50px;
        padding-top:40px;
        padding-left:30px;
        padding-right:30px;
    }

    .tybg p {
        font-size:14px;
        color: #a0aec0;
        margin-bottom:10px;
    }

    .tyaround{
        width:85%;
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

    <div class="tyaround">
    <div class="tybg">
        <h1> Oops! nothing is found </h1>
        <br/>
        <p class="pty"> There are currently none for now, Kindly check back later or <a href="/"> Go Back to Home </a> </p>


    </div>

</div>
</body>
</html>
