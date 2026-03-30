<!-- =======================
    Footer START -->
<footer class="pt-0 bg-blue rounded-4 position-relative mx-2 mx-md-4 mb-3">
    <!-- SVG decoration for curve -->
    <figure class="mb-0">
        <svg class="fill-body rotate-180" width="100%" height="150" viewBox="0 0 500 150" preserveAspectRatio="none">
            <path d="M0,150 L0,40 Q250,150 500,40 L580,150 Z"></path>
        </svg>
    </figure>

    <div class="container">
        <div class="row mx-auto">
            <div class="col-lg-6 mx-auto text-center my-5">
                <!-- Logo -->
                <img class="mx-auto h-40px" src="{{ asset(\App\Helpers\Helper::getLogoLight()) }}" alt="logo">
                <p class="mt-3 text-white text-center">
                    Empowering professionals worldwide with practical learning, expert guidance, and career-boosting
                    certifications.
                </p>
                <!-- Links -->
                <ul class="nav justify-content-center text-primary-hover mt-3 mt-md-0">
                    <li class="nav-item"><a class="nav-link text-white" href="#">About</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Terms</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Privacy</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Career</a></li>
                    <li class="nav-item"><a class="nav-link text-white" href="#">Contact us</a></li>
                    <li class="nav-item"><a class="nav-link text-white pe-0" href="#">Cookies</a></li>
                </ul>
                <!-- Social media button -->
                <ul class="list-inline mt-3 mb-0">
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-facebook" href="#">
                            <i class="fab fa-fw fa-facebook-f"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-instagram" href="#">
                            <i class="fab fa-fw fa-instagram"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-twitter" href="#">
                            <i class="fab fa-fw fa-twitter"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="btn btn-white btn-sm shadow px-2 text-linkedin" href="#">
                            <i class="fab fa-fw fa-linkedin-in"></i>
                        </a>
                    </li>
                </ul>
                <!-- Bottom footer link -->
                <div class="mt-3 text-white">Copyrights ©{{ date('Y') }}
                    {{ \App\Helpers\Helper::getCompanyName() }}. Designed and Developed by <a
                        href="https://supersofttechnology.com/" class="text-reset btn-link text-primary-hover"
                        target="_blank">Supersoft Technology</a></div>
            </div>
        </div>
    </div>
</footer>
<!-- =======================
    Footer END -->

<!-- Cookie alert box START -->
<div class="alert alert-light fade show position-fixed start-0 bottom-0 z-index-99 rounded-3 shadow p-4 ms-3 mb-3 col-10 col-md-4 col-lg-3 col-xxl-2"
    role="alert" id="cookieBox">

    <div class="text-dark text-center">
        <img src="{{ 'frontAssets/images/element/27.svg' }}" class="h-50px mb-3" alt="cookie">

        <p class="mb-0">
            This website stores cookies on your computer. To find out more about the cookies we use,
            see our <a class="text-dark" href="#"><u> Privacy Policy</u></a>
        </p>

        <div class="mt-3">
            <button type="button" id="acceptCookies" class="btn btn-success-soft btn-sm mb-0">
                Accept
            </button>
            <button type="button" id="declineCookies" class="btn btn-danger-soft btn-sm mb-0">
                Decline
            </button>
        </div>
    </div>
</div>
<!-- Cookie alert box END -->

<!-- Back to top -->
<div class="back-top"><i class="bi bi-arrow-up-short position-absolute top-50 start-50 translate-middle"></i>
</div>
