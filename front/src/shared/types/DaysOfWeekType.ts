import type {ColumnDescriptor} from "./ColumnDescriptor.ts";

export type DaysOfWeekKey = typeof DaysOfWeek[number]["key"];
export const DaysOfWeek: ColumnDescriptor[] = [
    { key: "sunday", label: "Dimanche" },
    { key: "monday", label: "Lundi" },
    { key: "tuesday", label: "Mardi" },
    { key: "wednesday", label: "Mercredi" },
    { key: "thursday", label: "Jeudi" },
    { key: "friday", label: "Vendredi" },
    { key: "saturday", label: "Samedi" },
] as const;