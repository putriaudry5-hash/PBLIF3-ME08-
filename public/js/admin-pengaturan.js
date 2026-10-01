document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const alerts = document.querySelectorAll(
        '.setting-alert-success'
    );

    alerts.forEach(function (item) {
        setTimeout(function () {
            item.classList.add('hide');
        }, 3000);

        setTimeout(function () {
            item.remove();
        }, 3400);
    });

    const toggles = document.querySelectorAll(
        '.setting-password-toggle'
    );

    toggles.forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            const hidden = input.type === 'password';

            input.type = hidden
                ? 'text'
                : 'password';

            this.innerHTML = hidden
                ? '<i data-lucide="eye-off"></i>'
                : '<i data-lucide="eye"></i>';

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    });

    const settingForm = document.querySelector(
        '.setting-form'
    );

    const jamBuka = document.getElementById('jamBuka');
    const jamTutup = document.getElementById('jamTutup');

    if (settingForm) {
        settingForm.addEventListener(
            'submit',
            function (event) {
                if (
                    jamBuka &&
                    jamTutup &&
                    jamBuka.value &&
                    jamTutup.value &&
                    jamTutup.value <= jamBuka.value
                ) {
                    event.preventDefault();

                    window.alert(
                        'Jam tutup harus lebih besar dari jam buka.'
                    );

                    jamTutup.focus();
                }
            }
        );
    }

    const passwordForm = document.querySelector(
        '.setting-password-form'
    );

    if (passwordForm) {
        passwordForm.addEventListener(
            'submit',
            function (event) {
                const password = document.getElementById(
                    'passwordBaru'
                );

                const confirmation = document.getElementById(
                    'passwordKonfirmasi'
                );

                if (
                    !password ||
                    !confirmation
                ) {
                    return;
                }

                if (password.value.length < 6) {
                    event.preventDefault();

                    window.alert(
                        'Password baru minimal 6 karakter.'
                    );

                    password.focus();

                    return;
                }

                if (
                    password.value !==
                    confirmation.value
                ) {
                    event.preventDefault();

                    window.alert(
                        'Konfirmasi password baru tidak sesuai.'
                    );

                    confirmation.focus();
                }
            }
        );
    }
});