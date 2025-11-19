import type {UserType} from "../UserType.ts";
import type {CheckAuthResponse} from "../Auth/CheckAuthResponse.ts";

export type AuthContextType = {
    user: UserType | null;
    storeUser: (userData: UserType) => void;
    removeUser: () => void;
    checkAuth: () => Promise<CheckAuthResponse>;
    loading: boolean
};