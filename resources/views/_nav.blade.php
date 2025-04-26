<div class="nav-container">
    @auth
    <!-- Authenticated User Navigation -->
    <div class="ctna">
        <div class="navflex">
            <div class="flogow">
                <img src="/images/tdlogo.png" alt="Logo"/>
            </div>

            <div class="navlinks">
                <ul>
                    <li><a href="/">All</a></li>
                    <li><a href="/category/music">Music</a></li>
                    <li><a href="/category/movies">Movies</a></li>
                    <li><a href="/category/theatreandcomedy">Theatre/Comedy</a></li>
                    <li><a href="/category/sports">Sports</a></li>
                    <li><a href="/category/festivals">Festivals</a></li>
                    <li><a href="/category/others">Others</a></li>
                    <li><a href="/contact">Contact us</a></li>
                </ul>
            </div>

            @if(auth()->user()->profilepic)
            <div id="usericonid">
                <div class="circle-clip" id="usericonid">
                    <img src="{{ asset('storage/' . auth()->user()->profilepic) }}">
                </div>
            </div>
            @else
            <div id="usericonid">
                <div aria-hidden="true" class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials" style="width: 2.5rem; height: 2.5rem;">
                    <span class="initials">{{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}</span>
                    <span class="initials">{{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }}</span>
                </div>
            </div>
            <div id="cancelpop">
                <div aria-hidden="true" class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials" style="width: 2.5rem; height: 2.5rem;">
                    <span class="initials">{{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}</span>
                    <span class="initials">{{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Mobile Navigation for Auth User -->
    <div class="ctnamedia">
        <div class="navflex">
            <div class="flogow">
                <img src="/images/tdlogo.png" alt="Logo"/>
            </div>

            @if(auth()->user()->profilepic)
            <div id="usericonidres">
                <div class="circle-clip">
                    <img src="{{ asset('storage/' . auth()->user()->profilepic) }}">
                </div>
            </div>
            @else
            <div id="usericonidres">
                <div aria-hidden="true" class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials" style="width: 2.5rem; height: 2.5rem;">
                    <span class="initials">{{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}</span>
                    <span class="initials">{{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }}</span>
                </div>
            </div>
            <div id="cancelpopres">
                <div aria-hidden="true" class="user-profile-dropdown-module--dropdown-button-avatar--2jhme ud-avatar ud-heading-sm" data-purpose="display-initials" style="width: 2.5rem; height: 2.5rem;">
                    <span class="initials">{{ strtoupper(substr(auth()->user()->firstname, 0, 1)) }}</span>
                    <span class="initials">{{ strtoupper(substr(auth()->user()->lastname, 0, 1)) }}</span>
                </div>
            </div>
            @endif

            <div class="lisures">
                <h3><a href="/login">Log in</a></h3>
            </div>

            <div>
                <button class="menuu" onclick="menutoggleauth()" aria-expanded="true">
                    <svg width="50" height="50" viewBox="0 0 100 100">
                        <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058"></path>
                        <path class="line line2" d="M 20,50 H 80"></path>
                        <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mqlist">
            <ul class="mqul">
                <li><a href="/">All</a></li>
                <li><a href="/category/music">Music</a></li>
                <li><a href="/category/movies">Movies</a></li>
                <li><a href="/category/theatreandcomedy">Theatre/Comedy</a></li>
                <li><a href="/category/sports">Sports</a></li>
                <li><a href="/category/festivals">Festivals</a></li>
                <li><a href="/category/others">Others</a></li>
                <li><a href="/contact">Contact us</a></li>
            </ul>
        </div>
    </div>

    <!-- User dropdown menu -->
    <div class="lisu">
        <form method="POST" action="/logout">
            @csrf
            <button><a>Log out</a></button>
        </form>
        <h3><a>Sign up</a></h3>
    </div>

    @else
    <!-- Guest Navigation -->
    <div class="ctnacon">
        <div class="ctna">
            <div class="navflex">
                <div class="flogow">
                    <img src="/images/tdlogo.png" alt="Logo"/>
                </div>

                <div class="navlinks">
                    <ul>
                        <li><a href="/">All</a></li>
                        <li><a href="/category/music">Music</a></li>
                        <li><a href="/category/movies">Movies</a></li>
                        <li><a href="/category/theatreandcomedy">Theatre/Comedy</a></li>
                        <li><a href="/category/sports">Sports</a></li>
                        <li><a href="/category/festivals">Festivals</a></li>
                        <li><a href="/category/others">Others</a></li>
                        <li><a href="/contact">Contact us</a></li>
                    </ul>
                </div>

                <div class="userandcart">
                    <div class="user" id="usericonid">
                        <i class="fa fa-circle-user"></i>
                    </div>
                    <div class="user" id="cancelpop">
                        <i class="fa fa-circle-user"></i>
                    </div>
                </div>

                <div class="lisu">
                    <h3><a href="/login">Log in</a></h3>
                    <h3><a href="/signup">Sign up</a></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation for Guest -->
    <div class="ctnamedia">
        <div class="navflex">
            <div class="flogow">
                <img src="/images/tdlogo.png" alt="Logo"/>
            </div>

            <div class="userandcart">
                <div class="user" id="usericonidres">
                    <i class="fa fa-circle-user"></i>
                </div>
                <div class="user" id="cancelpopres">
                    <i class="fa fa-circle-user"></i>
                </div>
            </div>

            <div class="lisures">
                <h3><a href="/login">Log in</a></h3>
                <h3><a href="/signup">Sign up</a></h3>
            </div>

            <div>
                <button class="menu" onclick="menutoggle()" aria-expanded="true">
                    <svg width="50" height="50" viewBox="0 0 100 100">
                        <path class="line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058"></path>
                        <path class="line line2" d="M 20,50 H 80"></path>
                        <path class="line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="mqlist">
            <ul class="mqul">
                <li><a href="/">All</a></li>
                <li><a href="/category/music">Music</a></li>
                <li><a href="/category/movies">Movies</a></li>
                <li><a href="/category/theatreandcomedy">Theatre/Comedy</a></li>
                <li><a href="/category/sports">Sports</a></li>
                <li><a href="/category/festivals">Festivals</a></li>
                <li><a href="/category/others">Others</a></li>
                <li><a href="/contact">Contact us</a></li>
            </ul>
        </div>
    </div>
    @endauth
</div>

<script>
// Cancel pop up
document.addEventListener('DOMContentLoaded', function() {
    const cpop = document.getElementById("cancelpop");
    const uid = document.getElementById("usericonid");
    const lisu = document.querySelector(".lisu");

    const cpopres = document.getElementById("cancelpopres");
    const uidres = document.getElementById("usericonidres");
    const lisures = document.querySelector(".lisures");

    // Set a scroll threshold in pixels
    const scrollThreshold = 0;

    if (cpop) {
        cpop.addEventListener("click", function() {
            lisu.style.display = "none";
            uid.style.display = "block";
            cpop.style.display = "none";
        });
    }

    if (uid) {
        uid.addEventListener("click", function() {
            lisu.style.display = "block";
            uid.style.display = "none";
            cpop.style.display = "block";
        });
    }

    // Media query nav
    if (uidres) {
        uidres.addEventListener("click", function() {
            lisures.style.display = 'block';
            uidres.style.display = "none";
            if (cpopres) cpopres.style.display = "block";
        });
    }

    if (cpopres) {
        cpopres.addEventListener("click", function() {
            lisures.style.display = 'none';
            uidres.style.display = "block";
            cpopres.style.display = "none";
        });
    }

    // Function to toggle the visibility of the div based on the scroll position
    function toggleDivVisibility() {
        if (window.scrollY >= scrollThreshold) {
            if (lisures) lisures.style.display = 'none';
            if (uidres) uidres.style.display = "block";
            if (cpopres) cpopres.style.display = "none";
        } else {
            if (cpop) cpop.style.display = "none";
            if (lisures) lisures.style.display = 'none';
        }
    }

    // Attach the toggleDivVisibility function to the scroll event
    window.addEventListener('scroll', toggleDivVisibility);
});

function menutoggle() {
    const menuu = document.querySelector(".mqlist");
    const burger = document.querySelector(".menu");

    if (menuu.style.display === "block") {
        menuu.style.display = "none";
        burger.setAttribute('aria-expanded', burger.classList.contains('opened'));
        burger.classList.remove('opened');
    } else {
        menuu.style.display = "block";
        burger.classList.toggle('opened');
    }
}

function menutoggleauth() {
    const menuuu = document.querySelector(".mqlist");
    const burgerr = document.querySelector(".menuu");

    if (menuuu.style.display === "block") {
        menuuu.style.display = "none";
        burgerr.setAttribute('aria-expanded', burgerr.classList.contains('opened'));
        burgerr.classList.remove('opened');
    } else {
        menuuu.style.display = "block";
        burgerr.classList.toggle('opened');
    }
}
</script>
