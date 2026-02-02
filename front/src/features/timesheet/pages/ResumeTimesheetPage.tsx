import TimesheetResume from "../components/steps/Resume/TimesheetResume.tsx";
import {useParams} from "react-router-dom";
import React from "react";


export default function ResumeTimesheetPage() {

    const { employeeUuid, timesheetUuid } = useParams();

    return (
        <section>
            <TimesheetResume employeeUuid={employeeUuid} timesheetUuid={timesheetUuid}/>
        </section>
    );

}
