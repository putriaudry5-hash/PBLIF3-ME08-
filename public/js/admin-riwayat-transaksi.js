document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const buttons = document.querySelectorAll('.history-detail-btn');

    buttons.forEach(function (button) {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const detail = document.getElementById(
                'historyDetail' + id
            );

            if (!detail) {
                return;
            }

            detail.style.display =
                detail.style.display === 'none'
                    ? 'table-row'
                    : 'none';
        });
    });
});