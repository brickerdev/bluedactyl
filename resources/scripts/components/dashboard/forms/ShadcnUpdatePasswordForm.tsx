import { Actions, State, useStoreActions, useStoreState } from 'easy-peasy';
import { Form, Formik, FormikHelpers } from 'formik';
import { Fragment } from 'react';
import * as Yup from 'yup';

import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import updateAccountPassword from '@/api/account/updateAccountPassword';
import { httpErrorToHuman } from '@/api/http';

import { ApplicationStore } from '@/state';

interface Values {
    current: string;
    password: string;
    confirmPassword: string;
}

const schema = Yup.object().shape({
    current: Yup.string().min(1).required('You must provide your current account password.'),
    password: Yup.string().min(8).required(),
    confirmPassword: Yup.string().test(
        'password',
        'Password confirmation does not match the password you entered.',
        function (value) {
            return value === this.parent.password;
        },
    ),
});

const ShadcnUpdatePasswordForm = () => {
    const user = useStoreState((state: State<ApplicationStore>) => state.user.data);
    const { clearFlashes, addFlash } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    if (!user) {
        return null;
    }

    const submit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes('account:password');
        updateAccountPassword({ ...values })
            .then(() => {
                // @ts-expect-error this is valid
                window.location = '/auth/login';
            })
            .catch((error) =>
                addFlash({
                    key: 'account:password',
                    type: 'error',
                    title: 'Error',
                    message: httpErrorToHuman(error),
                }),
            )
            .then(() => setSubmitting(false));
    };

    return (
        <Formik
            onSubmit={submit}
            validationSchema={schema}
            initialValues={{ current: '', password: '', confirmPassword: '' }}
        >
            {({ isSubmitting, isValid, values, handleChange, handleBlur, errors, touched }) => (
                <Fragment>
                    <SpinnerOverlay size={'large'} visible={isSubmitting} />
                    <Form className='grid grid-cols-1 gap-4'>
                        <input
                            type='text'
                            name='username'
                            value={user.email}
                            readOnly
                            className='hidden'
                            autoComplete='username'
                        />
                        <div className='space-y-2'>
                            <Label htmlFor='current_password'>Current Password</Label>
                            <Input
                                id='current_password'
                                name='current'
                                type='password'
                                value={values.current}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                autoComplete='current-password'
                            />
                            {errors.current && touched.current && (
                                <p className='text-xs text-destructive'>{errors.current}</p>
                            )}
                        </div>
                        <div className='space-y-2'>
                            <Label htmlFor='new_password'>New Password</Label>
                            <Input
                                id='new_password'
                                name='password'
                                type='password'
                                value={values.password}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                autoComplete='new-password'
                            />
                            <p className='text-xs text-muted-foreground'>
                                Your new password should be at least 8 characters in length and unique to this website.
                            </p>
                            {errors.password && touched.password && (
                                <p className='text-xs text-destructive'>{errors.password}</p>
                            )}
                        </div>
                        <div className='space-y-2'>
                            <Label htmlFor='confirm_new_password'>Confirm New Password</Label>
                            <Input
                                id='confirm_new_password'
                                name='confirmPassword'
                                type='password'
                                value={values.confirmPassword}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                autoComplete='new-password'
                            />
                            {errors.confirmPassword && touched.confirmPassword && (
                                <p className='text-xs text-destructive'>{errors.confirmPassword}</p>
                            )}
                        </div>
                        <div className='mt-2'>
                            <Button type='submit' disabled={isSubmitting || !isValid}>
                                {isSubmitting ? 'Updating...' : 'Update Password'}
                            </Button>
                        </div>
                    </Form>
                </Fragment>
            )}
        </Formik>
    );
};

export default ShadcnUpdatePasswordForm;
