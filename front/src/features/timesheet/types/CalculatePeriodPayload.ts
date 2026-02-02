import { z } from "zod";

export const CalculatePeriodPayloadSchema = z.object({
    date: z.string().min(1, "La date est obligatoire"),
});

export type CalculatePeriodPayload = z.infer<typeof CalculatePeriodPayloadSchema>;
