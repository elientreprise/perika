import { post } from "../../../app/services/api.ts";
import type {LoginPayload} from "../types/LoginPayload.ts";
import type {LoginResponse} from "../types/LoginResponse.ts";
import type {RegisterPayload} from "../types/RegisterPayload.ts";
import type {UserType} from "../../employee/types/UserType.ts";


export async function login(data: LoginPayload): Promise<LoginResponse> {
    return post<LoginResponse>("/login", data);
}

// todo : changer la response du back pour envoyer un message + user
export async function register(data: RegisterPayload): Promise<UserType> {
    return post<UserType>("/register", data);
}

// todo: changer la response en back pour afficher un message
export async function logout():Promise<void> {
    return post("/logout", {});
}
