import {Suspense} from "react";
import {AuthProvider} from "../features/auth/providers/AuthProvider.tsx";
import {BrowserRouter, Route, Routes} from "react-router-dom";

import Register from "../pages/Register.tsx";
import Login from "../pages/Login.tsx";

import './App.css';
import HomePage from "../pages/HomePage.tsx";
import FinancePage from "../pages/FinancePage.tsx";

import {FlashProvider} from "./providers/FlashProvider.tsx";
import DashboardLayout from "../shared/components/layout/DashboardLayout.tsx";
import { useFlash } from "../shared/hooks/useFlash.ts";
import PublicRoute from "../features/auth/components/PublicRoute.tsx";
import ProtectedRoute from "../features/auth/components/ProtectedRoute.tsx";
import FlashContainer from "../shared/components/layout/FlashContainer.tsx";
import {Loader} from "../shared/components/ui/Loader.tsx";
import CreateTimesheetPage from "../features/timesheet/pages/CreateTimesheetPage";
import ResumeTimesheetPage from "../features/timesheet/pages/ResumeTimesheetPage.tsx";
import HistoryTimesheetPage from "../features/timesheet/pages/HistoryTimesheetPage.tsx";

export default function App() {
    const flash = useFlash();

    return (
        <AuthProvider>
            <FlashProvider>
                <BrowserRouter>
                    <FlashContainer flashes={flash.flashes} remove={flash.remove} />
                    <Suspense fallback={<Loader />}>
                        <Routes>
                            <Route element={<PublicRoute />}>
                                <Route path="/login" element={<Login />} />
                                <Route path="/register" element={<Register />} />
                            </Route>

                            <Route element={<ProtectedRoute />}>
                                <Route path="/" element={<DashboardLayout />}>
                                    <Route index element={<HomePage />} />
                                    <Route path="/finance" element={<FinancePage />} />
                                    <Route path="/finance/timesheets/create" element={<CreateTimesheetPage />} />
                                    <Route exact path="/finance/employees/:employeeUuid/timesheets/:timesheetUuid" element={<ResumeTimesheetPage />} />
                                    <Route exact path="/finance/timesheets" element={<HistoryTimesheetPage />} />
                                </Route>
                            </Route>
                        </Routes>
                    </Suspense>
                </BrowserRouter>
            </FlashProvider>
        </AuthProvider>
    );
}