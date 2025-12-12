
import Table from "../../../../../shared/components/ui/Table";
import type {useTableData} from "../../../../../shared/hooks/useTableData.ts";
import {columnsWithProject, columnsWithTotal, dayColumns} from "../../../config/columns/columns.tsx";
import {rowsLeaves, rowsLocation, rowsProject, rowsRest} from "../../../config/rows/rows.tsx";
import React from "react";


type Props = {
    current: boolean;
    onPrevious?: () => void;
    onSubmit?: () => void;
    projectTable: ReturnType<typeof useTableData>;
    restTable: ReturnType<typeof useTableData>;
    locationTable: ReturnType<typeof useTableData>;
    leavesTable: ReturnType<typeof useTableData>;
    errors: Record<string, string>;
    readonly:boolean;
};

export default function Step2DetailsView({
                                             current,
                                             onPrevious,
                                             onSubmit,
                                             projectTable,
                                             restTable,
                                             locationTable,
                                             leavesTable,
                                             errors,
                                             readonly
                                         }: Readonly<Props>) {
    return (
        <div className="w-full space-y-6">
            <h3 className="text-xl font-semibold">Heures projet</h3>
            <Table
                columns={columnsWithProject}
                rows={rowsProject}
                data={projectTable.data}
                onChange={projectTable.handleChange}
                errors={errors}
                readonly={readonly}
            />

            <h3 className="text-xl font-semibold mb-4 mt-6">Absences et heures internes</h3>

            <Table
                columns={columnsWithTotal}
                rows={rowsLeaves}
                data={leavesTable.data}
                onChange={leavesTable.handleChange}
                errors={errors}
                footerRows={[
                    {
                        key: "total-internal",
                        label: "Total absences",
                        render: ({ colKey }) => {
                            const total = rowsLeaves.reduce((sum, row) => {
                                const value = leavesTable.data[row.key]?.[colKey];
                                return sum + (typeof value === "number" ? value : 0);
                            }, 0);
                            return <strong>{total}</strong>;
                        },
                    },
                    {
                        key: "total-global",
                        label: "Total global",
                        render: ({ colKey }) => {
                            const projectValue = projectTable.data.projectRow?.[colKey] || 0;
                            const hoursTotal = rowsLeaves.reduce((sum, row) => {
                                const value = leavesTable.data[row.key]?.[colKey];
                                return sum + (typeof value === "number" ? value : 0);
                            }, 0);
                            return <strong>{projectValue + hoursTotal}</strong>;
                        },
                    },
                ]}
                readonly={readonly}
            />


            <h3 className="text-xl font-semibold">Informations complémentaires</h3>
            <Table
                columns={dayColumns}
                rows={rowsRest}
                data={restTable.data}
                onChange={restTable.handleChange}
                errors={errors}
                readonly={readonly}
            />

            <h3 className="text-xl font-semibold">Localisation</h3>
            <Table
                columns={dayColumns}
                rows={rowsLocation}
                data={locationTable.data}
                onChange={locationTable.handleChange}
                errors={errors}
                readonly={readonly}
            />

            {current && (
                <div className="flex gap-3 mt-4">
                    <button onClick={onPrevious} className="px-4 py-2 border border-error text-white rounded">Retour</button>
                    <button onClick={onSubmit} className="px-4 py-2 bg-primary text-white rounded">Soumettre</button>
                </div>
            )}
        </div>
    );
}