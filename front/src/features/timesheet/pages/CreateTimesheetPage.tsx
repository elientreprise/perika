
import {useTimesheetStore} from "../store/useTimesheetStore.ts";
import Step1Period from "../components/steps/Step1/Step1Period.tsx";
import Step2Details from "../components/steps/Step2/Step2Details.tsx";
import Step1PeriodResume from "../components/steps/Step1/Step1PeriodResume.tsx";

export default function CreateTimesheetPage() {
    const { step, next, previous, timesheet } = useTimesheetStore();

    return (
        <section className={`space-y-4 w-1/2`}>

            {step === 1 ? (
                <Step1Period
                    current={step === 1}
                    onNext={next}
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
                    onNext={next}
                    onPrevious={previous}
                />
            </div>

        </section>
    );

}
