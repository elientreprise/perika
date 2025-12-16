
import {columnRenderers} from "./renderers.tsx";
import type {ColumnDescriptor} from "../../../../shared/types/ColumnDescriptor.ts";
import {DaysOfWeek} from "../../../../shared/types/DaysOfWeekType.ts";


export const dayColumns: ColumnDescriptor[] = DaysOfWeek.map((day) => ({
    key: day.key,
    label: day.label,
}));

const totalColumn: ColumnDescriptor = {
    key: "total",
    label: "Total",
    disabled: true,
    render: columnRenderers.total
} as ColumnDescriptor;

const projectColumns: ColumnDescriptor[] = [
    { key: "project", label: "Projet", render: columnRenderers.project } as ColumnDescriptor,
    { key: "activity", label: "Activité", render: columnRenderers.activity } as ColumnDescriptor,
    { key: "typeSource", label: "Type source", render: columnRenderers.typeSource } as ColumnDescriptor,
    { key: "category", label: "Catégorie", render: columnRenderers.category } as ColumnDescriptor
];

export const columnsWithTotal = [...dayColumns, totalColumn];
export const columnsWithProject = [...projectColumns, ...columnsWithTotal];