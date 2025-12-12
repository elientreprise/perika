import type {DaysOfWeekKey} from "../../../shared/types/DaysOfWeekType.ts";
import type {EntriesTable} from "../../../shared/types/EntriesTable.ts";


/**
 * Construit les entrés pour le composant <Table/>
 *
 */
export function buildEntries<T extends string>(
    rows: { key: T }[],
    columns: { key: DaysOfWeekKey | string }[]
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
