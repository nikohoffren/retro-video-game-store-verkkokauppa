
<footer class="page-footer custom-linear-gradient">
    <div class="container">
        <div class="row">
            <div class="col l6 s12">
                <h5 class="white-text">YHTEYSTIEDOT</h5>
                <p class="grey-text text-lighten-4">RETRO GAMES</p>
                <p class="grey-text text-lighten-4">Retrokuja 69</p>
                <p class="grey-text text-lighten-4">69420 KUOPIO</p>
                <p class="grey-text text-lighten-4">050-666 6666</p>

                <div class="icons">
                    <a href="https://www.facebook.com" target="_blank"><i class="fa fa-facebook"></i></a>
                    <a href="https://www.twitter.com" target="_blank"><i class="fa fa-twitter"></i></a>
                    <a href="https://www.instagram.com" target="_blank"><i class="fa fa-instagram"></i></a>
                    <a href="https://www.linkedin.com" target="_blank"><i class="fa fa-linkedin"></i></a>
                </div>
            </div>

            <div class="col l4 offset-l2 s12">
                <h5 class="white-text">Lisätietoa konsoleista</h5>
                <ul>
                    <li><a class="grey-text text-lighten-3" href="https://fi.wikipedia.org/wiki/Atari_2600" target="_blank">Atari 2600</a></li>
                    <li><a class="grey-text text-lighten-3" href="https://fi.wikipedia.org/wiki/Nintendo_Entertainment_System" target="_blank">NES</a></li>
                    <li><a class="grey-text text-lighten-3" href="https://fi.wikipedia.org/wiki/Super_Nintendo_Entertainment_System" target="_blank">SNES</a></li>
                    <li><a class="grey-text text-lighten-3" href="https://fi.wikipedia.org/wiki/Nintendo_64" target="_blank">Nintendo 64</a></li>
                    <li><a class="grey-text text-lighten-3" href="https://fi.wikipedia.org/wiki/Sega_Master_System" target="_blank">Sega Master System</a></li>
                    <li><a class="grey-text text-lighten-3" href="https://fi.wikipedia.org/wiki/Sega_Mega_Drive" target="_blank">Sega Mega Drive / Genesis</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="footer-copyright">
        <div class="container">
            Copyright &#169; 2023 Niko Hoffrén
            <a class="grey-text text-lighten-4 right" href="terms_of_service">Toimitusehdot</a><br />
            <a class="grey-text text-lighten-4 right" href="privacy_policy">Tietosuojaseloste</a>
        </div>
    </div>
</footer>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<script src="https://www.paypal.com/sdk/js?client-id=AT6P8Rd5g6yER5XrjsM8O7jts3togLy7O3mE-TgjKJw_9dB58TgHcR71lwj73514Zq4fU-oYszMo9MUE"></script>

<script type="text/javascript">
    //* search bar setup
    document.addEventListener('DOMContentLoaded', () => {
        let elems = document.querySelectorAll('#search')
        let instances = M.Sidenav.init(elems, options)
    })

    //* mobile sidebar setup
    const navLinks = document.querySelector("#navLinks")
    const showMenu = () =>
        navLinks.style.right = "0"
    const hideMenu = () =>
        navLinks.style.right = "-200px"

    //* collapsible setup
    document.addEventListener('DOMContentLoaded', () => {
    const elems = document.querySelectorAll('.collapsible')
    const instances = M.Collapsible.init(elems)
    });

    //* password eye toggle 1
    const togglePassword1 = document.querySelector('#togglePassword1')
    const password1 = document.querySelector('#id_password_1')
    togglePassword1.addEventListener('click', (e) => {
        const type = password1.getAttribute('type') === 'password' ? 'text' : 'password'
        password1.setAttribute('type', type)
        this.classList.toggle('fa-eye-slash')
    })

    //* password eye toggle 2
    const togglePassword2 = document.querySelector('#togglePassword2')
    const password2 = document.querySelector('#id_password_2')
    togglePassword2.addEventListener('click', (e) => {
        const type = password2.getAttribute('type') === 'password' ? 'text' : 'password'
        password2.setAttribute('type', type)
        this.classList.toggle('fa-eye-slash')
    })

    //* facebook login setup
    window.fbAsyncInit = () => {
        FB.init({
        appId      : '{your-app-id}',
        cookie     : true,
        xfbml      : true,
        version    : '{api-version}'
        })

        FB.AppEvents.logPageView()
    }

    (function(d, s, id){
        let js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {return;}
        js = d.createElement(s); js.id = id
        js.src = "https://connect.facebook.net/en_US/sdk.js"
        fjs.parentNode.insertBefore(js, fjs)
    }(document, 'script', 'facebook-jssdk'))
</script>

</body>
