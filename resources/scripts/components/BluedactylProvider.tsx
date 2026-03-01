// Provides necessary information for components to function properly
// million-ignore
const BluedactylProvider = ({ children }) => {
    return (
        <div
            data-blue-pyrodactylprovider=''
            data-blue-bluedactyl-version={import.meta.env.VITE_BLUEDACTYL_VERSION}
            data-blue-bluedactyl-build={import.meta.env.VITE_BLUEDACTYL_BUILD_NUMBER}
            data-blue-commit-hash={import.meta.env.VITE_COMMIT_HASH}
        >
            {children}
        </div>
    );
};

export default BluedactylProvider;
