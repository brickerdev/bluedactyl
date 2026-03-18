import { useLocation } from 'react-router-dom';

import MessageBox from '@/components/MessageBox';
import ShadcnConfigureTwoFactorForm from '@/components/dashboard/forms/ShadcnConfigureTwoFactorForm';
import ShadcnUpdateEmailAddressForm from '@/components/dashboard/forms/ShadcnUpdateEmailAddressForm';
import ShadcnUpdatePasswordForm from '@/components/dashboard/forms/ShadcnUpdatePasswordForm';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';

const AccountOverviewContainer = () => {
    const { state } = useLocation();

    return (
        <div className='container mx-auto p-6'>
            <div className='flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4'>
                <div>
                    <h1 className='text-3xl font-bold tracking-tight'>Your Settings</h1>
                    <p className='text-muted-foreground'>Manage your account security and preferences.</p>
                </div>
            </div>

            <div className='flex flex-col w-full gap-6'>
                {state?.twoFactorRedirect && (
                    <MessageBox title={'2-Factor Required'} type={'error'}>
                        Your account must have two-factor authentication enabled in order to continue.
                    </MessageBox>
                )}

                <div className='grid grid-cols-1 lg:grid-cols-2 gap-6'>
                    <Card>
                        <CardHeader>
                            <CardTitle>Account Email</CardTitle>
                            <CardDescription>Update your account's email address.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ShadcnUpdateEmailAddressForm />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Account Password</CardTitle>
                            <CardDescription>Change your account password to keep it secure.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ShadcnUpdatePasswordForm />
                        </CardContent>
                    </Card>

                    <Card>
                        <CardHeader>
                            <CardTitle>Multi-Factor Authentication</CardTitle>
                            <CardDescription>Add an extra layer of security to your account.</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <ShadcnConfigureTwoFactorForm />
                        </CardContent>
                    </Card>

                    <Card className='bg-muted/50'>
                        <CardHeader>
                            <CardTitle>Panel Version</CardTitle>
                            <CardDescription>
                                This is useful to provide Blue staff if you run into an unexpected issue.
                            </CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div className='flex flex-col gap-2'>
                                <pre className='font-mono text-sm px-2 py-1 inline-block rounded-sm w-fit bg-background border border-border'>
                                    Version: {import.meta.env.VITE_BLUEDACTYL_VERSION} -{' '}
                                    {import.meta.env.VITE_BRANCH_NAME}
                                </pre>
                                <pre className='font-mono text-sm px-2 py-1 inline-block rounded-sm w-fit bg-background border border-border'>
                                    Commit : {import.meta.env.VITE_COMMIT_HASH.slice(0, 7)}
                                </pre>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    );
};

export default AccountOverviewContainer;
