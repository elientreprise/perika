import { z } from "zod";
import {UserSchema} from "../../employee/types/UserType.ts";


export const CheckAuthResponseSchema = z.object({
    user: UserSchema,
});

export type CheckAuthResponse = z.infer<typeof CheckAuthResponseSchema>;