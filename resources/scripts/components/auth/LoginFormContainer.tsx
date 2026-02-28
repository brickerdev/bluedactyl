import { Form } from 'formik';
import { forwardRef } from 'react';

import FlashMessageRender from '@/components/FlashMessageRender';

type Props = React.DetailedHTMLProps<React.FormHTMLAttributes<HTMLFormElement>, HTMLFormElement> & {
    title?: string;
};

const LoginFormContainer = forwardRef<HTMLFormElement, Props>(({ title, ...props }, ref) => (
    <div className='w-full max-w-md mx-auto px-6 sm:px-8 py-8'>
        {title && <h2 className={`text-2xl sm:text-3xl text-center text-zinc-100 font-medium py-2`}>{title}</h2>}
        <FlashMessageRender />
        <Form {...props} ref={ref}>
            <div className={`w-full`}>
                <div className={`w-full`}>{props.children}</div>
            </div>
        </Form>
    </div>
));

LoginFormContainer.displayName = 'LoginFormContainer';

export default LoginFormContainer;
