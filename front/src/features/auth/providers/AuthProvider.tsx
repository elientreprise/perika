import {useCallback, useEffect, useMemo, useState} from "react";
import type { ReactNode } from "react";
import {get} from "../../../app/services/api.ts";
import {AuthContext} from "../contexts/AuthContext.tsx";
import type {UserType} from "../../employee/types/UserType.ts";
import type {CheckAuthResponse} from "../types/CheckAuthResponse.ts";
import {CheckAuthResponseSchema} from "../types/CheckAuthResponse.ts";


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

    const checkAuth = useCallback(async ():Promise<CheckAuthResponse |  null > => {
        try {
            const res = await get("/authenticated");
            const parsed = CheckAuthResponseSchema.parse(res);

            setUser(parsed.user);
            return parsed;
        } catch(err:any) {
            console.error("Erreur auth:", err);
            setUser(null);
            return null;
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