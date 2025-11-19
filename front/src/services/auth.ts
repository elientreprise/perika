import { post } from "./api";
import type {LoginPayload} from "../types/Auth/LoginPayload.ts";
import type {RegisterPayload} from "../types/Auth/RegisterPayload.ts";
import type {LoginResponse} from "../types/Auth/LoginResponse.ts";
import type {UserType} from "../types/UserType.ts";


export async function login(data: LoginPayload): Promise<LoginResponse> {
    return post<LoginResponse>("/login", data);
}

// todo : changer la response du back pour envoyer un message + user
export async function register(data: RegisterPayload): Promise<UserType> {
    return post<UserType>("/register", data);
}

// todo: changer la response en back pour afficher un message
export async function logout():unknown {
    return post("/logout", {});
}
