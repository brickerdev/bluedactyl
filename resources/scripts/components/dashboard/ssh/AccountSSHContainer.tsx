import { Eye, EyeSlash, Key, Plus, TrashBin } from '@gravity-ui/icons';
import { yupResolver } from '@hookform/resolvers/yup';
import { format } from 'date-fns';
import { Actions, useStoreActions } from 'easy-peasy';
import { useEffect, useState } from 'react';
import { Controller, useForm } from 'react-hook-form';
import { object, string } from 'yup';

import FlashMessageRender from '@/components/FlashMessageRender';
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
import { Textarea } from '@/components/ui/textarea';

import { createSSHKey, deleteSSHKey, useSSHKeys } from '@/api/account/ssh-keys';
import { httpErrorToHuman } from '@/api/http';

import { ApplicationStore } from '@/state';

import { useFlashKey } from '@/plugins/useFlash';

interface CreateValues {
    name: string;
    publicKey: string;
}

function CreateSSHForm({
    onSubmit,
}: {
    onSubmit: (values: CreateValues, helpers: { setSubmitting: (v: boolean) => void; resetForm: () => void }) => void;
}) {
    const schema = object().shape({
        name: string().required('SSH Key Name is required'),
        publicKey: string().required('Public Key is required'),
    });

    const { control, handleSubmit, formState, reset } = useForm<CreateValues>({
        resolver: yupResolver(schema as any),
        defaultValues: { name: '', publicKey: '' },
    });

    const submitting = formState.isSubmitting;

    return (
        <form
            id='create-ssh-form'
            className='space-y-4'
            onSubmit={handleSubmit((values) => onSubmit(values, { setSubmitting: () => {}, resetForm: () => reset() }))}
        >
            <SpinnerOverlay visible={submitting} />

            <div className='space-y-2'>
                <Label htmlFor='name'>SSH Key Name</Label>
                <Controller
                    control={control}
                    name='name'
                    render={({ field }) => <Input {...field} id='name' className='w-full' />}
                />
                <p className='text-xs text-muted-foreground'>A name to identify this SSH key.</p>
            </div>

            <div className='space-y-2'>
                <Label htmlFor='publicKey'>Public Key</Label>
                <Controller
                    control={control}
                    name='publicKey'
                    render={({ field }) => (
                        <Textarea {...field} id='publicKey' className='w-full min-h-[100px] font-mono text-xs' />
                    )}
                />
                <p className='text-xs text-muted-foreground'>Enter your public SSH key.</p>
            </div>

            <button type='submit' className='hidden' />
        </form>
    );
}

