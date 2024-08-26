document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll('.calendar-nav-icon').forEach(function (element) {
        element.addEventListener('click', function (event) {
            event.preventDefault();
            const url = this.getAttribute('href');
            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const calendarContent = doc.querySelector('.main-container').innerHTML;
                    document.querySelector('.main-container').innerHTML = calendarContent;
                    window.history.pushState({ path: url }, '', url);
                })
                .catch(err => console.warn('Something went wrong.', err));
        });
    });
});
