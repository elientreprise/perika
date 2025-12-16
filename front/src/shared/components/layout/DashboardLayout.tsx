import { Outlet } from "react-router-dom";
import Sidebar from "./Sidebar.tsx";
import Navbar from "./Navbar.tsx";

export default function DashboardLayout() {
    return (
        <div className="flex min-h-screen">
            <Sidebar />

            <div className="flex flex-col flex-1">
                <Navbar />

                <main className="flex-1">
                    <Outlet />
                </main>
            </div>
        </div>
    );
}
