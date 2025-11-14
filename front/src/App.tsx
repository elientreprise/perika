import React, {Suspense} from "react";
import {AuthProvider} from "./providers/AuthProvider.tsx";
import {BrowserRouter, Route, Router, Routes} from "react-router-dom";
import ProtectedRoute from "./components/Route/ProtectedRoute.tsx";
import Register from "./pages/Register.tsx";
import Login from "./pages/Login.tsx";
import Dashboard from "./pages/Dashboard.tsx";
import PublicRoute from "./components/Route/PublicRoute.tsx";
import {Loader} from "./components/Loader.tsx";

export default function App() {
    return (
        <AuthProvider>
            <BrowserRouter>
                <Suspense fallback={<Loader />}
                >
                    <Routes>
                        <Route element={<PublicRoute />}>
                            <Route path="/login" element={<Login />} />
                            <Route path="/register" element={<Register />} />
                        </Route>

                        <Route element={<ProtectedRoute />}>
                            <Route path="/" element={<Dashboard />} />
                        </Route>
                    </Routes>
                </Suspense>
            </BrowserRouter>
        </AuthProvider>
    );
}