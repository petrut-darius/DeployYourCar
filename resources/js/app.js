import '../css/app.css';

import { createInertiaApp} from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { InertiaProgress } from '@inertiajs/progress';
import { InertiaToast, toastStore } from '@laravel-inertia-toast/vue'

InertiaProgress.init({
  color: '#29d',
  showSpinner: false,
});

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(InertiaToast, {
                duration: 3500,
                position: 'top-right',
                maxVisible: 5,
            });

        const initialToasts = props.initialPage.props.flash?.toasts ?? [];
        initialToasts.forEach(toast => toastStore.addToast(toast));

        return app.mount(el);;
    },
    progress: {
        color: '#4B5563',
    },
});
