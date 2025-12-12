import {InputHTMLAttributes} from "react";
import type {Option} from "./SelectOptionProps.ts";

export type SearchableSelectProps = {
    options: Option[];
    value?: string | number;
    query?: string | number;
    onChange?: (value: string | number) => void;
    setQuery?: (value: (((prevState: string) => string) | string)) => void;
    placeholder?: string;
    className?: string;
    loading?: boolean
    error?: boolean;
    readonly?: boolean;
} & InputHTMLAttributes<HTMLInputElement>

