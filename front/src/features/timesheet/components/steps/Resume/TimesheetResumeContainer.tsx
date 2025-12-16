import {useTimesheet} from "../../../hooks/useTimesheet.ts";
import TimesheetResumeView from "./TimesheetResumeView.tsx";
import {NotFound} from "../../../../../shared/components/ui/NotFound.tsx";
import {useState} from "react";

type Props = {
    employeeUuid: string;
    timesheetUuid: string;
};
export default function TimesheetResumeContainer({
                                                    employeeUuid,
                                                    timesheetUuid
                                                 }: Readonly<Props>) {

    const { timesheet, notFound } = useTimesheet(employeeUuid || '-',  timesheetUuid || '-');

    const [comment, setComment] = useState<string>( "");

    const [displayComment, setDisplayComment] = useState<boolean>( false);

    function handleDisplayComment() {
        setDisplayComment(!displayComment)
    }

    if (notFound) {
        return (<NotFound/>)
    }

    if (timesheet) {
        return (<TimesheetResumeView
            timesheet={timesheet}
            handleDisplayComment={handleDisplayComment}
            comment={comment}
            setComment={setComment}
            displayComment={displayComment}
            setDisplayComment={setDisplayComment}
        />);
    }



    return (
        <div className="flex w-1/2 flex-col gap-4">
            <div className="skeleton h-12 w-full"></div>
            <div className="skeleton h-4 w-28"></div>
            <div className="skeleton h-4 w-full"></div>
            <div className="skeleton h-4 w-full"></div>
            <div className="skeleton h-[100vh] w-full"></div>
        </div>
    );
}
