<!DOCTYPE html>
<html>
<head>
    <title>Login Device Info</title>
</head>
<style>

body {

   font-family: "Montserrat",
      sans-serif;
   -webkit-font-smoothing: antialiased;
   /* font-family: 'Ubuntu',
      sans-serif; */
}

ul{
    list-style: none;
}

.formcontainer{
    width:80%;
    margin:auto;
}

.infologin{
    margin-top:30px;
    margin-bottom:30px;
}

.headinfo{
    color:#f746a5;
}

.if{
    line-height:1.7em;
}

.if a{
    color:#f746a5;
    font-weight:700;
}
.formcontainer img {
            display: block; /* Center the image */
            margin: 0 auto; /* Center the image horizontally */
            margin-bottom: 50px;
        }

</style>

<body>

    <div class="formcontainer">
    <a href="https://www.tixdemand.com/" target="_blank"> <img src="/images/tdlogob.jpg" alt="tixdemand"/> </a>
    <h2> Hi {{$firstname}} </h2>
    <h3> Welcome to Tixdemand, your best moments, on demand!</h3>
    <hr/>
    <br/>

        <p class="infologin"> We are exited to embark on this journey with you. </p>

</div>
<p class="if"> <a> Feel free to reply this message if you have any questions <a/> </p>
<hr/>
    <!-- You can customize the email content and styling as per your requirements -->
</body>
</html>
