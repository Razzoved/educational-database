<a class="go-up" onclick="goUp()">
    <i class="fa fa-arrow-circle-up" aria-hidden="true"></i><br>
    <span class="go-up__text">Go up</span>
    <script>
        let goUpButton = document.querySelector(".go-up");

        window.onscroll = () => {
            if (document.body.scrollTop > 150 || document.documentElement.scrollTop > 150) {
                goUpButton.style.display = "block";
            } else {
                goUpButton.style.display = "none";
            }
        };

        const goUp = () => {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</a>
