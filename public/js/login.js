document.addEventListener(
    'DOMContentLoaded',
    function () {

        const password =
            document.getElementById('password');

        const toggle =
            document.getElementById('passwordToggle');

        if (!password || !toggle) {
            return;
        }

        toggle.addEventListener(
            'click',
            function () {

                const hidden =
                    password.type === 'password';

                password.type =
                    hidden
                        ? 'text'
                        : 'password';

                toggle.textContent =
                    hidden
                        ? 'Sembunyikan'
                        : 'Lihat';
            }
        );
    }
);