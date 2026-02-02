import {z} from "zod";

export const TimesheetCreateResponseSchema = z.object({
    message: z.string(),
    preventEditing: z.boolean(),
    uuid: z.uuid(),
    employeeUuid: z.uuid()
});

export type TimesheetCreateResponse = z.infer<typeof TimesheetCreateResponseSchema>;
