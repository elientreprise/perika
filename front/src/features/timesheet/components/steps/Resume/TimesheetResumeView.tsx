import Step1PeriodResume from "../Step1/Step1PeriodResume.tsx";
import Step2DetailsResume from "../Step2/Step2DetailsResume.tsx";
import type {CommentType, TimesheetType} from "../../../types/TimesheetType.ts";
import TimesheetCommentForm from "../../comment/Form/TimesheetCommentForm.tsx";
import React from "react";
import Chat from "../../../../../shared/components/ui/Chat.tsx";

type Props = {
    timesheet: TimesheetType;
    setComment: (value:string) => void;
    comment: string,
    handlePostComment: () => void;
    comments: CommentType[];
};
export default function TimesheetResumeView({
                                                timesheet,
                                                setComment,
                                                comment,
                                                handlePostComment,
                                                comments
                                            }: Readonly<Props>) {
    return (
        <div className={"flex gap-5"}>
            <div className={'flex pointer-events-none flex-col gap-5 p-10'}>
                <Step1PeriodResume timesheet={timesheet}/>
                <Step2DetailsResume timesheet={timesheet}/>
            </div>

            <div className={"w-1/3 rounded bg-base-300 p-10"}>
                <div className={"h-[35vh] overflow-y-auto flex flex-col-reverse break-all"}>
                    {
                        comments?.map(comment => {
                            if (timesheet.employee.uuid === comment.createdBy.uuid) {
                                return (
                                    <Chat
                                        formattedCreatedAt={comment.formattedCreatedAt}
                                        fullName={timesheet.employee.fullName}
                                        text={comment.comment}
                                        translateStatus={comment.translateStatus}
                                    />
                                )
                            } else {
                                return (
                                    <Chat
                                        formattedCreatedAt={comment.formattedCreatedAt}
                                        fullName={timesheet.employee.fullName}
                                        text={comment.comment}
                                        translateStatus={comment.translateStatus}
                                        chatEnd={true}
                                    />
                                )
                            }
                        })
                    }
                </div>
                <div className={"divider"}></div>
                <div className={"p-3 flex flex-col"}>
                    <TimesheetCommentForm onChange={setComment} value={comment}/>
                    <div className={"w-full flex justify-end mt-3"}>
                        <span className={"link text-xs cursor-pointer flex items-center gap-2"} onClick={handlePostComment}>
                            Ajouter commentaire
                            <svg className="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                              <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>

                        </span>
                    </div>
                </div>
            </div>
        </div>
    );
}
