import axios, {AxiosHeaders} from "axios";
import { API_URL } from "../config/api.tsx";
import type { AxiosInstance } from "axios";

export const api: AxiosInstance = axios.create({
    baseURL: API_URL,
    withCredentials: true,
    headers: new AxiosHeaders(
        {
            "Content-Type": "application/ld+json"
        }
    )
});


api.interceptors.response.use(
    (response) => response,
    (error) => {
        if (error.response?.status === 401) {
            globalThis.location.href = "/login";
        }
        return Promise.reject(error);
    }
);

export async function get<T = unknown>(url: string): Promise<T> {
    const response = await api.get<T>(url);
    return response.data;
}

export async function post<T = unknown>(url: string, body?: unknown): Promise<T> {
    const response = await api.post<T>(url, body);
    return response.data;
}

export async function patch<T = unknown>(url: string, body?: unknown): Promise<T> {
    const response = await api.patch<T>(url, body, {
        headers: new AxiosHeaders(
            {
                "Content-Type": "application/merge-patch+json"
            }
        )
    });
    return response.data;
}

