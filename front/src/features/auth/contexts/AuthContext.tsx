import {createContext} from "react";
import type {AuthContextType} from "../types/AuthContextType.ts";

export const AuthContext = createContext<AuthContextType>({
    user: null,
    storeUser: () => {},
    removeUser: () => {},
    checkAuth: async () => ({ user: null}),
    loading: true
});