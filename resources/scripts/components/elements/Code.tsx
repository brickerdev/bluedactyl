import clsx from 'clsx';

interface CodeProps {
    className?: string;
    children: React.ReactNode;
}

import { cn } from '@/lib/utils';

const Code = ({ children, className }: CodeProps) => (
    <code className={cn('font-mono text-sm px-2 py-1 inline-block rounded-sm w-fit bg-muted/20', className)}>
        {children}
    </code>
);

export default Code;
