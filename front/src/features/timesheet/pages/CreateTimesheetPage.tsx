
import {useTimesheetStore} from "../store/useTimesheetStore.ts";
import Step1Period from "../components/steps/Step1/Step1Period.tsx";
import Step2Details from "../components/steps/Step2/Step2Details.tsx";
import Step1PeriodResume from "../components/steps/Step1/Step1PeriodResume.tsx";
import TimesheetCommentForm from "../components/comment/Form/TimesheetCommentForm.tsx";
import React, {useState} from "react";

export default function CreateTimesheetPage() {
    const { step, next, previous, timesheet, update } = useTimesheetStore();
    const [comment, setComment] = useState<string>( "");

    const [displayComment, setDisplayComment] = useState<boolean>( false);

    function handleNext() {
        update({
            comments: [{comment: comment}]
        });
        next()
    }

    function handleDisplayComment() {
        setDisplayComment(!displayComment);
    }

    return (
        <section className={`flex gap-5`}>
            <div className={'flex flex-col gap-5  p-10'}>
                <div>
                    <div className="badge badge-warning">Brouillon</div>
                </div>


                {step === 1 ? (
                    <Step1Period
                        current={step === 1}
                        onNext={handleNext}
                    />
                ) : (
                    <Step1PeriodResume timesheet={timesheet}/>
                )}


                <div
                    className={`transition-opacity duration-300 ${
                        step === 2 ? "opacity-100 pointer-events-auto" : "opacity-25 pointer-events-none"
                    }`}
                >
                    <Step2Details
                        current={step === 2}
                        onNext={handleNext}
                        onPrevious={previous}
                    />
                </div>
            </div>
            <div className={"w-1/3 rounded bg-base-300 p-2"}>
                <div className={"w-full flex justify-end"}>
                    <button onClick={handleDisplayComment} className={"btn btn-xs text-xs flex items-center gap-3"}>
                        Ajouter commentaire
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" strokeWidth={1.5}
                             stroke="currentColor" className="size-4">
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
                            console.log(comment)
                        })
                    }
                    <div className="chat chat-start text-xs">
                        <div className="chat-header">
                            Obi-Wan Kenobi
                            <time className="text-xs opacity-50">12:45</time>
                        </div>
                        <div className="chat-bubble">You were the Chosen One!</div>
                        <div className="chat-footer opacity-50">lu.</div>
                    </div>
                    <div className="chat chat-end text-xs">
                        <div className="chat-header">
                            Anakin
                            <time className="text-xs opacity-50">12:46</time>
                        </div>
                        <div className="chat-bubble">I hate you!</div>
                        <div className="chat-footer opacity-50">nouveau.</div>
                    </div>
                </div>

            </div>
        </section>
    )
        ;

}
