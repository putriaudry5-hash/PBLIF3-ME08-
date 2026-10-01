document.addEventListener('DOMContentLoaded', function () {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    const detailButtons = document.querySelectorAll('.online-detail-btn');

    detailButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            const id = this.dataset.id;
            const detail = document.getElementById('onlineDetail' + id);

            if (!detail) {
                return;
            }

            if (detail.style.display === 'none') {
                detail.style.display = 'table-row';
            } else {
                detail.style.display = 'none';
            }
        });
    });
});