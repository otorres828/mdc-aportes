document.addEventListener('alpine:init', () => {
    Alpine.data('mdcDateInput', () => ({
        picker: null,

        init() {
            this.$nextTick(() => {
                if (!this.$el.isConnected) return;
                const model = this.$el.dataset.model;
                this.picker = flatpickr(this.$refs.input, {
                    locale: 'es',
                    dateFormat: 'd/m/Y',
                    defaultDate: this.$wire[model] ? new Date(this.$wire[model] + 'T12:00:00') : null,
                    minDate: this.$el.dataset.min ? new Date(this.$el.dataset.min + 'T12:00:00') : null,
                    maxDate: this.$el.dataset.max ? new Date(this.$el.dataset.max + 'T12:00:00') : null,
                    disableMobile: true,
                    onChange: dates => {
                        this.$wire[model] = dates.length ? flatpickr.formatDate(dates[0], 'Y-m-d') : '';
                    },
                });
                this.$watch('$wire.' + model, value => {
                    this.picker?.setDate(value ? new Date(value + 'T12:00:00') : null, false);
                });
            });
        },

        destroy() {
            this.picker?.destroy();
            this.picker = null;
        },
    }));

    Alpine.data('mdcShell', () => ({
        toast: '',
        alertMessage: '',
        alertVisible: false,
        timer: null,
        offline: !navigator.onLine,
        listeners: [],

        init() {
            const online = () => { this.offline = false; };
            const offline = () => { this.offline = true; };
            const toast = event => {
                this.toast = event.detail;
                clearTimeout(this.timer);
                this.timer = setTimeout(() => { this.toast = ''; }, 2800);
            };
            const ageAlert = event => {
                this.alertMessage = event.detail.message;
                this.alertVisible = true;
            };
            this.listeners = [['online', online], ['offline', offline], ['mdc-toast', toast], ['mdc-age-alert', ageAlert]];
            this.listeners.forEach(([event, handler]) => window.addEventListener(event, handler));
        },

        closeAlert() {
            this.alertVisible = false;
        },

        destroy() {
            clearTimeout(this.timer);
            this.listeners.forEach(([event, handler]) => window.removeEventListener(event, handler));
        },
    }));
});

document.addEventListener('livewire:navigated', () => {
    window.scrollTo({ top: 0, behavior: 'instant' });
    resetLoadingButtons();
});

const resetLoadingButtons = () => {
    document.querySelectorAll('button[wire\\:loading\\.attr="disabled"]').forEach(button => {
        button.removeAttribute('disabled');
    });
};

window.addEventListener('pageshow', event => {
    if (event.persisted) resetLoadingButtons();
});

async function copyText(value, message = 'Dato copiado') {
    try {
        if (navigator.clipboard && window.isSecureContext) {
            await navigator.clipboard.writeText(value);
        } else {
            const field = document.createElement('textarea');
            field.value = value;
            field.style.position = 'fixed';
            field.style.opacity = '0';
            document.body.append(field);
            field.select();
            const copied = document.execCommand('copy');
            field.remove();
            if (!copied) throw new Error('copy');
        }
        window.dispatchEvent(new CustomEvent('mdc-toast', { detail: message }));
    } catch {
        window.dispatchEvent(new CustomEvent('mdc-toast', { detail: 'No se pudo copiar. Inténtalo nuevamente.' }));
    }
}
document.addEventListener('click', event => {
    const button = event.target.closest('[data-copy]');
    if (button) copyText(button.dataset.copy, button.dataset.copyMessage || 'Dato copiado');
});

// La app conserva el desplazamiento; evita pinch zoom y doble toque en móvil.
document.addEventListener('touchmove', event => {
    if (event.touches.length > 1) event.preventDefault();
}, { passive: false });
document.addEventListener('gesturestart', event => event.preventDefault(), { passive: false });
// Mantiene selección y menú de edición únicamente dentro de los campos.
document.addEventListener('contextmenu', event => {
    if (!event.target.closest('input, textarea, [contenteditable="true"]')) event.preventDefault();
});
document.addEventListener('selectstart', event => {
    if (!event.target.closest('input, textarea, [contenteditable="true"]')) event.preventDefault();
});
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                preventDefault();
                window.dispatchEvent(new CustomEvent('mdc-toast', { detail: 'La sesión expiró. Recarga la página para continuar.' }));
            } else if (status === 0 || status >= 500) {
                preventDefault();
                window.dispatchEvent(new CustomEvent('mdc-toast', { detail: 'No pudimos conectar. Tus datos siguen aquí; inténtalo de nuevo.' }));
            }
        });
    });
});
if ('serviceWorker' in navigator && window.isSecureContext) {
    navigator.serviceWorker.register('/sw.js').catch(() => {});
}
