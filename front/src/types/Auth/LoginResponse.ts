import type {UserType} from "../UserType.ts";

export type LoginResponse = {
    message: string; user: UserType
}