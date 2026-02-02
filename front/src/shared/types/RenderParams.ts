export type RenderParams = {
    value: any;
    rowKey: string;
    colKey: string;
    onChange?: (rowKey: string, colKey: string, value: any) => void;
    hasError?: boolean;
    readonly?: boolean;
};