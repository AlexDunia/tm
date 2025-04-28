<head>

    <link
        rel="stylesheet"
        href="\css\admin.css"/>
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css">
        <script src="https://kit.fontawesome.com/9ff47ec0f8.js" crossorigin="anonymous"> </script>
    <style>
        .admin-controls {
            background: linear-gradient(145deg, #23222f 0%, #1a1922 100%);
            border-radius: 15px;
            padding: 25px;
            margin: 30px auto;
            max-width: 1200px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .admin-title {
            color: #C04888;
            font-size: 28px;
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: center;
        }

        .admin-btn {
            background: linear-gradient(to right, #C04888, #a43a72);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .admin-btn:hover {
            background: linear-gradient(to right, #a43a72, #C04888);
            transform: translateY(-2px);
        }

        .admin-btn i {
            font-size: 18px;
        }
    </style>
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
              <li><a href="/"> Home </a> </li>
              <li><a href="/category/Music"> Music </a></li>
              <li><a href="/category/Movies"> Movies </a></li>
              <li><a href="/category/Theatre"> Theatre/Comedy</a></li>
              <li><a href="/category/Sports"> Sports </a></li>
              <li><a href="/contact"> Contact us </a></li>
            </ul>
          </div>
          <div>
            <button class="btn">
              My cart
            </button>
          </div>
        </div>
      </div>

    {{-- Admin Control Panel --}}
    <div class="admin-controls">
        <h2 class="admin-title">Admin Control Panel</h2>
        <div class="admin-buttons">
            <a href="/createpost" class="admin-btn">
                <i class="fa-solid fa-plus"></i> Basic Event Creator
            </a>
            <a href="/admin/events/create" class="admin-btn">
                <i class="fa-solid fa-calendar-plus"></i> Advanced Event Creator
            </a>
            <a href="/events" class="admin-btn">
                <i class="fa-solid fa-list"></i> Manage Events
            </a>
            <a href="/reports" class="admin-btn">
                <i class="fa-solid fa-chart-line"></i> Sales Reports
            </a>
        </div>
    </div>

    {{-- End of Nav bar --}}
    {{-- Other stuff --}}
    {{-- <h1> Hi admin </h1> --}}


    {{-- <div id="newslide" class="slideshow-container">
        @foreach($welcome->reverse()->take(3) as $index => $onewelcome)
        <a href="{{$onewelcome->date}}" class="slide-content"> {{$onewelcome['herolink']}}</a>
        <div class="newslideshow fade">
            <div class="slide-image" style="background-image: url('{{ asset('storage/' . $onewelcome->heroimage) }}')"></div>
        </div>
    @endforeach
    </div>
</div> --}}
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
