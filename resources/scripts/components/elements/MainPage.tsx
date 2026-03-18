const MainPage: React.FC<React.HTMLAttributes<HTMLDivElement>> = ({ children, className, ...props }) => (
    <div className={`max-w-[120rem] w-full mx-auto px-2 sm:px-14 py-2 sm:py-14 ${className || ''}`} {...props}>
        {children}
    </div>
);

export default MainPage;
