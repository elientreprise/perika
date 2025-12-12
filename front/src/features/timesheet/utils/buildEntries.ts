import type {DaysOfWeekKey} from "../../../shared/types/DaysOfWeekType.ts";
import type {EntriesTable} from "../../../shared/types/EntriesTable.ts";
import type {RowDescriptor} from "../../../shared/types/RowDescriptor.ts";
import type {ColumnDescriptor} from "../../../shared/types/ColumnDescriptor.ts";


/**
 * Construit les entrés pour le composant <Table/>
 *
 */
export function buildEntries<T extends string>(
    rows: RowDescriptor[],
    columns: ColumnDescriptor[]
): EntriesTable {
    const result: Record<T, Record<string, number | null>> = {};

    for (const { key } of rows) {
        result[key] = {};
        for (const col of columns) {
            result[key][col.key] = null;
        }
    }

    return result;
}
