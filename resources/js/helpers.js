export function route() {
    const currentUrl = window.location.pathname;
    
    return {
        current: (name) => {
            if (name.endsWith('.*')) {
                const baseName = name.slice(0, -2);
                return currentUrl.startsWith(`/${baseName}`);
            }
            return currentUrl === `/${name}` || currentUrl === name;
        }
    };
}
