import {z} from "zod";

export const CheckExistsResponseSchema = z.object({
    exists: z.boolean(),
    message: z.string().nullable(),
});

export type CheckExistsResponse = z.infer<typeof CheckExistsResponseSchema>;