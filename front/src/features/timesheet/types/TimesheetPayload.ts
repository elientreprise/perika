import { z } from "zod";
import {WorkDaySchema} from "./TimesheetType.ts";


export const TimesheetPayloadSchema = z.object({
    uuid: z.string().nullable(),
    employee: z.url(),
    startPeriod: z.string(),
    endPeriod: z.string(),
    workDays: z.array(WorkDaySchema),
});

export type TimesheetPayloadType = z.infer<typeof TimesheetPayloadSchema>;
