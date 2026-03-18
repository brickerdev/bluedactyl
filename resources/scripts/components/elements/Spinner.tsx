import { Suspense } from 'react';

import ErrorBoundary from '@/components/elements/ErrorBoundary';
import { Spinner as UISpinner } from '@/components/ui/spinner';

import { cn } from '@/lib/utils';

export type SpinnerSize = 'small' | 'base' | 'large';

interface Props {
    size?: SpinnerSize;
    visible?: boolean;
    centered?: boolean;
    isBlue?: boolean;
    children?: React.ReactNode;
    className?: string;
}

interface Spinner extends React.FC<Props> {
    Size: Record<'SMALL' | 'BASE' | 'LARGE', SpinnerSize>;
    Suspense: React.FC<{ children: React.ReactNode }>;
}

const Spinner: Spinner = ({ centered, visible = true, size = 'base', isBlue, className, ...props }) => {
    if (!visible) return null;

    const sizeClasses = {
        small: 'size-4',
        base: 'size-8',
        large: 'size-16',
    };

    const spinner = (
        <UISpinner className={cn(sizeClasses[size], isBlue ? 'text-blue-500' : 'text-primary', className)} {...props} />
    );

    if (centered) {
        return <div className='flex justify-center items-center w-full sm:absolute sm:inset-0 sm:z-50'>{spinner}</div>;
    }

    return spinner;
};

Spinner.displayName = 'Spinner';

Spinner.Size = {
    SMALL: 'small',
    BASE: 'base',
    LARGE: 'large',
};

Spinner.Suspense = ({ children }) => (
    <Suspense fallback={<Spinner centered size={Spinner.Size.LARGE} />}>
        <ErrorBoundary>{children}</ErrorBoundary>
    </Suspense>
);
Spinner.Suspense.displayName = 'Spinner.Suspense';

export default Spinner;
