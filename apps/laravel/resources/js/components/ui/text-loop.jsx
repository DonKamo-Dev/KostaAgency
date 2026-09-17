import * as React from 'react';
import { AnimatePresence, motion } from 'framer-motion';
import { cn } from '@/lib/utils';

export function TextLoop({
    children,
    className,
    interval = 2,
    transition = { duration: 0.3 },
    variants,
    onIndexChange,
}) {
    const [currentIndex, setCurrentIndex] = React.useState(0);
    const items = React.Children.toArray(children);

    React.useEffect(() => {
        const timeoutId = setTimeout(() => {
            const next = (currentIndex + 1) % items.length;
            setCurrentIndex(next);
            onIndexChange?.(next);
        }, interval * 1000);
        return () => clearTimeout(timeoutId);
    }, [currentIndex, interval, items.length, onIndexChange]);

    const motionVariants = {
        initial: { y: 20, opacity: 0 },
        animate: { y: 0, opacity: 1 },
        exit: { y: -20, opacity: 0 },
        ...variants,
    };

    return (
        <div className={cn('relative inline-grid overflow-hidden', className)}>
            <AnimatePresence mode="popLayout" initial={false}>
                <motion.div
                    key={currentIndex}
                    initial="initial"
                    animate="animate"
                    exit="exit"
                    transition={transition}
                    variants={motionVariants}
                >
                    {items[currentIndex]}
                </motion.div>
            </AnimatePresence>
        </div>
    );
}
