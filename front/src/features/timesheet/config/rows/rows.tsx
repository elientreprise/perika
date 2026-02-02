
import {rowRenderers} from "./renderers.tsx";
import type {RowDescriptor} from "../../../../shared/types/RowDescriptor.ts";
import type {ColumnDescriptor} from "../../../../shared/types/ColumnDescriptor.ts";
import {DaysOfWeek} from "../../../../shared/types/DaysOfWeekType.ts";
import {TimesheetLeaves} from "../../types/TimesheetLeavesType.ts";

export const rowsProject: RowDescriptor[] = [
    { key: "projectRow", label: "" }
];

export const rowsRest: RowDescriptor[] = [
    { key: "isMinDailyRestMet", label: "Repos 11h respecté", render: rowRenderers.isMinDailyRestMet },
    { key: "isWorkShiftValid", label: "Horaires corrects", render: rowRenderers.isWorkShiftValid },
    { key: "workedMoreThanHalfDay", label: "Plus d'une demi-journée", render: rowRenderers.workedMoreThanHalfDay },
    { key: "lunchBreak", label: "Pause déjeuner" }
];

export const rowsLocation: RowDescriptor[] = [
    { key: "am", label: "Matin", render: rowRenderers.am },
    { key: "pm", label: "Après-midi", render: rowRenderers.pm }
];

export const rowsLeaves: RowDescriptor[] = TimesheetLeaves.map((leave) => ({
    key: leave.key,
    label: leave.label,
}));


export const rowsTotal = [

]