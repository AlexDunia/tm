<head>

    <link
        rel="stylesheet"
        href="\css\admin.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
</head>
<body>
    {{-- Navigation panel --}}

    <div class="ctna">
        <div class="navflex">
          <div class="flogow">
            <img src="/images/tdlogo.png" alt="Logo"/>
          </div>
          <div class="navlinks">
            <ul>
              <li><a> Homee </a> </li>
              <li><a> Music </a></li>
              <li><a> Movies </a></li>
              <li><a> Theatre/Comedy</a></li>
              <li><a> Sportss </a></li>
              <li><a> Contact us </a></li>
            </ul>
          </div>
          <div>
            <button class="btn">
              My cart
            </button>
          </div>
        </div>
      </div>


    {{-- End of Nav bar --}}
    {{-- Other stuff --}}
    {{-- <h1> Hi admin </h1> --}}


    <div id="newslide" class="slideshow-container">
        @foreach($welcome->reverse()->take(3) as $index => $onewelcome)
        <a href="{{$onewelcome->date}}" class="slide-content"> {{$onewelcome['herolink']}}</a>
        <div class="newslideshow fade">
            <div class="slide-image" style="background-image: url('{{ asset('storage/' . $onewelcome->heroimage) }}')"></div>
        </div>
    @endforeach
    </div>
</div>
    <br/>
            <div class="circularbg">

            <div class="circular" style="background-image: url('{{ asset('images/crowd.jpg') }}');">
       <div class="view">
      <div class="latest"> <h1> Latest Events and Movies .</h1> </div>

        <div class="t_grids">
      <div class="one_e">
      <ul>
      <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
        <li class="noe"> WIZKID LIVE IN CONCERT  WIZKID LIVE IN CONCERT  </li>
          <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

         <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>

         <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>
        {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
        <button class="b_t"> Buy Ticket </button>
        </ul>
      </div>

      <div class="one_e">
        <ul>
        <li class="mypimage">  <img src="/images/asake.jpg" alt="Logo"/> </li>
          <li class="noe"> WIZKID LIVE IN CONCERT </li>
            <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

           <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>

           <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


          {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
          <button class="b_t"> Buy Ticket </button>
          </ul>
        </div>

        <div class="one_e">
            <ul>
            <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
              <li class="noe"> WIZKID LIVE IN CONCERT </li>
                <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

               <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
               <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


              {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
              <button class="b_t"> Buy Ticket </button>
              </ul>
            </div>

            <div class="one_e">
                <ul>
                <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                  <li class="noe"> WIZKID LIVE IN CONCERT </li>
                    <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                   <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                   <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


                  {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
                  <button class="b_t"> Buy Ticket </button>
                  </ul>
                </div>

                <div class="one_e">
                    <ul>
                    <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                      <li class="noe"> WIZKID LIVE IN CONCERT </li>
                        <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                       <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                       <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


                      {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
                      <button class="b_t"> Buy Ticket </button>
                      </ul>
                    </div>

                    <div class="one_e">
                        <ul>
                        <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                          <li class="noe"> WIZKID LIVE IN CONCERT </li>
                            <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                           <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                           <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


                          {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
                          <button class="b_t"> Buy Ticket </button>
                          </ul>
                        </div>

                        <div class="one_e">
                            <ul>
                            <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                              <li class="noe"> WIZKID LIVE IN CONCERT </li>
                                <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                               <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                               <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


                              {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
                              <button class="b_t"> Buy Ticket </button>
                              </ul>
                            </div>

                            <div class="one_e">
                                <ul>
                                <li class="mypimage">  <img src="/images/kizz.png" alt="Logo"/> </li>
                                  <li class="noe"> WIZKID LIVE IN CONCERT </li>
                                    <div class="toe"> <i class="fa-solid fa-location-dot"> </i>   Genesis Event Center, Lagos, Nigeria. </div>

                                   <div class="toe"> <i class="fa-solid fa-calendar-days"></i> December 15 @6:30pm </div>
                                   <div class="toe"> <i class="fa-solid fa-ticket"></i> Starting @5000 </div>


                                  {{-- <li class="toe"> {{ oneticket.Price }} </li> --}}
                                  <h3 class="b_t"> Buy Ticket </h3>
                                  </ul>
                                </div>


     </div>


      </div>
       </div>
    </div>





</body>
<script>
var slideIndex = 0;
showSlides();

function showSlides() {
    var slides = document.getElementsByClassName("newslideshow");
    var contents = document.getElementsByClassName("slide-content");
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }
    for (var i = 0; i < slides.length; i++) {
        slides[i].style.opacity = 0;
        contents[i].style.display = "none"; // Use quotes and set display to "none"
    }
    slides[slideIndex - 1].style.opacity = 1;
    contents[slideIndex - 1].style.display = "block"; // Use quotes and set display to "block"
    setTimeout(showSlides, 2000); // Change image every 2 seconds (adjust as needed)
}

</script>