import {JSX} from "react";

export type ColumnDescriptor = {
    key: string;
    label: string;
    disabled?: boolean;
    render?: (value: number | string | boolean | null, rowKey: string, colKey: string, onChange, error?: boolean, readonly: boolean | undefined) => React.JSX.Element;
    readonly?: boolean;
};
