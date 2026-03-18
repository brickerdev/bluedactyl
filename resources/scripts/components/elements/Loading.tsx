import { useEffect, useState } from 'react'
import { Progress } from '@/components/ui/progress'

function Loading() {
    const [value, setValue] = useState<number>(0)

    useEffect(() => {
        const interval = setInterval(() => {
            setValue((oldValue: number) => {
                if (oldValue >= 100) {
                    return 0
                }
                return oldValue + 10
            })
        }, 300)
        return () => clearInterval(interval)
    }, [])

    return (
        <div className='min-h-screen flex items-center justify-center'>
            <Progress value={value} className='w-full max-w-sm'>
                Loading...
            </Progress>
        </div>
    )
}

export default Loading
export { Loading }
