<!-- Dark mode -->
<script>
    const storedTheme = localStorage.getItem('theme')

    const getPreferredTheme = () => {
        if (storedTheme) {
            return storedTheme
        }
        return window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'light'
    }

    const setTheme = function(theme) {
        if (theme === 'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches) {
            document.documentElement.setAttribute('data-bs-theme', 'dark')
        } else {
            document.documentElement.setAttribute('data-bs-theme', theme)
        }
    }

    setTheme(getPreferredTheme())

    window.addEventListener('DOMContentLoaded', () => {
        var el = document.querySelector('.theme-icon-active');
        if (el != 'undefined' && el != null) {
            const showActiveTheme = theme => {
                const activeThemeIcon = document.querySelector('.theme-icon-active use')
                const btnToActive = document.querySelector(`[data-bs-theme-value="${theme}"]`)
                const svgOfActiveBtn = btnToActive.querySelector('.mode-switch use').getAttribute('href')

                document.querySelectorAll('[data-bs-theme-value]').forEach(element => {
                    element.classList.remove('active')
                })

                btnToActive.classList.add('active')
                activeThemeIcon.setAttribute('href', svgOfActiveBtn)
            }

            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => {
                if (storedTheme !== 'light' || storedTheme !== 'dark') {
                    setTheme(getPreferredTheme())
                }
            })

            showActiveTheme(getPreferredTheme())

            document.querySelectorAll('[data-bs-theme-value]')
                .forEach(toggle => {
                    toggle.addEventListener('click', () => {
                        const theme = toggle.getAttribute('data-bs-theme-value')
                        localStorage.setItem('theme', theme)
                        setTheme(theme)
                        showActiveTheme(theme)
                    })
                })

        }
    })
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {

        let cookieBox = document.getElementById("cookieBox");

        // 🔹 agar pehle select nahi kia to show karo
        if (!localStorage.getItem("cookieChoice")) {
            cookieBox.style.display = "block";
        }

        // ✅ Accept
        document.getElementById("acceptCookies").addEventListener("click", function() {
            localStorage.setItem("cookieChoice", "accepted");
            cookieBox.style.display = "none";
        });

        // ❌ Decline
        document.getElementById("declineCookies").addEventListener("click", function() {
            localStorage.setItem("cookieChoice", "declined");
            cookieBox.style.display = "none";
        });

    });
</script>

<!-- Bootstrap JS -->
<script src="{{ asset('frontAssets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

<!-- Vendors -->
<script src="{{ asset('frontAssets/vendor/tiny-slider/tiny-slider.js') }}"></script>
<script src="{{ asset('frontAssets/vendor/glightbox/js/glightbox.js') }}"></script>

<!-- Template Functions -->
<script src="{{ asset('frontAssets/js/functions.js') }}"></script>
