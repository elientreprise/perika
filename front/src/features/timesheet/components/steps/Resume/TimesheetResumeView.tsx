import Step1PeriodResume from "../Step1/Step1PeriodResume.tsx";
import Step2DetailsResume from "../Step2/Step2DetailsResume.tsx";
import type {CommentType, TimesheetType} from "../../../types/TimesheetType.ts";
import TimesheetCommentForm from "../../comment/Form/TimesheetCommentForm.tsx";
import React from "react";
import Chat from "../../../../../shared/components/ui/Chat.tsx";
import {LoaderButton} from "../../../../../shared/components/ui/LoaderButton.tsx";
import TimesheetValidate from "../Validate/TimesheetValidate.tsx";


// todo typer les params
type Props = {
    timesheet: TimesheetType;
    setComment: (value:string) => void;
    comment: string,
    handlePostComment: () => void;
    comments: CommentType[];
    ref;
    onScroll;
    loadingComment: boolean;
    totalComments: number;
    isValidating: boolean;
    setIsValidating: (value: boolean) => void;
};
export default function
    TimesheetResumeView({
                                                timesheet,
                                                setComment,
                                                comment,
                                                handlePostComment,
                                                comments,
                                                ref,
                                                onScroll,
                                                loadingComment,
                                                totalComments,
                                                isValidating,
                                                setIsValidating
                                            }: Readonly<Props>) {
    console.log(timesheet.translateStatus)

    return (
        <div className={"flex gap-5"}>
            <div className={'flex pointer-events-none flex-col gap-5 p-10'}>
                <Step1PeriodResume timesheet={timesheet}/>
                <Step2DetailsResume timesheet={timesheet}/>
            </div>

            <div className={"w-1/3 rounded bg-base-300 p-10"}>
                {comments.length > 0 ? (<div
                    className={"w-full text-xs mb-5 flex justify-end"}>{comments.length}/{totalComments}</div>) : ""}
                {loadingComment ? (
                    <div className={"flex w-full items-center justify-center"}>
                        <LoaderButton/>
                    </div>
                ) : ''}
                {comments && comments.length > 0 ? (
                    <div ref={ref} onScroll={onScroll} className={"h-[35vh] overflow-y-auto flex flex-col break-all"}>
                        {
                            comments?.map(comment => {

                                if (timesheet.employee.uuid === comment.createdBy.uuid) {
                                    return (
                                        <Chat
                                            formattedCreatedAt={comment.formattedCreatedAt}
                                            fullName={comment.createdBy.fullName}
                                            text={comment.comment}
                                            translateStatus={comment.translateStatus}
                                        />
                                    )
                                } else {
                                    return (
                                        <Chat
                                            formattedCreatedAt={comment.formattedCreatedAt}
                                            fullName={comment.createdBy.fullName}
                                            text={comment.comment}
                                            translateStatus={comment.translateStatus}
                                            chatEnd={true}
                                        />
                                    )
                                }
                            })
                        }
                    </div>) : (<span className={"text-xs opacity-60"}> Aucun commentaire.</span>)}
                <div className={"divider"}></div>
                <div className={"p-3 flex flex-col"}>
                    <TimesheetCommentForm onChange={setComment} value={comment}/>
                    <div className={"w-full flex justify-end mt-3"}>
                        <span className={"link text-xs cursor-pointer flex items-center gap-2"}
                              onClick={handlePostComment}>
                            Ajouter commentaire
                            <svg className="w-4 h-4 text-gray-800 dark:text-white" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                 viewBox="0 0 24 24">
                              <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2"
                                    d="M12 7.757v8.486M7.757 12h8.486M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>

                        </span>
                    </div>
                </div>
                {/*Todo: envoyer dans la response du timesheet isValidated*/}
                {timesheet.valid ? "" : (
                    <>
                        <div className={"divider"}></div>
                        <div className={"flex justify-between mt-5"}>
                            <TimesheetValidate
                                timesheetUuid={timesheet.uuid}
                                isValidating={isValidating}
                                setIsValidating={setIsValidating}
                            />
                            <button className={"btn btn-error btn-xs flex gap-2"} disabled={isValidating}>Refuser la
                                feuille
                                de temps <svg
                                    className="w-4 h-4 text-gray-800" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round"
                                          strokeWidth="2"
                                          d="M6 18 17.94 6M18 18 6.06 6"/>
                                </svg>
                            </button>
                        </div>
                    </>

                )}

            </div>
        </div>
    );
}
