import type { FormikHelpers } from 'formik';
import { Formik } from 'formik';
import { Link } from 'react-router-dom';
import { object, string } from 'yup';

import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Separator } from '@/components/ui/separator';
import Captcha, { getCaptchaResponse } from '@/components/elements/Captcha';

import CaptchaManager from '@/lib/captcha';

import { httpErrorToHuman } from '@/api/http';
import http from '@/api/http';

import useFlash from '@/plugins/useFlash';

interface Values {
    email: string;
}

const ForgotPasswordContainer = () => {
    const { clearFlashes, addFlash } = useFlash();

    const handleSubmission = ({ email }: Values, { setSubmitting, resetForm }: FormikHelpers<Values>) => {
        clearFlashes();

        // Get captcha response if enabled
        const captchaResponse = getCaptchaResponse();

        let requestData: any = { email };
        if (CaptchaManager.isEnabled() && captchaResponse) {
            const fieldName = CaptchaManager.getProviderInstance().getResponseFieldName();
            if (fieldName) {
                requestData = { ...requestData, [fieldName]: captchaResponse };
            }
        }

        http.post('/auth/password', requestData)
            .then((response) => {
                resetForm();
                addFlash({ type: 'success', title: 'Success', message: response.data.status || 'Email sent!' });
            })
            .catch((error) => {
                console.error(error);
                addFlash({ type: 'error', title: 'Error', message: httpErrorToHuman(error) });
            })
            .finally(() => {
                setSubmitting(false);
            });
    };

    return (
        <div className="w-full max-w-md mx-auto flex flex-col gap-6 px-6 py-8">
            <Card className="w-full max-w-md mx-auto rounded-2xl shadow-2xl bg-accent-foreground">
                <CardHeader className="space-y-1">
                    <CardTitle className="text-3xl font-extrabold text-center text-[--color-white]">
                        Reset Password
                    </CardTitle>
                    <CardDescription className="text-center text-[--color-gray-300]">
                        We'll send you an email with a link to reset your password.
                    </CardDescription>
                </CardHeader>
                <CardContent>
                    <Formik
                        onSubmit={handleSubmission}
                        initialValues={{ email: '' }}
                        validationSchema={object().shape({
                            email: string().email('Enter a valid email address.').required('Email is required.'),
                        })}
                    >
                        {({ values, handleChange, handleBlur, handleSubmit, isSubmitting, errors, touched }) => (
                            <form onSubmit={handleSubmit} className="space-y-6">
                                <div className="space-y-2">
                                    <Label htmlFor="email" className="text-[--color-white]">
                                        Email
                                    </Label>
                                    <Input
                                        id="email"
                                        name="email"
                                        type="email"
                                        autoComplete="email"
                                        value={values.email}
                                        onChange={handleChange}
                                        onBlur={handleBlur}
                                        placeholder="Enter your email"
                                        className={`h-10 rounded-lg bg-[--color-background]/80 text-[--color-white] placeholder:text-[--color-gray-400] border ${errors.email && touched.email ? 'border-[--color-red-500]' : 'border-[--color-gray-500]'} focus-visible:ring-2 focus-visible:ring-[--color-brand] focus-visible:border-[--color-brand] transition-colors`}
                                    />
                                    {errors.email && touched.email && (
                                        <p className="text-sm text-[--color-red-500]">{errors.email}</p>
                                    )}
                                </div>

                                <Captcha
                                    onError={(error) => {
                                        console.error('Captcha error:', error);
                                        addFlash({
                                            type: 'error',
                                            title: 'Error',
                                            message: 'Captcha verification failed. Please try again.',
                                        });
                                    }}
                                />

                                <Button
                                    type="submit"
                                    className="w-full rounded-full"
                                    size="icon"
                                    variant="secondary"
                                    disabled={isSubmitting}
                                >
                                    {isSubmitting ? 'Sending...' : 'Send Email'}
                                </Button>
                            </form>
                        )}
                    </Formik>
                </CardContent>

                <CardFooter>
                    <Link
                        to="/"
                        className="w-full"
                    >
                        <Button
                            className="w-full rounded-full mt-2"
                            size="default"
                        >
                            Back to Home
                        </Button>
                    </Link>
                </CardFooter>
            </Card>
        </div>
    );
};

export default ForgotPasswordContainer;