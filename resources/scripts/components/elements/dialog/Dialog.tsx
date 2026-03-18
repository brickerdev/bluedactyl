import { Xmark } from '@gravity-ui/icons';
import * as React from 'react';
import { useState } from 'react';

import {
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    Dialog as ShadcnDialog,
} from '@/components/ui/dialog';

import { DialogContext, IconPosition, RenderDialogProps } from './';

const Dialog = ({
    open,
    title,
    description,
    onClose,
    hideCloseIcon,
    preventExternalClose,
    children,
}: RenderDialogProps) => {
    const [icon, setIcon] = useState<React.ReactNode>();
    const [footer, setFooter] = useState<React.ReactNode>();
    const [iconPosition, setIconPosition] = useState<IconPosition>('title');

    return (
        <ShadcnDialog
            open={open}
            onOpenChange={(isOpen) => {
                if (!isOpen && !preventExternalClose) {
                    onClose();
                }
            }}
        >
            <DialogContext.Provider value={{ setIcon, setFooter, setIconPosition }}>
                <DialogContent
                    className='sm:max-w-[600px] p-0 overflow-hidden border-none bg-transparent shadow-none outline-none'
                    hideClose={hideCloseIcon}
                >
                    <div className='relative bg-background rounded-xl border border-border shadow-lg overflow-hidden'>
                        <div className='flex p-6 pb-0 overflow-y-auto'>
                            {iconPosition === 'container' && icon}
                            <div className='flex-1 max-h-[70vh] min-w-0'>
                                <div className='flex items-center'>
                                    {iconPosition !== 'container' && icon}
                                    <div>
                                        {title && (
                                            <DialogHeader className='p-0 mb-2'>
                                                <DialogTitle className='text-2xl font-bold text-foreground'>
                                                    {title}
                                                </DialogTitle>
                                            </DialogHeader>
                                        )}
                                        {description && (
                                            <DialogDescription className='text-muted-foreground'>
                                                {description}
                                            </DialogDescription>
                                        )}
                                    </div>
                                </div>
                                {children}
                                <div className='invisible h-6' />
                            </div>
                        </div>
                        {footer}
                    </div>
                </DialogContent>
            </DialogContext.Provider>
        </ShadcnDialog>
    );
};

export default Dialog;
