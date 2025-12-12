import {z} from "zod";

export const CalculatePeriodResponseSchema = z.object({
    start: z.string(),
    end: z.string(),
});

export type CalculatePeriodResponse = z.infer<typeof CalculatePeriodResponseSchema>;
