import { Shield } from '@gravity-ui/icons';
import clsx from 'clsx';
import { useContext, useEffect } from 'react';

import { DialogContext, DialogIconProps, styles } from './';

// const icons = {
//     danger: ShieldExclamationIcon,
//     warning: ExclamationIcon,
//     success: CheckIcon,
//     info: InformationCircleIcon,
// };

export default ({ type, position, className }: DialogIconProps) => {
    const { setIcon, setIconPosition } = useContext(DialogContext);

    useEffect(() => {
        setIcon(
            <div
                className={clsx(
                    'mr-4 flex h-10 w-10 items-center justify-center rounded-full',
                    {
                        'bg-destructive text-destructive-foreground': type === 'danger',
                        'bg-yellow-600 text-yellow-50': type === 'warning',
                        'bg-green-600 text-green-50': type === 'success',
                        'bg-muted text-muted-foreground': type === 'info',
                    },
                    className,
                )}
            >
                <Shield width={22} height={22} fill='currentColor' />
            </div>,
        );
    }, [type, className]);

    useEffect(() => {
        setIconPosition(position);
    }, [position]);

    return null;
};
