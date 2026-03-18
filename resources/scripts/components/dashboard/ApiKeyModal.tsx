import FlashMessageRender from '@/components/FlashMessageRender';
import CopyOnClick from '@/components/elements/CopyOnClick';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';

interface Props {
    visible: boolean;
    onDismissed: () => void;
    apiKey: string;
}

const ApiKeyModal = ({ visible, onDismissed, apiKey }: Props) => {
    return (
        <Dialog open={visible} onOpenChange={(open) => !open && onDismissed()}>
            <DialogContent className='sm:max-w-150'>
                <DialogHeader>
                    <DialogTitle className='text-2xl font-bold'>Your API Key</DialogTitle>
                    <DialogDescription>
                        The API key you have requested is shown below. Please store it in a safe place, as it will not
                        be shown again.
                    </DialogDescription>
                </DialogHeader>

                <div className='py-4'>
                    <FlashMessageRender byKey='account' />

                    <div className='relative mt-2'>
                        <div className='bg-muted p-4 rounded-lg font-mono overflow-x-auto border border-border'>
                            <CopyOnClick text={apiKey}>
                                <code className='text-sm break-words text-foreground'>{apiKey}</code>
                            </CopyOnClick>
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button type='button' onClick={() => onDismissed()} variant='outline'>
                        Close
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    );
};

export default ApiKeyModal;
