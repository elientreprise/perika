import type {LoginPayload} from "../types/Auth/LoginPayload.ts";
import type {RegisterPayload} from "../types/Auth/RegisterPayload.ts";

export async function login(data: LoginPayload) {
    return { success: true, user: data.email };
}

export async function register(data: RegisterPayload) {
    return { success: true, user: data.email };
}
