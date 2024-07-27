document.addEventListener('DOMContentLoaded', function () {
    const banners = document.querySelectorAll('.sgc-banner a');

    banners.forEach(function (banner) {
        banner.addEventListener('click', function (e) {
            e.preventDefault();

            const bannerId = this.dataset.bannerId;
            const bannerUrl = this.href;

            fetch(sgc_clicks_tracker.ajax_url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({
                    action: 'register_click',
                    banner_id: bannerId,
                }),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = bannerUrl;
                    } else {
                        console.error(data.message);
                    }
                })
                .catch(error => console.error('Error:', error));
        });
    });
});
