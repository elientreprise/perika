import { useSearchParams as useRouterSearchParams } from "react-router-dom";
import { useCallback, useMemo } from "react";

export function useSearchParams<T extends Record<string, string>>() {
    const [searchParams, setSearchParams] = useRouterSearchParams();

    const params = useMemo(() => {
        const result: Partial<T> = {};

        for (const [key, value] of searchParams.entries()) {
            result[key as keyof T] = value as T[keyof T];
        }
        return result;
    }, [searchParams]);

    const setParams = useCallback((newParams: Partial<T>) => {
        const filteredParams: Record<string, string> = {};

        for (const [key, value] of Object.entries(newParams)) {
            if (value !== undefined && value !== null && value !== "") {
                filteredParams[key] = String(value);
            }
        }

        setSearchParams(filteredParams);
    }, [setSearchParams]);

    const updateParam = useCallback((key: keyof T, value: string | null) => {
        const newParams = new URLSearchParams(searchParams);
        if (value === null || value === undefined || value === "") {
            newParams.delete(String(key));
        } else {
            newParams.set(String(key), String(value));
        }
        setSearchParams(newParams);
    }, [searchParams, setSearchParams]);

    const clearParams = useCallback(() => {
        setSearchParams({});
    }, [setSearchParams]);

    return {
        params,
        setParams,
        updateParam,
        clearParams,
    };
}