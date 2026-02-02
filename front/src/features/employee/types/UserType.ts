import {z} from "zod";

export const UserSchema: z.ZodType<any> = z.lazy(() =>
    z.object({
        uuid: z.uuid(),
        email: z.email(),
        firstName: z.string().nullable(),
        lastName: z.string().nullable(),
        position: z.string().nullable(),
        salary: z.number().nullable(),
        hireDate: z.preprocess(
            (v) => (typeof v === "string" || v instanceof Date ? new Date(v) : v),
            z.date()
        ).nullable(),
        phoneNumber: z.string().nullable(),
        birthDate: z.string().nullable(),
        manager: UserSchema.nullable(),
        fullName: z.string().nullable(),
        seniority: z.string().nullable(),
    })
);

export type UserType = z.infer<typeof UserSchema>;
