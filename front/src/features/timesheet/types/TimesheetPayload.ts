import { z } from "zod";
import {WorkDaySchema} from "./TimesheetType.ts";


export const CommentPayloadSchema = z.object({
    comment: z.string(),
    propertyPath: z.string().nullable(),
    timesheet: z.url().nullable()
});

export const TimesheetPayloadSchema = z.object({
    uuid: z.string().nullable(),
    employee: z.url(),
    startPeriod: z.string(),
    endPeriod: z.string(),
    workDays: z.array(WorkDaySchema),
    comments: z.array(CommentPayloadSchema)
});



export type TimesheetPayloadType = z.infer<typeof TimesheetPayloadSchema>;
export type CommentPayloadType = z.infer<typeof CommentPayloadSchema>;