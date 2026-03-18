import { Route, Routes } from 'react-router-dom';

import { GravityStarsBackground } from '@/components/animate-ui/gravity-stars';
import ForgotPasswordContainer from '@/components/auth/ForgotPasswordContainer';
import LoginCheckpointContainer from '@/components/auth/LoginCheckpointContainer';
import LoginContainer from '@/components/auth/LoginContainer';
import ResetPasswordContainer from '@/components/auth/ResetPasswordContainer';
import { NotFound } from '@/components/elements/ScreenBlock';

const AuthenticationRouter = () => {
    return (
        <div className='relative w-full h-screen overflow-hidden bg-background'>
            <div className='fixed inset-0 z-0 pointer-events-none'>
                <GravityStarsBackground className='w-full h-full opacity-60' />
            </div>

            <div className='relative z-10 w-full h-full flex flex-col items-center justify-center overflow-y-auto p-4 sm:p-8 pointer-events-auto'>
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
