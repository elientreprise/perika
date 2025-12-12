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
        columnsWithTotal,
        columnsWithProject,
        rowsProject,
        rowsRest,
        rowsLocation,
        hoursEntries,
        projectEntries,
        restEntries,
        locationEntries,
        totalsByDay,
        totalsGlobalByDay,
    } = useTimesheetFormData({
        readonly: true,
        initialData: timesheet
    });


    return (
        <Step2DetailsView
            current={false}
            onPrevious={()=>{}}
            columnsWithProject={columnsWithProject}
            rowsProject={rowsProject}
            projectEntries={projectEntries}
            columnsWithTotal={columnsWithTotal}
            hoursEntries={hoursEntries}
            totalsByDay={totalsByDay}
            totalsGlobalByDay={totalsGlobalByDay}
            rowsRest={rowsRest}
            restEntries={restEntries}
            rowsLocation={rowsLocation}
            locationEntries={locationEntries}
            readonly={true}
        />
    );
}
