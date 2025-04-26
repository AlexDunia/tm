@extends('layouts.app')

@section('content')
<div class="main-container">
    <div class="content-wrapper">
        <div class="tyaround">
            <h1>Contact Us</h1>
            <form method="post" action="/formsent">
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

                <button class="btncontact">Submit</button>
            </form>
        </div>
    </div>
</div>
@endsection

<style>
    .tyaround {
        width: 85%;
        margin: auto;
        padding-top: 20px;
        padding-bottom: 50px;
    }

    .tyaround h1 {
        font-size: 45px;
        margin-top: 30px;
        margin-bottom: 60px;
        color: white;
    }

    .tyaround form label {
        font-size: 20px;
        color: white;
    }

    form input,
    form textarea {
        width: 100%;
        padding: 20px;
        background: rgba(37, 36, 50, .8);
        border: none;
        margin-top: 20px;
        box-sizing: border-box;
        color: white;
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
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btncontact:hover {
        background-color: #aa3a72;
    }

    .lab {
        margin-top: 20px;
    }

    @media (max-width: 700px) {
        .tyaround h1 {
            font-size: 30px;
        }
    }
</style>
