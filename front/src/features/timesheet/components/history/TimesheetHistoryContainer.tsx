import TimesheetHistoryView from "./TimesheetHistoryView.tsx";
import {useEffect, useMemo, useState} from "react";
import type {RowDescriptor} from "../../../../shared/types/RowDescriptor.ts";
import {useSearchTimesheets} from "../../hooks/useSearchTimesheets.ts";
import type {EntriesTable} from "../../../../shared/types/EntriesTable.ts";
import {toSimpleDate} from "../../../../shared/utils/DateFormatter.ts";
import {useSearchParams} from "../../../../shared/hooks/useSearchParams.ts";
import type {TimesheetSearchParameters} from "../../types/TimesheetSearchParameters.ts";
import type {RenderParams} from "../../../../shared/types/RenderParams.ts";
import {Link} from "react-router-dom";

export default function TimesheetHistoryContainer() {


    const { timesheets, search, loading, error } = useSearchTimesheets();

    const columns = useMemo(
        () =>  [
            {
                key: "endPeriod",
                label: "Date fin période",
            },
            {
                key: "timesheet",
                label: "Feuille temps",
            },
            {
                key: "status",
                label: "Status",
            },
            {
                key: "actions",
                label: "",
                render: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => (
                    <div>
                        <Link to={`/finance/employees/${timesheets[rowKey]?.employee.uuid}/timesheets/${timesheets[rowKey]?.uuid}`}> {">"} </Link>
                    </div>
                )
            }
        ], [timesheets])

    const { params, updateParam } = useSearchParams<TimesheetSearchParameters>();


    useEffect(() => {
        if (Object.keys(params).length > 0) {
            search(params.employee, params);
        }
    }, []);


    const [tableData, setTableData] = useState<EntriesTable>({});
    const [rows, setRows] = useState<RowDescriptor[]>([
        {
            key: "0",
            label: ""
        }
    ]);


    useEffect(() => {
        if (timesheets.length === 0) {
            setTableData({});
            return;
        }

        const timesheetData: EntriesTable = {};
        const timesheetRow: RowDescriptor[] = [];

        timesheets.forEach((timesheet, index) => {
            const rowKey = index;

            if (!timesheetData[rowKey]) {
                timesheetData[rowKey] = {};
                timesheetRow[rowKey] = {};
            }

            timesheetData[rowKey]["endPeriod"] = toSimpleDate(new Date(timesheet.endPeriod)) || "";
            timesheetData[rowKey]["status"] = "pending";
            timesheetData[rowKey]["timesheet"] = timesheet.uuid;
            timesheetData[rowKey]["employee"] = timesheet.employee?.uuid || "";

            timesheetRow[rowKey]["key"] = rowKey.toString();
            timesheetRow[rowKey]["label"] = "";
        });
        setTableData(timesheetData);
        setRows(timesheetRow)
    }, [timesheets]);


    const onSubmitSearch = async () => {
        search(params.employee, params)
    };

    return(
        <TimesheetHistoryView
            rows={rows}
            columns={columns}
            timesheets={tableData}
            params={params}
            updateParam={updateParam}
            onSubmitSearch={onSubmitSearch}
            loading={loading}
            error={error}
        />
    )
}