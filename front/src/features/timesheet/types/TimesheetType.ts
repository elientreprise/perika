import { z } from "zod";
import {UserSchema} from "../../employee/types/UserType.ts";


export const LocationSchema = z.object({
    am: z.string().nullable(),
    pm: z.string().nullable(),
}).nullable();

export const WorkDaySchema = z.object({
    day: z.string(),
    projectTime: z.number().nullable(),
    isMinDailyRestMet: z.boolean().nullable(),
    isWorkShiftValid: z.boolean().nullable(),
    workedMoreThanHalfDay: z.boolean().nullable(),
    lunchBreak: z.number().nullable(),
    location: LocationSchema
});

export const TimesheetEmployeeSchema: z.ZodType<any> = z.lazy(() =>
    z.object({
        uuid: z.uuid(),
        firstName: z.string().nullable(),
        lastName: z.string().nullable(),
        position: z.string().nullable(),
        hireDate: z.preprocess(
            (v) => (typeof v === "string" || v instanceof Date ? new Date(v) : v),
            z.date()
        ).nullable(),
        manager: TimesheetEmployeeSchema.nullable(),
        fullName: z.string().nullable(),
        seniority: z.string().nullable(),
    })
);

export const CommentSchema = z.object({
    comment: z.string(),
    propertyPath: z.string().nullable(),
    createdBy: TimesheetEmployeeSchema,
    createdAt: z.string(),
});

export const TimesheetSchema = z.object({
    uuid: z.string().nullable(),
    employee: TimesheetEmployeeSchema.nullable(),
    startPeriod: z.string(),
    endPeriod: z.string(),
    workDays: z.array(WorkDaySchema),
    comments: z.array(CommentSchema),
    status: z.string()
});


export type CommentType = z.infer<typeof CommentSchema>;
export type TimesheetEmployeeType = z.infer<typeof TimesheetEmployeeSchema>;
export type LocationType = z.infer<typeof LocationSchema>;
export type WorkDayType = z.infer<typeof WorkDaySchema>;
export type TimesheetType = z.infer<typeof TimesheetSchema>;
