import type {EntriesTable} from "./EntriesTable.ts";
import type {RowDescriptor} from "./RowDescriptor.ts";
import type {ColumnDescriptor} from "./ColumnDescriptor.ts";
import type {FieldErrors} from "../../features/timesheet/types/ValidationError.ts";

export interface TableProps {
    rows: RowDescriptor[];
    columns: ColumnDescriptor[];
    entries: EntriesTable;
    onChange?: (row: string | number, column: string | number, value: number) => void;
    className?: string;
    footerRows?: RowDescriptor[];
    readonly?: boolean;
    fieldErrors?: FieldErrors;
}