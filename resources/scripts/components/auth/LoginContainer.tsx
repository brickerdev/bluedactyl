import type { FormikHelpers } from 'formik';
import { Formik } from 'formik';
import { useEffect } from 'react';
import { Link, useNavigate } from 'react-router-dom';
import { object, string } from 'yup';

import { ShineBorder } from '@/components/ui/shine-border';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { Spinner } from '@/components/ui/spinner';
import {
    Field,
    FieldGroup,
    FieldLabel,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import Captcha, { getCaptchaResponse } from '@/components/elements/Captcha';
import CaptchaManager from '@/lib/captcha';
import login from '@/api/auth/login';
import { GalleryVerticalEnd } from 'lucide-react';
import useFlash from '@/plugins/useFlash';

interface Values {
    user: string;
    password: string;
}

function LoginContainer() {
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const navigate = useNavigate();

    useEffect(() => {
        clearFlashes();
    }, []);

    const onSubmit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();

        // Get captcha response if enabled
        let loginData: any = values;
        if (CaptchaManager.isEnabled()) {
            const captchaResponse = getCaptchaResponse();
            const fieldName = CaptchaManager.getProviderInstance().getResponseFieldName();

            console.log('Captcha enabled, response:', captchaResponse, 'fieldName:', fieldName);

            if (fieldName) {
                if (captchaResponse) {
                    loginData = { ...values, [fieldName]: captchaResponse };
                    console.log('Adding captcha to login data:', loginData);
                } else {
                    // Captcha is enabled but no response - show error
                    console.error('Captcha enabled but no response available');
                    clearAndAddHttpError({ error: new Error('Please complete the captcha verification.') });
                    setSubmitting(false);
                    return;
                }
            }
        } else {
            console.log('Captcha not enabled');
        }

        login(loginData)
            .then((response) => {
                if (response.complete) {
                    window.location.href = response.intended || '/';
                    return;
                }
                navigate('/auth/login/checkpoint', { state: { token: response.confirmationToken } });
            })
            .catch((error: any) => {
                setSubmitting(false);

                if (error.code === 'InvalidCredentials') {
                    clearAndAddHttpError({ error: new Error('Invalid username or password. Please try again.') });
                } else if (error.code === 'DisplayException') {
                    clearAndAddHttpError({ error: new Error(error.detail || error.message) });
                } else {
                    clearAndAddHttpError({ error });
                }
            });
    };

    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={{ user: '', password: '' }}
            validationSchema={object().shape({
                user: string().required('A username or email must be provided.'),
                password: string().required('Please enter your account password.'),
            })}
        >
            {({ isSubmitting, handleSubmit, handleChange, values }) => (
                <div className="w-full max-w-md mx-auto flex flex-col gap-6 py-8 items-center">
                    <Link to="/" className="flex items-center gap-3 self-center text-sm sm:text-base font-medium">
                        <div className="bg-primary text-primary-foreground flex h-8 w-8 items-center justify-center rounded-md">
                            <GalleryVerticalEnd className="h-5 w-5" />
                        </div>
                        Bluedactyl
                    </Link>

                    <Card className="relative w-full max-w-87.5 overflow-hidden">
                        <ShineBorder shineColor={["#A07CFE", "#FE8FB5", "#FFBE7B"]} />
                        <CardHeader className="text-left pt-4">
                            <CardTitle className="text-2xl sm:text-3xl font-semibold">Login</CardTitle>
                            <CardDescription className="text-sm text-zinc-400">
                                Enter your credentials to continue
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="p-6">
                            <form onSubmit={handleSubmit} className="space-y-4">
                                <FieldGroup>
                                    {/* Username/Email Field */}
                                    <Field>
                                        <FieldLabel htmlFor="user">Username or Email</FieldLabel>
                                        <Input
                                            id="user"
                                            name="user"
                                            type="text"
                                            autoComplete="username"
                                            placeholder="Enter your username or email"
                                            value={values.user}
                                            onChange={handleChange}
                                            disabled={isSubmitting}
                                            required
                                        />
                                    </Field>

                                    {/* Password Field with Forgot Password Link */}
                                    <Field>
                                        <div className="flex items-center justify-between">
                                            <FieldLabel htmlFor="password">Password</FieldLabel>
                                            <Link
                                                to="/auth/password"
                                                className="text-sm text-zinc-500 tracking-wide no-underline hover:text-zinc-600"
                                            >
                                                Forgot Password?
                                            </Link>
                                        </div>
                                        <Input
                                            id="password"
                                            name="password"
                                            type="password"
                                            autoComplete="current-password"
                                            placeholder="Enter your password"
                                            value={values.password}
                                            onChange={handleChange}
                                            disabled={isSubmitting}
                                            required
                                        />
                                    </Field>

                                    {/* Captcha Field */}
                                    <Field>
                                        <Captcha
                                            className="mt-3"
                                            onError={(error) => {
                                                console.error('Captcha error:', error);
                                                clearAndAddHttpError({
                                                    error: new Error('Captcha verification failed. Please try again.'),
                                                });
                                            }}
                                        />
                                    </Field>

                                    {/* Submit Button */}
                                    <Field>
                                        <Button
                                            type="submit"
                                            className="w-full mt-2"
                                            size="default"
                                            disabled={isSubmitting}
                                        >
                                            {isSubmitting ? (
                                                <Spinner data-icon="inline-start" />
                                            ) : (
                                                'Login'
                                            )}
                                        </Button>
                                    </Field>
                                </FieldGroup>
                            </form>
                        </CardContent>
                    </Card>
                </div>
            )}
        </Formik>
    );
}

export default LoginContainer;