document.addEventListener('alpine:init', () => {
    Alpine.data('toggleDebugMode', () => ({
        on: false,
        async toggleDebugMode() {
            this.on = !this.on;
            try {
                const response = await fetch(ajaxurl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        action: 'qr_toggle_debug_mode',
                        qr_debug_mode: this.on ? 1 : 0,
                        _ajax_nonce: qr_toggle_debug_mode.nonce,
                    })
                });
                const result = await response.json();
                if (result.success) {
                    console.log('Debug mode updated successfully');
                } else {
                    console.error('Error updating debug mode');
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }
    }));
});
