
import {useParams} from "react-router-dom";
import Table from "../../../shared/components/ui/Table.tsx";
import {useMemo} from "react";
import {buildEntries} from "../utils/buildEntries.ts";


export default function HistoryTimesheetPage() {

    const { employeeUuid, timesheetUuid } = useParams();

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
                key: 0
            },
        ], [])

    const timesheetEntries = useMemo(
        () => buildEntries(rows, columns),
        [columns, rows]
    );


    return (
        <section className={`space-y-4 w-full pointer-events-none`}>
            <h3>Rechercher une feuille de temps</h3>
            <div className={"rounded bg-base-300 h-25 w-full p-3 flex flex-col gap-3"}>
                <label className="input input-xs">
                    Employée :
                    <input type="search" required placeholder="Search"/>
                    <svg className="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g
                            stroke-linejoin="round"
                            stroke-linecap="round"
                            stroke-width="2.5"
                            fill="none"
                            stroke="currentColor"
                        >
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>

                </label>
                <label className="input input-xs">
                    Date fin période :
                    <input type="search" required placeholder="Search"/>
                    <svg className="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g
                            stroke-linejoin="round"
                            stroke-linecap="round"
                            stroke-width="2.5"
                            fill="none"
                            stroke="currentColor"
                        >
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                </label>
            </div>
            <Table
                rows={rows}
                columns={columns}
                entries={timesheetEntries}
                readonly={true}
            />
        </section>
    );

}
