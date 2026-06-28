import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
document.addEventListener('alpine:init', () => {
    Alpine.store('modal', {
        active: null,
        payload: null,
        open(name, payload = null) {
            this.active = name
            this.payload = payload
        },
        close() {
            this.active = null
            this.payload = null
        },
        isOpen(name) {
            return this.active === name
        }
    })
})

Alpine.start();