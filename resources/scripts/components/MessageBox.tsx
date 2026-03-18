import { AlertCircle, CheckCircle2, Info, TriangleAlert } from 'lucide-react';
import React from 'react';

import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';

interface MessageBoxProps {
    title?: string;
    type?: 'error' | 'info' | 'success' | 'warning';
    children?: React.ReactNode;
}

const MessageBox: React.FC<MessageBoxProps> = ({ title, type = 'info', children }) => {
    const Icon = {
        error: AlertCircle,
        warning: TriangleAlert,
        success: CheckCircle2,
        info: Info,
    }[type];

    return (
        <Alert variant={type === 'error' ? 'destructive' : 'default'}>
            <Icon className='h-4 w-4' />
            {title && <AlertTitle>{title}</AlertTitle>}
            <AlertDescription>{children}</AlertDescription>
        </Alert>
    );
};

export default MessageBox;
