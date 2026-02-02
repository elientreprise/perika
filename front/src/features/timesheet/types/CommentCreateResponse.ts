import {z} from "zod";
import {CommentSchema} from "./TimesheetType.ts";

export const CommentCreateResponseSchema = z.object({
    message: z.string(),
    comment: CommentSchema
});

export type CommentCreateResponse = z.infer<typeof CommentCreateResponseSchema>;
