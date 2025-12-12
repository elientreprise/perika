
import TimesheetResume from "../components/steps/Resume/TimesheetResume.tsx";
import {useParams} from "react-router-dom";


export default function ResumeTimesheetPage() {

    const { employeeUuid, timesheetUuid } = useParams();



    return (
        <section className={`space-y-4 w-full pointer-events-none`}>
            <TimesheetResume employeeUuid={employeeUuid} timesheetUuid={timesheetUuid}  />
        </section>
    );

}
