import {InputHTMLAttributes} from "react";

export type SearchDateInputProps = {
    label: string;
    query: string;
    setQuery: (value: string) => void;
    loading: boolean
    className?: string;
    readonly?: boolean;
    error?: boolean;
} & InputHTMLAttributes<HTMLInputElement>

