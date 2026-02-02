import Table from "../../../../shared/components/ui/Table.tsx";
import type {RowDescriptor} from "../../../../shared/types/RowDescriptor.ts";
import type {ColumnDescriptor} from "../../../../shared/types/ColumnDescriptor.ts";
import SearchInput from "../../../../shared/components/ui/SearchInput.tsx";
import SearchDateInput from "../../../../shared/components/ui/SearchDateInput.tsx";
import React from "react";
import type {EntriesTable} from "../../../../shared/types/EntriesTable.ts";
import {LoaderButton} from "../../../../shared/components/ui/LoaderButton.tsx";

type Props = {
    rows: RowDescriptor[];
    columns: ColumnDescriptor[];
    timesheets: EntriesTable;
    params: Partial<T>;
    updateParam: (key: string, value: string | null) => void;
    onSubmitSearch: () => void ;
    loading: boolean;
    error: string | null;
}
export default function TimesheetHistoryView({
                                                 rows,
                                                 columns,
                                                 timesheets,
                                                 params,
                                                 updateParam,
                                                 onSubmitSearch,
                                                 loading,
                                                 error
                                             }:Readonly<Props>) {
    return (
        <div>
            <div className={"bg-base-300 h-25 w-full p-3 flex flex-col gap-3 mt-1"}>
                <div className={"flex gap-3"}>
                    <SearchInput label={"Employée"} loading={loading} setQuery={(event) => updateParam('employee', event.target.value)} query={params.employee} placeholder={"Code employée"}/>
                    <SearchDateInput label={"Date fin période"} loading={loading} setQuery={(value) => updateParam('endPeriod', value)} query={params.endPeriod}/>
                </div>

                {error && (
                    <div className="alert alert-error mb-4">
                        <span>{error}</span>
                    </div>
                )}

                <div className={"w-full flex justify-end"}>
                    <button onClick={onSubmitSearch}
                            className="px-4 py-2 bg-primary text-white rounded btn btn-xs text-xs">Trouver feuilles
                        de temps
                    </button>
                </div>
            </div>
            {loading ? <LoaderButton/> :<Table
                rows={rows}
                columns={columns}
                data={timesheets}
                readonly={true}
            />}

        </div>
    )

}