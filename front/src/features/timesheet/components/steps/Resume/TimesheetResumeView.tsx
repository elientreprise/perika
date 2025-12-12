import Step1PeriodResume from "../Step1/Step1PeriodResume.tsx";
import Step2DetailsResume from "../Step2/Step2DetailsResume.tsx";
import type {TimesheetType} from "../../../types/TimesheetType.ts";

type Props = {
    timesheet: TimesheetType;
};
export default function TimesheetResumeView({
                                                timesheet
                                            }: Readonly<Props>) {
    return (
        <>
            <Step1PeriodResume timesheet={timesheet}/>
            <Step2DetailsResume timesheet={timesheet} />
        </>
    );
}
