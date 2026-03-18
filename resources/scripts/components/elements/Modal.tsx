import * as React from 'react';

import Spinner from '@/components/elements/Spinner';
import { Dialog, DialogContent, DialogHeader, DialogTitle } from '@/components/ui/dialog';

import { cn } from '@/lib/utils';

export interface RequiredModalProps {
    visible: boolean;
    onDismissed: () => void;
    appear?: boolean;
    top?: boolean;
    children?: React.ReactNode;
}

export interface ModalProps extends RequiredModalProps {
    title?: string;
    closeButton?: boolean;
    dismissable?: boolean;
    closeOnEscape?: boolean;
    closeOnBackground?: boolean;
    showSpinnerOverlay?: boolean;
}

const Modal: React.FC<ModalProps> = ({ title, visible, onDismissed, showSpinnerOverlay, children }) => {
    return (
        <Dialog open={visible} onOpenChange={(open) => !open && onDismissed()}>
            <DialogContent className='sm:max-w-[600px] p-0 overflow-hidden border-none bg-transparent shadow-none outline-none'>
                <div className='relative bg-background rounded-xl border border-border shadow-lg overflow-hidden'>
                    {showSpinnerOverlay && (
                        <div className='absolute inset-0 z-50 flex items-center justify-center bg-background/50'>
                            <Spinner />
                        </div>
                    )}

                    {title && (
                        <DialogHeader className='p-6 pb-0'>
                            <DialogTitle className='text-2xl font-bold text-foreground'>{title}</DialogTitle>
                        </DialogHeader>
                    )}

                    <div className={cn('p-6', !title && 'pt-6')}>{children}</div>
                </div>
            </DialogContent>
        </Dialog>
    );
};

export default Modal;
export { Modal };
