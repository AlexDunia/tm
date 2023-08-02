<h3> Edit post </h3>

    <form method="POST" class="fstyle" action="/createticket">

    {{ csrf_field() }}

    <div class="forminner">

        <input
            type="text"
            name="name"
            value="{{$lexlist->name}}"
        />
        @error('name')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="text"
            name="location"
            value="{{$lexlist->location}}"
        />
        @error('location')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="text"
            name="description"
            value="{{$lexlist->description}}"
        />
        @error('description')
        <p> {{$message}} </p>
        @enderror
    </div>


    {{-- <div class="forminner">

        <input
            type="file"
            name="image"
            value="image"
        />

    </div> --}}


    <div class="forminner">

        <input
            type="text"
            name="date"
            value="date"
        />
        @error('date')
        <p> {{$message}} </p>
        @enderror
    </div>


    <div class="forminner">

        <input
            type="text"
            name="location"
            value="location"
        />
        @error('location')
        <p> {{$message}} </p>
        @enderror
    </div>

{{--
    <div class="forminner">
        <br/>
        <br/>
        <label
            for="password"
        >
            Password
        </label>
        <br/>
        <br/>
        <input
            type="password"
            name="password"
            value="password"
        />

    </div> --}}


    {{-- <div class="forminner">
        <br/>
        <br/>
        <label
            for="password"
        >
            Confirm password
        </label>
        <br/>
        <br/>
        <input
            type="password"
            name="password"
            value="{{old('password')}}"
        />

    </div> --}}

    <br/>
    <br/>
    <br/>

    <div class="forminner">
        <button
            type="submit"
        >
        Create Event
        </button>
    </div>

    {{-- <div class="mt-8">

            <a href="/register" class="text-laravel"
                > SCreate an account </a
            >

    </div> --}}

</form>