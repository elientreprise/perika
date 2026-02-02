import {z} from "zod";

export const LoginPayloadSchema = z.object({
    email: z.email(),
    password: z.string().min(6),
});
export type LoginPayload = z.infer<typeof LoginPayloadSchema>;