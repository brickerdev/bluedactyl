import { Suspense, lazy } from 'react';
import { createBrowserRouter } from 'react-router-dom';

import AccountOverviewContainer from '@/components/dashboard/AccountOverviewContainer';
import DashboardContainer from '@/components/dashboard/DashboardContainer';
import AuthenticatedRoute from '@/components/elements/AuthenticatedRoute';
import { NotFound } from '@/components/elements/ScreenBlock';
import Spinner from '@/components/elements/Spinner';

import { ServerContext } from '@/state/server';

import AccountApiContainer from './components/dashboard/AccountApiContainer';
import AccountSSHContainer from './components/dashboard/ssh/AccountSSHContainer';

const DashboardRouter = lazy(() => import('@/routers/DashboardRouter'));
const ServerRouter = lazy(() => import('@/routers/ServerRouter'));
const AuthenticationRouter = lazy(() => import('@/routers/AuthenticationRouter'));

export const router = createBrowserRouter([
    {
        path: '/auth/*',
        element: (
            <Suspense fallback={<Spinner centered size={Spinner.Size.LARGE} />}>
                <AuthenticationRouter />
            </Suspense>
        ),
    },
    {
        path: '/server/:id/*',
        element: (
            <AuthenticatedRoute>
                <Suspense fallback={<Spinner centered size={Spinner.Size.LARGE} />}>
                    <ServerContext.Provider>
                        <ServerRouter />
                    </ServerContext.Provider>
                </Suspense>
            </AuthenticatedRoute>
        ),
    },
    {
        path: '/*',
        element: (
            <AuthenticatedRoute>
                <DashboardRouter />
            </AuthenticatedRoute>
        ),
        children: [
            { index: true, element: <DashboardContainer /> },
            { path: 'account', element: <AccountOverviewContainer /> },
            { path: 'account/api', element: <AccountApiContainer /> },
            { path: 'account/ssh', element: <AccountSSHContainer /> },
        ],
    },
    {
        path: '*',
        element: <NotFound />,
    },
]);
