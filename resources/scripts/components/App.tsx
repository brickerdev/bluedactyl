import '@/assets/tailwind.css';
import { router } from '@/routes';
import '@preact/signals-react';
import { StoreProvider } from 'easy-peasy';
import { RouterProvider } from 'react-router-dom';

import Spinner from '@/components/elements/Spinner';
import { ThemeProvider } from '@/components/ui/theme-provider';
import { TooltipProvider } from '@/components/ui/tooltip';

import { store } from '@/state';
import { SiteSettings } from '@/state/settings';

import BluedactylProvider from './BluedactylProvider';

interface ExtendedWindow extends Window {
    SiteConfiguration?: SiteSettings & {
        captcha: {
            enabled: boolean;
            provider: string;
            siteKey: string;
            scriptIncludes: string[];
        };
    };
    PterodactylUser?: {
        uuid: string;
        username: string;
        email: string;

        root_admin: boolean;
        use_totp: boolean;
        language: string;
        updated_at: string;
        created_at: string;
    };
}

// Define routes using createBrowserRouter

const App = () => {
    const { PterodactylUser, SiteConfiguration } = window as ExtendedWindow;
    if (PterodactylUser && !store.getState().user.data) {
        store.getActions().user.setUserData({
            uuid: PterodactylUser.uuid,
            username: PterodactylUser.username,
            email: PterodactylUser.email,
            language: PterodactylUser.language,
            rootAdmin: PterodactylUser.root_admin,
            useTotp: PterodactylUser.use_totp,
            createdAt: new Date(PterodactylUser.created_at),
            updatedAt: new Date(PterodactylUser.updated_at),
        });
    }

    if (!store.getState().settings.data) {
        store.getActions().settings.setSettings(SiteConfiguration!);
    }

    return (
        <StoreProvider store={store}>
            <ThemeProvider defaultTheme='dark'>
                <TooltipProvider>
                    <BluedactylProvider>
                        <div
                            data-blue-routerwrap=''
                            className='relative w-full min-h-screen flex flex-row overflow-hidden bg-background text-foreground'
                        >
                            <Spinner.Suspense>
                                <RouterProvider router={router} />
                            </Spinner.Suspense>
                        </div>
                    </BluedactylProvider>
                </TooltipProvider>
            </ThemeProvider>
        </StoreProvider>
    );
};

export default App;
