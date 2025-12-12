import {useContext} from "react";
import type {AuthContextType} from "../types/AuthContextType.ts";
import {AuthContext} from "../contexts/AuthContext.tsx";

export function useAuth(): AuthContextType {
    const context = useContext(AuthContext);
    if (!context) throw new Error("useAuth must be used inside <AuthProvider>");
    return context;
}
