import type {EntriesTable} from "./EntriesTable.ts";
import type {RowDescriptor} from "./RowDescriptor.ts";
import type {ColumnDescriptor} from "./ColumnDescriptor.ts";
import type {FieldErrors} from "../../features/timesheet/types/ValidationError.ts";

export type TableProps = {
    columns: ColumnDescriptor[];
    rows: RowDescriptor[];
    data: EntriesTable;
    onChange?: (rowKey: string, colKey: string, value: any) => void;
    errors?: FieldErrors;
    readonly?: boolean;
    footerRows?: RowDescriptor[];
    className?: string;
};