import React, {useContext} from "react";
import { Navigate, Outlet } from "react-router-dom";
import { AuthContext } from "../../contexts/AuthContext.tsx";
import {Loader} from "../Loader.tsx";

export default function ProtectedRoute() {
    const { user, loading  } = useContext(AuthContext);

    if (loading) return <Loader />;

    if (!user) {
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
}
