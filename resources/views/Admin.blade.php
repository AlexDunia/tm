<form method="post" class="fstyle" action="/createnewadmin" enctype="multipart/form-data">

    {{ csrf_field() }}

    <div class="forminner">

        <input
            type="text"
            name="name"
            {{-- value="name" --}}
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
            {{-- value="password" --}}
        />
        @error('profilepic')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="password"
            name="password"
            {{-- value="password" --}}
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

</form>