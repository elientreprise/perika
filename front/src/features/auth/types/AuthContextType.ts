import {z} from "zod";
import type {UserSchema} from "../../employee/types/UserType.ts";
import type {CheckAuthResponseSchema} from "./CheckAuthResponse.ts";

export type AuthContextType = {
    user: z.infer<typeof UserSchema> | null;
    storeUser: (userData: z.infer<typeof UserSchema>) => void;
    removeUser: () => void;
    checkAuth: () => Promise<z.infer<typeof CheckAuthResponseSchema> | null>;
    loading: boolean;
};