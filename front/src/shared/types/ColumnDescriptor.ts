import type {RenderParams} from "./RenderParams.ts";
import type {ReactNode} from "react";

export type ColumnDescriptor = {
    key: string;
    label: string;
    disabled?: boolean;
    render?: (params: RenderParams) => ReactNode;
    readonly?: boolean;
};
