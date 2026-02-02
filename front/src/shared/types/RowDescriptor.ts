import type {RenderParams} from "./RenderParams.ts";
import type {ReactNode} from "react";

export type RowDescriptor = {
    key: string;
    label: string;
    disabled?: boolean;
    render?: (params: RenderParams) => ReactNode;
    readonly?: boolean;
};
