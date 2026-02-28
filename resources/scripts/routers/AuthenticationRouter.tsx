import { GalleryVerticalEnd } from 'lucide-react';
import { Link, Route, Routes } from 'react-router-dom';

import { GravityStarsBackground } from '@/components/animate-ui/components/backgrounds/gravity-stars';
import ForgotPasswordContainer from '@/components/auth/ForgotPasswordContainer';
import LoginCheckpointContainer from '@/components/auth/LoginCheckpointContainer';
import LoginContainer from '@/components/auth/LoginContainer';
import ResetPasswordContainer from '@/components/auth/ResetPasswordContainer';
import { NotFound } from '@/components/elements/ScreenBlock';

const AuthenticationRouter = () => {
    return (
        <div className='relative w-full h-screen overflow-hidden'>
            <GravityStarsBackground className='absolute inset-0 -z-10 pointer-events-none opacity-70' />

            <div className='pointer-events-none absolute left-1/2 top-6 -translate-x-1/2 flex items-center gap-3 text-sm font-medium sm:text-base'>
                <Link
                    to='/'
                    className='pointer-events-auto flex items-center gap-3'
                >
                    <div className='bg-primary text-primary-foreground flex h-8 w-8 items-center justify-center rounded-md'>
                        <GalleryVerticalEnd className='h-5 w-5' />
                    </div>
                    Bluedactyl
                </Link>
            </div>

            <div className='mt-20'>
                <Routes>
                    <Route path='login' element={<LoginContainer />} />
                    <Route path='login/checkpoint/*' element={<LoginCheckpointContainer />} />
                    <Route path='password' element={<ForgotPasswordContainer />} />
                    <Route path='password/reset/:token' element={<ResetPasswordContainer />} />
                    <Route path='*' element={<NotFound />} />
                </Routes>
            </div>
        </div>
    );
};

export default AuthenticationRouter;
