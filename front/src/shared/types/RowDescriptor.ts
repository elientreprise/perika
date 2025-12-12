import {JSX} from "react";

export type RowDescriptor = {
    key: string;
    label: string;
    disabled?: boolean;
    render?: (value: number | string | null | boolean, rowKey: string, colKey: string, onChange, error?: boolean) => JSX.Element;
    readonly?: boolean;
};
