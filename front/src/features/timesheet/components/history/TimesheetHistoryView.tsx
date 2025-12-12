import Table from "../../../../shared/components/ui/Table.tsx";
import type {RowDescriptor} from "../../../../shared/types/RowDescriptor.ts";
import type {ColumnDescriptor} from "../../../../shared/types/ColumnDescriptor.ts";
import type {EntriesTable} from "../../../../shared/types/EntriesTable.ts";

type Props = {
    rows: RowDescriptor[];
    columns: ColumnDescriptor[];
    timesheetEntries: EntriesTable;
}
export default function TimesheetHistoryView({
                                                 rows,
                                                 columns,
                                                 timesheetEntries
                                             }:Readonly<Props>) {

    return (
        <div>
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
                data={{}}
                entries={timesheetEntries}
                readonly={true}
            />
        </div>
    )

}