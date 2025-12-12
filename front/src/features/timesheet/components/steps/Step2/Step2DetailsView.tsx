import type {ColumnDescriptor} from "../../../../../shared/types/ColumnDescriptor.ts";
import type {RowDescriptor} from "../../../../../shared/types/RowDescriptor.ts";
import type {EntriesTable} from "../../../../../shared/types/EntriesTable.ts";
import {TimesheetLeaves} from "../../../types/TimesheetLeavesType.ts";
import {DaysOfWeek} from "../../../../../shared/types/DaysOfWeekType.ts";
import Table from "../../../../../shared/components/ui/Table.tsx";
import type {FieldErrors} from "../../../types/ValidationError.ts";

type Step2DetailsViewProps = {
    current: boolean;
    onPrevious?: () => void;
    columnsWithProject: ColumnDescriptor[],
    rowsProject: RowDescriptor[],
    projectEntries: EntriesTable,
    changeProjects?: (row: string, col: string, value: number) => void,
    columnsWithTotal: ColumnDescriptor[],
    hoursEntries?: EntriesTable,
    changeHours?: (row: string, col: string, value: number) => void,
    totalsByDay: Record<string, number>,
    totalsGlobalByDay: Record<string, number>,
    rowsRest: RowDescriptor[],
    restEntries: EntriesTable,
    changeRests?: (row: string, col: string, value: number) => void,
    rowsLocation: RowDescriptor[],
    locationEntries: EntriesTable,
    changeLocations?: (row: string, col: string, value: number) => void,
    onSubmit?: () => void,
    fieldErrors?: FieldErrors;
    readonly?: boolean;
};
export default function Step2DetailsView({
                                             current,
                                             onPrevious,
                                             columnsWithProject,
                                             projectEntries,
                                             changeProjects,
                                             rowsProject,
                                             columnsWithTotal,
                                             hoursEntries,
                                             changeHours,
                                             totalsByDay,
                                             totalsGlobalByDay,
                                             rowsRest,
                                             restEntries,
                                             changeRests,
                                             rowsLocation,
                                             locationEntries,
                                             changeLocations,
                                             onSubmit,
                                             fieldErrors = {},
                                             readonly

}: Readonly<Step2DetailsViewProps>) {
    return (
        <div className="w-full">
            <h3 className="text-xl font-semibold mb-4 mt-3">Détails heures projet</h3>

            <Table
                columns={columnsWithProject}
                rows={rowsProject}
                entries={projectEntries}
                onChange={changeProjects}
                fieldErrors={fieldErrors}
                readonly={readonly}
            />

            <h3 className="text-xl font-semibold mb-4 mt-6">Absences et heures internes</h3>

            <Table
                columns={columnsWithTotal}
                rows={TimesheetLeaves}
                entries={hoursEntries}
                onChange={changeHours}
                footerColumns={DaysOfWeek}
                footerRows={[
                    { key: "internal", label: "Total absences", render: (value, rowKey, colKey) => <strong>{totalsByDay[colKey]}</strong> } as RowDescriptor,
                    { key: "global", label: "Total global", render: (value, rowKey, colKey) => <strong>{totalsGlobalByDay[colKey]}</strong> } as RowDescriptor
                ]}
                fieldErrors={fieldErrors}
                readonly={readonly}
            />

            <h3 className="text-xl font-semibold mb-4 mt-6">Informations complémentaires</h3>
            <Table columns={DaysOfWeek} rows={rowsRest} entries={restEntries} onChange={changeRests} fieldErrors={fieldErrors} readonly={readonly} />

            <h4 className="text-xl font-semibold mb-4 mt-6">Localisation</h4>
            <Table columns={DaysOfWeek} rows={rowsLocation} entries={locationEntries} onChange={changeLocations} fieldErrors={fieldErrors} readonly={readonly} />

            {current && (
                <div className="flex gap-3 mt-4">
                    <button onClick={onPrevious} className="px-4 py-2 border border-error text-white rounded">Retour</button>
                    <button onClick={onSubmit} className="px-4 py-2 bg-primary text-white rounded">Soumettre</button>
                </div>
            )}
        </div>
    );
}