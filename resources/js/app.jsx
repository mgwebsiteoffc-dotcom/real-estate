import './bootstrap';
import '../css/app.css';
import { createRoot } from 'react-dom/client';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';

// Make route helper globally available
window.route = function() {
    const currentUrl = window.location.pathname;
    return {
        current: (name) => {
            if (name.endsWith('.*')) {
                const baseName = name.slice(0, -2);
                return currentUrl.startsWith(`/${baseName}`);
            }
            return currentUrl === `/${name}` || currentUrl === name;
        }
    };
};

const appName = import.meta.env.VITE_APP_NAME || 'Real Estate CRM';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.jsx`,
        import.meta.glob('./Pages/**/*.jsx')
    ),
    setup({ el, App, props }) {
        const root = createRoot(el);
        root.render(<App {...props} />);
    },
    progress: {
        color: '#2563eb',
        showSpinner: true,
    },
});
