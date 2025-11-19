import type {UserType} from "../UserType.ts";

export type CheckAuthResponse = {
    user: UserType | null;
}