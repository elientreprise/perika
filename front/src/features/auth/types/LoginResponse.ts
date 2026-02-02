import {UserSchema} from "../../employee/types/UserType.ts";
import {z} from "zod";

export const LoginResponseSchema = z.object({
    message: z.string(),
    user: UserSchema,
});
export type LoginResponse = z.infer<typeof LoginResponseSchema>;