// Provides necessary information for components to function properly
// million-ignore
const PyrodactylProvider = ({ children }) => {
    return (
        <div
            data-blue-pyrodactylprovider=''
            data-blue-bluedactyl-version={import.meta.env.VITE_PYRODACTYL_VERSION}
            data-blue-bluedactyl-build={import.meta.env.VITE_PYRODACTYL_BUILD_NUMBER}
            data-blue-commit-hash={import.meta.env.VITE_COMMIT_HASH}
        >
            {children}
        </div>
    );
};

export default PyrodactylProvider;
