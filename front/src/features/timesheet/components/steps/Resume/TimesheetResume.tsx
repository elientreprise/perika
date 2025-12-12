import TimesheetResumeContainer from "./TimesheetResumeContainer.tsx";

type Props = {
    employeeUuid: string;
    timesheetUuid: string;
};
export default function TimesheetResume(props: Readonly<Props>) {


    return (
       <TimesheetResumeContainer {...props} />
    );
}
