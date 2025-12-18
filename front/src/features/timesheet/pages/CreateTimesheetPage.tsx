
import {useTimesheetStore} from "../store/useTimesheetStore.ts";
import Step1Period from "../components/steps/Step1/Step1Period.tsx";
import Step2Details from "../components/steps/Step2/Step2Details.tsx";
import Step1PeriodResume from "../components/steps/Step1/Step1PeriodResume.tsx";
import TimesheetCommentForm from "../components/comment/Form/TimesheetCommentForm.tsx";
import React, {useState} from "react";

export default function CreateTimesheetPage() {
    const { step, next, previous, timesheet, update } = useTimesheetStore();
    const [comment, setComment] = useState<string>( "");

    function handleNext() {
        update({
            comments: [{comment: comment}]
        });
        next()
    }

    return (
        <section className={`flex flex-col gap-5 p-10 w-2/3`}>
                <div>
                    <div className="badge badge-warning">Brouillon</div>
                </div>
                <TimesheetCommentForm onChange={setComment} value={comment}/>
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
        </section>
    )
        ;

}
