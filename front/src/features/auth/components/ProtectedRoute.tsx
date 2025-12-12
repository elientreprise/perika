import {useContext} from "react";
import { Navigate, Outlet } from "react-router-dom";
import {Loader} from "../../../shared/components/ui/Loader.tsx";
import {AuthContext} from "../contexts/AuthContext.tsx";

export default function ProtectedRoute() {
    const { user, loading  } = useContext(AuthContext);

    if (loading) return <Loader />;

    if (!user) {
        return <Navigate to="/login" replace />;
    }

    return <Outlet />;
}
