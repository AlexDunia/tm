@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="content-wrapper">
        <div class="tyaround">
            <div class="tybg">
                <h1>Oops! nothing is found</h1>
                <br/>
                <p class="pty">There are currently none for now, Kindly check back later or <a href="/">Go Back to Home</a></p>
            </div>
        </div>
    </div>
</div>
@endsection

<style>
    .tyaround{
        width:80%;
        margin:auto;
        margin-top:40px;
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
