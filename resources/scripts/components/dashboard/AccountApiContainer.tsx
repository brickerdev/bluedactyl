import { Eye, EyeSlash, Key, Plus, TrashBin } from '@gravity-ui/icons';
import { yupResolver } from '@hookform/resolvers/yup';
import { format } from 'date-fns';
import { Actions, useStoreActions } from 'easy-peasy';
import { useEffect, useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { object, string } from 'yup';

import FlashMessageRender from '@/components/FlashMessageRender';
import ApiKeyModal from '@/components/dashboard/ApiKeyModal';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

import createApiKey from '@/api/account/createApiKey';
import deleteApiKey from '@/api/account/deleteApiKey';
import getApiKeys, { ApiKey } from '@/api/account/getApiKeys';
import { httpErrorToHuman } from '@/api/http';

import { ApplicationStore } from '@/state';

import { useFlashKey } from '@/plugins/useFlash';

interface CreateValues {
    description: string;
    allowedIps: string;
}

function CreateApiForm({
    onSubmit,
}: {
    onSubmit: (values: CreateValues, helpers: { setSubmitting: (v: boolean) => void; resetForm: () => void }) => void;
}) {
    const schema = object().shape({
        description: string().required().min(4),
        allowedIps: string(),
    });

    const { control, handleSubmit, formState, reset } = useForm<CreateValues>({
        resolver: yupResolver(schema as any),
        defaultValues: { description: '', allowedIps: '' },
    });

    const submitting = formState.isSubmitting;

    return (
        <form
            id='create-api-form'
            className='space-y-4'
            onSubmit={handleSubmit((values) => onSubmit(values, { setSubmitting: () => {}, resetForm: () => reset() }))}
        >
            <SpinnerOverlay visible={submitting} />

            <div className='space-y-2'>
                <Label htmlFor='description'>Description</Label>
                <Controller
                    control={control}
                    name='description'
                    render={({ field }) => <Input {...field} id='description' className='w-full' />}
                />
                <p className='text-xs text-muted-foreground'>A description of this API key.</p>
            </div>

            <div className='space-y-2'>
                <Label htmlFor='allowedIps'>Allowed IPs</Label>
                <Controller
                    control={control}
                    name='allowedIps'
                    render={({ field }) => <Input {...field} id='allowedIps' className='w-full' />}
                />
                <p className='text-xs text-muted-foreground'>
                    Leave blank to allow any IP address to use this API key, otherwise provide each IP address on a new
                    line. Note: You can also use CIDR ranges here.
                </p>
            </div>

            <button type='submit' className='hidden' />
        </form>
    );
}

const AccountApiContainer = () => {
    const [deleteIdentifier, setDeleteIdentifier] = useState('');
    const [keys, setKeys] = useState<ApiKey[]>([]);
    const [loading, setLoading] = useState(true);
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [apiKey, setApiKey] = useState('');
    const [showKeys, setShowKeys] = useState<Record<string, boolean>>({});

    const { clearAndAddHttpError } = useFlashKey('api-keys');
    const { addError, clearFlashes } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);

    useEffect(() => {
        getApiKeys()
            .then((keys) => setKeys(keys))
            .then(() => setLoading(false))
            .catch((error) => clearAndAddHttpError(error));
    }, []);

    const doDeletion = (identifier: string) => {
        setLoading(true);
        clearAndAddHttpError();
        deleteApiKey(identifier)
            .then(() => setKeys((s) => [...(s || []).filter((key) => key.identifier !== identifier)]))
            .catch((error) => clearAndAddHttpError(error))
            .then(() => {
                setLoading(false);
                setDeleteIdentifier('');
            });
    };

    const submitCreate = (
        values: CreateValues,
        helpers: { setSubmitting: (v: boolean) => void; resetForm: () => void },
    ) => {
        clearFlashes('account:api-keys');
        createApiKey(values.description, values.allowedIps)
            .then(({ secretToken, ...key }) => {
                helpers.resetForm();
                helpers.setSubmitting(false);
                setApiKey(`${key.identifier}${secretToken}`);
                setKeys((s) => [...s!, key]);
                setShowCreateModal(false);
            })
            .catch((error) => {
                console.error(error);
                addError({ key: 'account:api-keys', message: httpErrorToHuman(error) });
                helpers.setSubmitting(false);
            });
    };

    const toggleKeyVisibility = (identifier: string) => {
        setShowKeys((prev) => ({
            ...prev,
            [identifier]: !prev[identifier],
        }));
    };

    return (
        <div className='container mx-auto p-6'>
            <FlashMessageRender byKey='account:api-keys' />
            <ApiKeyModal visible={apiKey.length > 0} onDismissed={() => setApiKey('')} apiKey={apiKey} />

            {/* Create API Key Modal */}
            <AlertDialog open={showCreateModal} onOpenChange={setShowCreateModal}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Create API Key</AlertDialogTitle>
                    </AlertDialogHeader>

                    <CreateApiForm onSubmit={submitCreate} />

                    <AlertDialogFooter>
                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                        <AlertDialogAction
                            onClick={(e) => {
                                e.preventDefault();
                                const form = document.getElementById('create-api-form') as HTMLFormElement;
                                if (form) {
                                    const submitButton = form.querySelector(
                                        'button[type="submit"]',
                                    ) as HTMLButtonElement;
                                    if (submitButton) submitButton.click();
                                }
                            }}
                        >
                            Create Key
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>

            <div className='flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4'>
                <div>
                    <h1 className='text-3xl font-bold tracking-tight'>API Keys</h1>
                    <p className='text-muted-foreground'>Manage your account API keys.</p>
                </div>
                <Button variant='default' onClick={() => setShowCreateModal(true)} className='flex items-center gap-2'>
                    <Plus width={20} height={20} fill='currentColor' />
                    Create API Key
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle className='text-lg'>Active API Keys</CardTitle>
                    <CardDescription>A list of all API keys associated with your account.</CardDescription>
                </CardHeader>
                <CardContent>
                    {loading && keys.length === 0 ? (
                        <div className='py-8 text-center text-muted-foreground'>Loading your API keys...</div>
                    ) : (
                        <>
                            <AlertDialog
                                open={!!deleteIdentifier}
                                onOpenChange={(open) => !open && setDeleteIdentifier('')}
                            >
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Delete API Key</AlertDialogTitle>
                                        <AlertDialogDescription>
                                            All requests using the{' '}
                                            <code className='font-mono px-2 py-1 bg-muted/20 rounded'>
                                                {deleteIdentifier}
                                            </code>{' '}
                                            key will be invalidated.
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                                        <AlertDialogAction onClick={() => doDeletion(deleteIdentifier)}>
                                            Delete Key
                                        </AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>

                            {keys.length === 0 ? (
                                <div className='text-center py-12 border rounded-lg'>
                                    <div className='w-16 h-16 mx-auto mb-4 rounded-full bg-muted flex items-center justify-center'>
                                        <Key
                                            width={22}
                                            height={22}
                                            className='text-muted-foreground'
                                            fill='currentColor'
                                        />
                                    </div>
                                    <h3 className='text-lg font-semibold mb-2'>No API Keys</h3>
                                    <p className='text-sm text-muted-foreground max-w-sm mx-auto'>
                                        You haven't created any API keys yet. Create one to get started with the API.
                                    </p>
                                </div>
                            ) : (
                                <div className='space-y-3'>
                                    {keys.map((key) => (
                                        <div
                                            key={key.identifier}
                                            className='bg-popover border border-border rounded-lg p-4 hover:border-accent transition-all duration-150'
                                        >
                                            <div className='flex items-center justify-between'>
                                                <div className='flex-1 min-w-0'>
                                                    <div className='flex items-center gap-3 mb-2'>
                                                        <h4 className='text-sm font-medium truncate'>
                                                            {key.description}
                                                        </h4>
                                                    </div>
                                                    <div className='flex flex-wrap items-center gap-4 text-xs text-muted-foreground'>
                                                        <span>
                                                            Last used:{' '}
                                                            {key.lastUsedAt
                                                                ? format(new Date(key.lastUsedAt), 'MMM d, yyyy HH:mm')
                                                                : 'Never'}
                                                        </span>
                                                        <div className='flex items-center gap-2'>
                                                            <span>Key:</span>
                                                            <code className='font-mono px-2 py-1 bg-muted/20 border border-border rounded'>
                                                                {showKeys[key.identifier]
                                                                    ? key.identifier
                                                                    : '••••••••••••••••'}
                                                            </code>
                                                            <Button
                                                                variant='ghost'
                                                                size='icon'
                                                                onClick={() => toggleKeyVisibility(key.identifier)}
                                                                className='h-7 w-7'
                                                            >
                                                                {showKeys[key.identifier] ? (
                                                                    <EyeSlash
                                                                        width={16}
                                                                        height={16}
                                                                        fill='currentColor'
                                                                    />
                                                                ) : (
                                                                    <Eye width={16} height={16} fill='currentColor' />
                                                                )}
                                                            </Button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <Button
                                                    variant='destructive'
                                                    size='icon'
                                                    className='ml-4'
                                                    onClick={() => setDeleteIdentifier(key.identifier)}
                                                >
                                                    <TrashBin width={18} height={18} fill='currentColor' />
                                                </Button>
                                            </div>
                                        </div>
                                    ))}
                                </div>
                            )}
                        </>
                    )}
                </CardContent>
            </Card>
        </div>
    );
};

export default AccountApiContainer;
