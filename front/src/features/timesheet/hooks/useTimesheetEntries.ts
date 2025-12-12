import { useState, useCallback } from "react";
import type {EntriesTable} from "../../../shared/types/EntriesTable.ts";
import type {DaysOfWeekKey} from "../../../shared/types/DaysOfWeekType.ts";
import {roundFloat} from "../../../shared/utils/RoundNumber.ts";



export function useTimesheetEntries(initial: EntriesTable, dayColumns: { key: DaysOfWeekKey }[]) {
    const [entries, setEntries] = useState<EntriesTable>(initial);

    const handleChange = useCallback((row: string, col: string, value: number | string | boolean | null) => {
        setEntries(prev => {
            const updatedRow = { ...prev[row], [col]: value };

            updatedRow.total = dayColumns.reduce(
                (sum, d) => roundFloat(sum + (updatedRow[d.key] || 0)),
                0
            );

            return { ...prev, [row]: updatedRow };
        });
    }, [dayColumns]);

    return { entries, handleChange, setEntries };
}
