import {ReactNode, useCallback, useEffect, useMemo, useState} from "react";
import {get} from "../services/api.ts";
import {AuthContext} from "../contexts/AuthContext.tsx";
import type {CheckAuthResponse} from "../types/Auth/CheckAuthResponse.ts";
import type {UserType} from "../types/UserType.ts";

export const AuthProvider = ({ children }: { children: ReactNode }) => {
    const [user, setUser] = useState<UserType | null>(null);
    const [loading, setLoading] = useState(true);

    const storeUser = useCallback((userData: UserType) => {
        setUser(userData);
    }, []);

    const removeUser = useCallback(() => {
        setUser(null);
        globalThis.location.href = "/login";
    }, []);

    const checkAuth = useCallback(async ():Promise<CheckAuthResponse> => {
        try {
            const res = await get<CheckAuthResponse>("/authenticated");
            setUser(res.user);

        } catch {
            setUser(null);
        }
    }, []);

    useEffect(() => {
        const verify = async () => {
            await checkAuth();
            setLoading(false);
        };
        verify();
    }, [checkAuth]);
    
    const context = useMemo(() => ({ user, storeUser, removeUser, checkAuth, loading }), [checkAuth, loading, removeUser, storeUser, user]);

    return (
        <AuthContext.Provider value={context}>
            {children}
        </AuthContext.Provider>
    );
};