document.addEventListener('submit', async function (event) {
    var form = event.target.closest('.js-cart-form');
    if (!form) {
        return;
    }

    event.preventDefault();
    var button = form.querySelector('button[type="submit"]');
    var previousText = button.textContent;
    button.disabled = true;

    try {
        var response = await fetch(form.getAttribute('action'), {
            method: 'POST',
            body: new FormData(form),
            headers: { Accept: 'application/json' },
            credentials: 'same-origin'
        });
        var result = await response.json();

        if (!response.ok || !result.success) {
            throw new Error(result.message || 'Could not add that item right now.');
        }

        document.querySelectorAll('[data-cart-count]').forEach(function (count) {
            count.textContent = result.cartCount;
        });
        var toastElement = document.getElementById('app-toast');
        toastElement.querySelector('[data-toast-message]').textContent = result.message;
        bootstrap.Toast.getOrCreateInstance(toastElement).show();
        button.textContent = 'Added!';
        window.setTimeout(function () { button.textContent = previousText; }, 1200);
    } catch (error) {
        var toastElement = document.getElementById('app-toast');
        toastElement.querySelector('[data-toast-message]').textContent = error.message;
        bootstrap.Toast.getOrCreateInstance(toastElement).show();
    } finally {
        button.disabled = false;
    }
});
