document.getElementById('theme-toggle').addEventListener('click', function() {
    document.body.classList.toggle('dark-mode');

    if (document.body.classList.contains('dark-mode')) {
        this.textContent = 'Light Mode';
        this.classList.remove('btn-light');
        this.classList.add('btn-dark');
    } else {
        this.textContent = 'Dark Mode';
        this.classList.remove('btn-dark');
        this.classList.add('btn-light');
    }
});