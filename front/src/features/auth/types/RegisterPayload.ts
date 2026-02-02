import {z} from "zod";

export const RegisterPayloadSchema = z.object({
    email: z.email(),
    password: z.string().min(6),
});
export type RegisterPayload = z.infer<typeof RegisterPayloadSchema>;