import {ChangeEvent, InputHTMLAttributes} from "react";

export type SearchInputProps = {
    label: string;
    query: string | number;
    placeholder: string;
    setQuery: (event: ChangeEvent) => void;
    loading: boolean
    className: string;
    readonly?: boolean;
    error?: boolean;
} & InputHTMLAttributes<HTMLInputElement>

