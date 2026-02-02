import { useMemo } from "react";
import type {EntriesTable} from "../../../shared/types/EntriesTable.ts";
import type {ColumnDescriptor} from "../../../shared/types/ColumnDescriptor.ts";



export function useTotals(entriesList: EntriesTable[], columns: ColumnDescriptor[]) {
    return useMemo(() => {
        const totals: Record<string, number> = {};

        for (const col of columns) {
            totals[col.key] = 0;
        }

        for (const entries of entriesList) {
            for (const rowKey in entries) {
                for (const col of columns) {
                    totals[col.key] += entries[rowKey][col.key] || 0;
                }
            }
        }

        return totals;
    }, [entriesList, columns]);
}
