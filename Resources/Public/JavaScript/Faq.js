document.addEventListener('DOMContentLoaded', function () {
    injectClearButton();
    handleClearButtonClick();
    toggleClearButtonVisibility();
    handleThumbClicks();
});

function injectClearButton() {
    const searchInput = document.querySelector('input[name="tx_ckfaq_pi1[term]"]');
    if (searchInput) {
        const wrapper = searchInput.parentElement;
        wrapper.style.position = 'relative';

        const clearBtn = document.createElement('i');
        clearBtn.classList.add('bi', 'bi-x-lg', 'clear-btn');
        clearBtn.title = 'Clear';
        clearBtn.style.display = searchInput.value ? 'block' : 'none';

        wrapper.appendChild(clearBtn);
    }
}

function handleClearButtonClick() {
    document.body.addEventListener('click', function (e) {
        if (e.target.classList.contains('clear-btn')) {
            const input = document.querySelector('input[name="tx_ckfaq_pi1[term]"]');
            if (input) {
                input.value = '';
                e.target.style.display = 'none';

                const form = input.closest('form');
                if (form) form.submit();
            }
        }
    });
}

function toggleClearButtonVisibility() {
    const input = document.querySelector('input[name="tx_ckfaq_pi1[term]"]');
    const clearBtn = document.querySelector('.clear-btn');

    if (input && clearBtn) {
        input.addEventListener('input', () => {
            clearBtn.style.display = input.value ? 'block' : 'none';
        });
    }
}

function handleThumbClicks() {
    document.querySelectorAll('.faq-feedback i').forEach(icon => {
        icon.addEventListener('click', () => {
            const container = icon.closest('.faq-feedback');

            // Prevent re-click
            if (container.classList.contains('rated')) return;

            const faqId = container.closest('.accordion-collapse').id.split('-')[1];
            const act = icon.classList.contains('bi-hand-thumbs-up') ? 'i' : 'd';
            const url = '/?type=11556633';

            const formData = new URLSearchParams();
            formData.append('act', act);
            formData.append('faq', faqId);

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: formData.toString()
            })
                .then(response => response.json())
                .then(data => {
                    // Mark the icon as active
                    icon.classList.add('active');

                    // Show confirmation message
                    container.querySelector('.was-helpful')?.classList.add('d-none');
                    if (act === 'i') {
                        container.querySelector('.helpful')?.classList.remove('d-none');
                        container.querySelector('.bi-hand-thumbs-down')?.classList.add('d-none');
                    } else {
                        container.querySelector('.not-helpful')?.classList.remove('d-none');
                        container.querySelector('.bi-hand-thumbs-up')?.classList.add('d-none');
                    }

                    // Mark container as rated to prevent further clicks
                    container.classList.add('rated');
                })
                .catch(error => {
                    console.error('Rating AJAX failed:', error);
                });
        });
    });
}