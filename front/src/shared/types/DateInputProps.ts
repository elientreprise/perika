import type {ChangeEvent, InputHTMLAttributes} from "react";

export type DateInputProps = {
    label: string;
    value: string | undefined;
    onChange?: (event: ChangeEvent<HTMLInputElement>) => void;
    className?: string;
} & Omit<InputHTMLAttributes<HTMLInputElement>, "type" | "onChange" | "value">;