import {z} from "zod";

export const CheckExistsPayloadSchema = z.object({
    date: z.string().min(1, "La date est obligatoire"),
    employee: z.url()
});

export type CheckExistsPayload = z.infer<typeof CheckExistsPayloadSchema>;