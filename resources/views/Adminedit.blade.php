<h1> Edit Tix demand screen </h1>
<form method="post" class="fstyle" action="/creationsuccess" enctype="multipart/form-data">

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
            type="text"
            name="location"

        />
        @error('location')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="text"
            name="description"
            {{-- value="password" --}}
        />
        @error('description')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="text"
            name="date"
            {{-- value="password" --}}
        />
        @error('date')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="file"
            name="image"
            {{-- value="password" --}}
        />
        @error('image')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="file"
            name="heroimage"
            {{-- value="password" --}}
        />
        @error('heroimage')
        <p> {{$message}} </p>
        @enderror
    </div>

    <div class="forminner">

        <input
            type="text"
            name="herolink"
            {{-- value="password" --}}
        />
        @error('herolink')
        <p> {{$message}} </p>
        @enderror
    </div>


    <br/>
    <br/>

    <div class="forminner">
        <button
            type="submit"
        >
        Create post
        </button>
    </div>


</form>