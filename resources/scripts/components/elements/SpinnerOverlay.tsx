import { SpinnerSize } from '@/components/elements/Spinner';
import { Spinner } from '@/components/ui/spinner';

import { cn } from '@/lib/utils';

interface Props {
    visible: boolean;
    fixed?: boolean;
    size?: SpinnerSize;
    backgroundOpacity?: number;
    children?: React.ReactNode;
}

const SpinnerOverlay: React.FC<Props> = ({ visible, children, size }) => (
    <>
        {visible ? (
            <div className='absolute inset-0 z-50 flex items-center justify-center bg-background/50'>
                <Spinner
                    className={cn(
                        'text-primary',
                        size === 'small' ? 'size-4' : size === 'large' ? 'size-12' : 'size-8',
                    )}
                />
            </div>
        ) : (
            children
        )}
    </>
);

export default SpinnerOverlay;
