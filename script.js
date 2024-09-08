document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const input = document.querySelector('input[type="url"]');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        if (input.checkValidity()) {
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            submitButton.disabled = true;
            form.submit();
        }
    });

    input.addEventListener('input', function() {
        if (this.value.length > 0) {
            this.classList.add('has-content');
        } else {
            this.classList.remove('has-content');
        }
    });
});

function copyShortUrl() {
    const shortUrl = document.getElementById('shortUrl');
    const copyButton = document.getElementById('copyButton');

    shortUrl.select();
    shortUrl.setSelectionRange(0, 99999); // For mobile devices

    navigator.clipboard.writeText(shortUrl.value).then(() => {
        copyButton.innerHTML = '<i class="fas fa-check"></i>';
        copyButton.style.backgroundColor = '#27ae60';

        setTimeout(() => {
            copyButton.innerHTML = '<i class="fas fa-copy"></i>';
            copyButton.style.backgroundColor = '';
        }, 2000);
    }).catch(err => {
        console.error('Failed to copy: ', err);
        copyButton.innerHTML = '<i class="fas fa-times"></i>';
        copyButton.style.backgroundColor = '#e74c3c';

        setTimeout(() => {
            copyButton.innerHTML = '<i class="fas fa-copy"></i>';
            copyButton.style.backgroundColor = '';
        }, 2000);
    });
}