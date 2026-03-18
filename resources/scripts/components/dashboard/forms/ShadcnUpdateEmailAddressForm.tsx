import { Actions, State, useStoreActions, useStoreState } from 'easy-peasy';
import { Form, Formik, FormikHelpers } from 'formik';
import { Fragment } from 'react';
import * as Yup from 'yup';

import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import { httpErrorToHuman } from '@/api/http';

import { ApplicationStore } from '@/state';

interface Values {
    email: string;
    password: string;
}

const schema = Yup.object().shape({
    email: Yup.string().email().required(),
    password: Yup.string().required('You must provide your current account password.'),
});

const ShadcnUpdateEmailAddressForm = () => {
    const user = useStoreState((state: State<ApplicationStore>) => state.user.data);
    const updateEmail = useStoreActions((state: Actions<ApplicationStore>) => state.user.updateUserEmail);

    const { clearFlashes, addFlash } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    const submit = (values: Values, { resetForm, setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes('account:email');

        updateEmail({ ...values })
            .then(() =>
                addFlash({
                    type: 'success',
                    key: 'account:email',
                    message: 'Your primary email has been updated.',
                }),
            )
            .catch((error) =>
                addFlash({
                    type: 'error',
                    key: 'account:email',
                    title: 'Error',
                    message: httpErrorToHuman(error),
                }),
            )
            .then(() => {
                resetForm();
                setSubmitting(false);
            });
    };

    return (
        <Formik onSubmit={submit} validationSchema={schema} initialValues={{ email: user!.email, password: '' }}>
            {({ isSubmitting, isValid, values, handleChange, handleBlur, errors, touched }) => (
                <Fragment>
                    <SpinnerOverlay size={'large'} visible={isSubmitting} />
                    <Form className='grid grid-cols-1 gap-4'>
                        <div className='space-y-2'>
                            <Label htmlFor='email'>Email Address</Label>
                            <Input
                                id='email'
                                name='email'
                                type='email'
                                value={values.email}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                autoComplete='email'
                            />
                            {errors.email && touched.email && (
                                <p className='text-xs text-destructive'>{errors.email}</p>
                            )}
                        </div>
                        <div className='space-y-2'>
                            <Label htmlFor='password'>Current Password</Label>
                            <Input
                                id='password'
                                name='password'
                                type='password'
                                value={values.password}
                                onChange={handleChange}
                                onBlur={handleBlur}
                                autoComplete='current-password'
                            />
                            {errors.password && touched.password && (
                                <p className='text-xs text-destructive'>{errors.password}</p>
                            )}
                        </div>
                        <div className='mt-2'>
                            <Button type='submit' disabled={isSubmitting || !isValid}>
                                Update Email
                            </Button>
                        </div>
                    </Form>
                </Fragment>
            )}
        </Formik>
    );
};

export default ShadcnUpdateEmailAddressForm;
