export default function AppLogo() {
    return (
        <>
            <div className="flex aspect-square size-8 items-center justify-center rounded-md border bg-white text-sidebar-primary-foreground">
                <img src="storage/img/logo.svg" className="size-5 fill-current text-white" alt="logo" />
            </div>
            <div className="ml-1 grid flex-1 text-left text-sm">
                <span className="mb-0.5 truncate leading-tight font-semibold">List & task manager</span>
            </div>
        </>
    );
}
