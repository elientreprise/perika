import {useTimesheet} from "../../../hooks/useTimesheet.ts";
import TimesheetResumeView from "./TimesheetResumeView.tsx";
import {NotFound} from "../../../../../shared/components/ui/NotFound.tsx";
import {useEffect, useState} from "react";
import {createComment} from "../../../services/timesheet.ts";
import {API_URL} from "../../../../../app/config/api.tsx";
import type {CommentType} from "../../../types/TimesheetType.ts";
import type {CommentCreateResponse} from "../../../types/CommentCreateResponse.ts";
import {CommentSchema} from "../../../types/TimesheetType.ts";


type Props = {
    employeeUuid: string;
    timesheetUuid: string;
};
export default function TimesheetResumeContainer({
                                                    employeeUuid,
                                                    timesheetUuid
                                                 }: Readonly<Props>) {

    const { timesheet, notFound } = useTimesheet(employeeUuid || '-',  timesheetUuid || '-');

    const [comment, setComment] = useState<string>( "");

    const [comments, setComments] = useState<CommentType[]>(timesheet?.comments || [])

    useEffect(() => {
        setComments(timesheet?.comments)
    }, [timesheet])

    async function handlePostComment() {
        try {
            const response: CommentCreateResponse = await createComment({comment: comment, propertyPath: "", timesheet: API_URL+'/timesheets/'+timesheetUuid})


            setComments((prev) => {
                return [response.comment, ...prev]
            })


        } catch (err: any) {
            console.error(err)
        } finally {
            setComment(null)
        }

    }

    if (notFound) {
        return (<NotFound/>)
    }

    if (timesheet) {
        return (<TimesheetResumeView
            timesheet={timesheet}
            comments={comments}
            comment={comment}
            handlePostComment={handlePostComment}
            setComment={setComment}
            />
        );
    }



    return (
        <div className="flex w-1/2 flex-col gap-4">
            <div className="skeleton h-12 w-full"></div>
            <div className="skeleton h-4 w-28"></div>
            <div className="skeleton h-4 w-full"></div>
            <div className="skeleton h-4 w-full"></div>
            <div className="skeleton h-[100vh] w-full"></div>
        </div>
    );
}
