import { create } from "zustand";
import type {TimesheetEmployeeType, TimesheetType, WorkDayType} from "../types/TimesheetType.ts";
import {DaysOfWeek} from "../../../shared/types/DaysOfWeekType.ts";

function createEmptyTimesheet(): TimesheetType {
    const workDays: WorkDayType[] = DaysOfWeek.map(day => ({
        day: day.key,
        projectTime: 0,
        isMinDailyRestMet: null,
        isWorkShiftValid: null,
        workedMoreThanHalfDay: null,
        lunchBreak: 0,
        location: null,
    } as WorkDayType));

    return {
        employee: {
            uuid: null,
            firstName: null,
            lastName: null,
            position: null,
            hireDate: null,
            manager: null,
            fullName: null,
            seniority: null,
        } as TimesheetEmployeeType,
        startPeriod: "",
        endPeriod: "",
        workDays,
    };
}

type TimesheetStore = {
    step: number;
    timesheet: TimesheetType;

    next: () => void;
    previous: () => void;
    update: (partial: Partial<TimesheetType>) => void;
    reset: () => void;
};

export const useTimesheetStore = create<TimesheetStore>((set) => ({
    step: 1,
    timesheet: createEmptyTimesheet(),

    next: () => set((s) => ({ step: s.step + 1 })),
    previous: () => set((s) => ({ step: s.step - 1 })),

    update: (partial) =>
        set((s) => ({
            timesheet: { ...s.timesheet, ...partial }
        })),

    reset: () =>
        set({
            step: 1,
            timesheet: createEmptyTimesheet(),
        }),

}));
