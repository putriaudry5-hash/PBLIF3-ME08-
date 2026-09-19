const passwordInput =
    document.getElementById('password');

const togglePassword =
    document.getElementById('togglePassword');

const loginForm =
    document.getElementById('loginForm');


if (togglePassword && passwordInput) {

    togglePassword.addEventListener('click', function () {

        if (passwordInput.type === 'password') {

            passwordInput.type = 'text';

            togglePassword.textContent =
                'Sembunyikan';

        } else {

            passwordInput.type = 'password';

            togglePassword.textContent =
                'Lihat';

        }

    });

}


if (loginForm) {

    loginForm.addEventListener('submit', function (event) {

        event.preventDefault();

        const email =
            document.getElementById('email').value.trim();

        const password =
            passwordInput.value.trim();


        if (!email || !password) {

            alert(
                'Email dan password wajib diisi.'
            );

            return;

        }


        alert(
            'Frontend login sudah berjalan. Backend akan dihubungkan nanti.'
        );

    });

}