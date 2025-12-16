import {useState, useCallback, useEffect} from "react";
import type {RowDescriptor} from "../types/RowDescriptor.ts";
import type {ColumnDescriptor} from "../types/ColumnDescriptor.ts";
import type {EntriesTable} from "../types/EntriesTable.ts";
import {dayColumns} from "../../features/timesheet/config/columns/columns.tsx";
import {roundFloat} from "../utils/RoundNumber.ts";


type UseTableDataOptions = {
    rows: RowDescriptor[];
    columns: ColumnDescriptor[];
    initialData?: EntriesTable;
};

export function useTableData({ rows, columns, initialData }: UseTableDataOptions) {

    useEffect(() => {
        if (initialData) setData(initialData);
    }, [initialData]);

    const [data, setData] = useState<EntriesTable>(() => {
        if (initialData) return initialData;
        const emptyData: EntriesTable = {};
        for (const row of rows) {
            emptyData[row.key] = {};
            for (const col of columns) {
                emptyData[row.key][col.key] = null;
            }
        }
        return emptyData;
    });

    const handleChange = useCallback((rowKey: string, colKey: string, value: any) => {
        setData((prev) => {
            const updatedRow = { ...prev[rowKey], [colKey]: value };
            updatedRow.total = dayColumns.reduce(
                (sum, d) => roundFloat(sum + (updatedRow[d.key] || 0)),
                0
            );

            return { ...prev, [rowKey]: updatedRow };
        });
    }, []);


    const setTableData = useCallback((newData: EntriesTable | ((prev: EntriesTable) => EntriesTable)) => {
        setData(newData);
    }, []);

    return {
        data,
        handleChange,
        setData: setTableData,
    };
}