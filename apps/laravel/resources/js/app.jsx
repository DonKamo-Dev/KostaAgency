import './bootstrap';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

const appElement = document.getElementById('app');
if (appElement && (appElement.dataset.page || appElement.hasAttribute('data-page'))) {
    createInertiaApp({
        title: (title) => `${title} - Kamo`,
        resolve: (name) => {
            const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true });
            return pages[`./Pages/${name}.jsx`];
        },
        setup({ el, App, props }) {
            createRoot(el).render(<App {...props} />);
        },
    });
}

