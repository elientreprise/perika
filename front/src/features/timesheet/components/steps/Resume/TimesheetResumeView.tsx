import Step1PeriodResume from "../Step1/Step1PeriodResume.tsx";
import Step2DetailsResume from "../Step2/Step2DetailsResume.tsx";
import type {TimesheetType} from "../../../types/TimesheetType.ts";
import TimesheetCommentForm from "../../comment/Form/TimesheetCommentForm.tsx";
import React from "react";

type Props = {
    timesheet: TimesheetType;
};
export default function TimesheetResumeView({
                                                timesheet,
                                                handleDisplayComment,
                                                displayComment,
                                                setComment,
                                                comment,
                                            }: Readonly<Props>) {
    console.log(timesheet.comments)
    return (
        <div className={"flex gap-5"}>
            <div className={'flex pointer-events-none flex-col gap-5  p-10'}>
                <Step1PeriodResume timesheet={timesheet}/>
                <Step2DetailsResume timesheet={timesheet}/>
            </div>

            <div className={"w-1/3 rounded bg-base-300 p-2"}>
                <div className={"w-full flex justify-end mb-2"}>
                    <button onClick={handleDisplayComment} className={"btn btn-xs bg-transparent border-transparent text-xs flex items-center gap-1"}>
                        Ajouter commentaire
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                             stroke="currentColor" className="size-5">
                            <path strokeLinecap="round" strokeLinejoin="round"
                                  d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                    </button>
                </div>

                {displayComment && <TimesheetCommentForm onChange={setComment} value={comment}/>}

                <div className="divider"></div>
                <div>
                    {
                        timesheet.comments.map(comment => {
                            if (timesheet.employee.uuid === comment.createdBy.uuid) {
                                return (
                                    <div className="chat chat-start text-xs">
                                        <div className="chat-header">
                                            {comment.createdBy.fullName}
                                            <time className="text-xs opacity-50">{comment.createdAt}</time>
                                        </div>
                                        <div className="chat-bubble">{comment.comment}</div>
                                        <div className="chat-footer opacity-50">lu.</div>
                                    </div>
                                )
                            } else {
                               return (
                                   <div className="chat chat-end text-xs">
                                       <div className="chat-header">
                                           {comment.createdBy.fullName}
                                           <time className="text-xs opacity-50">{comment.createdAt}</time>
                                       </div>
                                       <div className="chat-bubble">{comment.comment}</div>
                                       <div className="chat-footer opacity-50">
                                           <div className="status status-info animate-bounce"></div>
                                       </div>
                                   </div>
                               )
                            }
                        })
                    }
                </div>

            </div>
        </div>
    );
}
