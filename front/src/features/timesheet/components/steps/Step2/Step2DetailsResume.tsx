import type {TimesheetType} from "../../../types/TimesheetType.ts";
import {useTimesheetFormData} from "../../../hooks/useTimesheetFormData.ts";
import Step2DetailsView from "./Step2DetailsView.tsx";


type Props = {
    timesheet: TimesheetType;
};
export default function Step2DetailsResume({
                                               timesheet
                                           }: Readonly<Props>) {

    const {
        projectTable,
        restTable,
        locationTable,
        leavesTable,
    } = useTimesheetFormData({ timesheet });

    return (
        <Step2DetailsView
            current={false}
            onPrevious={() => {}}
            locationTable={locationTable}
            projectTable={projectTable}
            restTable={restTable}
            leavesTable={leavesTable}
            errors={{}}
            readonly={true}
        />
    )
}
