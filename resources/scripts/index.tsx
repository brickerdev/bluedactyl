import { createRoot } from 'react-dom/client';
import { StrictMode } from 'react';
import App from '@/components/App';

const container = document.getElementById('app');
if (container) {
    const root = createRoot(container);
    root.render(<StrictMode><App /></StrictMode>);
} else {
    console.error('Failed to find the root element');
}
