export default function Navbar() {
    return (
        <header className="flex items-center justify-between p-5 shadow bg-base-100">
            <div>
                <h1 className="text-2xl font-bold"></h1>
                <p className="text-sm text-gray-500"></p>
            </div>

            <div className="avatar placeholder">
                <div className="bg-neutral text-neutral-content rounded-full w-12">
                    <span>N</span>
                </div>
            </div>
        </header>
    );
}
