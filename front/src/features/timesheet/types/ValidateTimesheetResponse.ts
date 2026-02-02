import {z} from "zod";

export const ValidateTimesheetResponseSchema = z.object({
    message: z.string(),
    uuid: z.string()
});

export type ValidateTimesheetResponse = z.infer<typeof ValidateTimesheetResponseSchema>;
