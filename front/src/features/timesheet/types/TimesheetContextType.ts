import type {TimesheetType} from "./TimesheetType.ts";

export type TimesheetContextType = {
    timesheet: TimesheetType;
    setTimesheet: (value: TimesheetType) => void;
};