const AccountSSHContainer = () => {
    const [deleteKey, setDeleteKey] = useState<{ name: string; fingerprint: string } | null>(null);
    const [showCreateModal, setShowCreateModal] = useState(false);
    const [showKeys, setShowKeys] = useState<Record<string, boolean>>({});

    const { clearAndAddHttpError } = useFlashKey('account:ssh-keys');
    const { addError, clearFlashes } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);
    const { data, isValidating, error, mutate } = useSSHKeys({
        revalidateOnMount: true,
        revalidateOnFocus: false,
    });

    useEffect(() => {
        clearAndAddHttpError(error);
    }, [error]);

    const doDeletion = () => {
        if (!deleteKey) return;

        clearAndAddHttpError();
        Promise.all([
            mutate((data) => data?.filter((value) => value.fingerprint !== deleteKey.fingerprint), false),
            deleteSSHKey(deleteKey.fingerprint),
        ])
            .catch((error) => {
                mutate(undefined, true).catch(console.error);
                clearAndAddHttpError(error);
            })
            .finally(() => {
                setDeleteKey(null);
            });
    };

    const submitCreate = (
        values: CreateValues,
        helpers: { setSubmitting: (v: boolean) => void; resetForm: () => void },
    ) => {
        clearFlashes('account:ssh-keys');
        createSSHKey(values.name, values.publicKey)
            .then((key) => {
                helpers.resetForm();
                helpers.setSubmitting(false);
                mutate((data) => (data || []).concat(key));
                setShowCreateModal(false);
            })
            .catch((error) => {
                console.error(error);
                addError({ key: 'account:ssh-keys', message: httpErrorToHuman(error) });
                helpers.setSubmitting(false);
            });
    };

    const toggleKeyVisibility = (fingerprint: string) => {
        setShowKeys((prev) => ({
            ...prev,
            [fingerprint]: !prev[fingerprint],
        }));
    };

    return (
        <div className='container mx-auto p-6'>
            <FlashMessageRender byKey='account:ssh-keys' />

            {/* Create SSH Key Modal */}
            <AlertDialog open={showCreateModal} onOpenChange={setShowCreateModal}>
                <AlertDialogContent>
                    <AlertDialogHeader>
                        <AlertDialogTitle>Add SSH Key</AlertDialogTitle>
                    </AlertDialogHeader>

                    <CreateSSHForm onSubmit={submitCreate} />

                    <AlertDialogFooter>
                        <AlertDialogCancel>Cancel</AlertDialogCancel>
                        <AlertDialogAction
                            onClick={(e) => {
                                e.preventDefault();
                                const form = document.getElementById('create-ssh-form') as HTMLFormElement;
                                if (form) {
                                    const submitButton = form.querySelector(
                                        'button[type="submit"]',
                                    ) as HTMLButtonElement;
                                    if (submitButton) submitButton.click();
                                }
                            }}
                        >
                            Add Key
                        </AlertDialogAction>
                    </AlertDialogFooter>
                </AlertDialogContent>
            </AlertDialog>

            <div className='flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4'>
                <div>
                    <h1 className='text-3xl font-bold tracking-tight'>SSH Keys</h1>
                    <p className='text-muted-foreground'>Manage your SSH keys for secure server access.</p>
                </div>
                <Button variant='default' onClick={() => setShowCreateModal(true)} className='flex items-center gap-2'>
                    <Plus width={20} height={20} fill='currentColor' />
                    Add SSH Key
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle className='text-lg'>Active SSH Keys</CardTitle>
                    <CardDescription>A list of all SSH keys associated with your account.</CardDescription>
                </CardHeader>
                <CardContent>
                    <SpinnerOverlay visible={!data && isValidating} />

                    <AlertDialog open={!!deleteKey} onOpenChange={(open) => !open && setDeleteKey(null)}>
                        <AlertDialogContent>
                            <AlertDialogHeader>
                                <AlertDialogTitle>Delete SSH Key</AlertDialogTitle>
                                <AlertDialogDescription>
                                    Removing the{' '}
                                    <code className='font-mono px-2 py-1 bg-muted/20 rounded'>{deleteKey?.name}</code>{' '}
                                    SSH key will invalidate its usage across the Panel.
                                </AlertDialogDescription>
                            </AlertDialogHeader>
                            <AlertDialogFooter>
                                <AlertDialogCancel>Cancel</AlertDialogCancel>
                                <AlertDialogAction onClick={doDeletion}>Delete Key</AlertDialogAction>
                            </AlertDialogFooter>
                        </AlertDialogContent>
                    </AlertDialog>

                    {!data || data.length === 0 ? (
                        <div className='text-center py-12 border rounded-lg'>
                            <div className='w-16 h-16 mx-auto mb-4 rounded-full bg-muted flex items-center justify-center'>
                                <Key width={22} height={22} className='text-muted-foreground' fill='currentColor' />
                            </div>
                            <h3 className='text-lg font-semibold mb-2'>No SSH Keys</h3>
                            <p className='text-sm text-muted-foreground max-w-sm mx-auto'>
                                {!data
                                    ? 'Loading your SSH keys...'
                                    : "You haven't added any SSH keys yet. Add one to securely access your servers."}
                            </p>
                        </div>
                    ) : (
                        <div className='space-y-3'>
                            {data.map((key) => (
                                <div
                                    key={key.fingerprint}
                                    className='bg-popover border border-border rounded-lg p-4 hover:border-accent transition-all duration-150'
                                >
                                    <div className='flex items-center justify-between'>
                                        <div className='flex-1 min-w-0'>
                                            <div className='flex items-center gap-3 mb-2'>
                                                <h4 className='text-sm font-medium truncate'>{key.name}</h4>
                                            </div>
                                            <div className='flex flex-wrap items-center gap-4 text-xs text-muted-foreground'>
                                                <span>Added: {format(key.createdAt, 'MMM d, yyyy HH:mm')}</span>
                                                <div className='flex items-center gap-2'>
                                                    <span>Fingerprint:</span>
                                                    <code className='font-mono px-2 py-1 bg-muted/20 border border-border rounded'>
                                                        {showKeys[key.fingerprint]
                                                            ? `SHA256:${key.fingerprint}`
                                                            : 'SHA256:••••••••••••••••'}
                                                    </code>
                                                    <Button
                                                        variant='ghost'
                                                        size='icon'
                                                        onClick={() => toggleKeyVisibility(key.fingerprint)}
                                                        className='h-7 w-7'
                                                    >
                                                        {showKeys[key.fingerprint] ? (
                                                            <EyeSlash width={16} height={16} fill='currentColor' />
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
                                            onClick={() =>
                                                setDeleteKey({ name: key.name, fingerprint: key.fingerprint })
                                            }
                                        >
                                            <TrashBin width={18} height={18} fill='currentColor' />
                                        </Button>
                                    </div>
                                </div>
                            ))}
                        </div>
                    )}
                </CardContent>
            </Card>
        </div>
    );
};

export default AccountSSHContainer;
