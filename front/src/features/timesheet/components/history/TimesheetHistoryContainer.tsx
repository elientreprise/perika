import TimesheetHistoryView from "./TimesheetHistoryView.tsx";
import {useMemo} from "react";
import {buildEntries} from "../../utils/buildEntries.ts";
import type {RowDescriptor} from "../../../../shared/types/RowDescriptor.ts";

export default function TimesheetHistoryContainer() {


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
            }
        ], [])

    const rows = useMemo(
        () =>  [
            {
                key: 0,
                label: ""
            } as RowDescriptor,
        ], [])

    const timesheetEntries = useMemo(
        () => buildEntries(rows, columns),
        [columns, rows]
    );


    return(
        <TimesheetHistoryView rows={rows} columns={columns} timesheetEntries={timesheetEntries}/>
    )
}