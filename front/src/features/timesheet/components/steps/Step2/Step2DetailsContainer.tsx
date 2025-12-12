import {useFlash} from "../../../../../shared/hooks/useFlash.ts";
import {create} from "../../../services/timesheet.ts";
import Step2DetailsView from "./Step2DetailsView.tsx";
import {useTimesheetStore} from "../../../store/useTimesheetStore.ts";
import type {WorkDayType} from "../../../types/TimesheetType.ts";
import {toBool} from "../../../../../shared/utils/ToBool.ts";
import {useNavigate} from "react-router-dom";
import {useTimesheetFormData} from "../../../hooks/useTimesheetFormData.ts";
import {useState} from "react";
import type {FieldErrors} from "../../../types/ValidationError.ts";
import {buildErrorKey, parsePropertyPath, parseValidationErrors} from "../../../utils/parseValidationErrors.ts";

type Props = {
    onNext: () => void;
    onPrevious: () => void;
    current: boolean,
};
export default function Step2DetailsContainer({
                                                  onNext,
                                                  onPrevious,
                                                  current
                                              }: Readonly<Props>) {

    const { timesheet, update } = useTimesheetStore();
    const navigate = useNavigate();

    const [fieldErrors, setFieldErrors] = useState<FieldErrors>({});

    const { push } = useFlash();

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
        changeHours,
        changeProjects,
        changeRests,
        changeLocations,
        totalsByDay,
        totalsGlobalByDay,
    } = useTimesheetFormData({ readonly: false });
    const onSubmit = async () => {

        const updatedWorkDays: WorkDayType[] = timesheet.workDays.map((workDay) => {
            const dayKey = workDay.day;

            return {
                ...workDay,
                isWorkShiftValid: restEntries.isWorkShiftValid ? toBool(restEntries.isWorkShiftValid?.[dayKey]) : null,
                lunchBreak: restEntries.lunchBreak?.[dayKey] || null,
                workedMoreThanHalfDay: restEntries.workedMoreThanHalfDay ? toBool(restEntries.workedMoreThanHalfDay?.[dayKey]) : null,
                location: {
                    am: locationEntries.am?.[dayKey] === 0 ? "" : locationEntries.am?.[dayKey] || null,
                    pm: locationEntries.pm?.[dayKey] === 0 ? "" : locationEntries.pm?.[dayKey] || null,
                },
                projectTime: projectEntries.projectRow?.[dayKey] || 0,
                isMinDailyRestMet: restEntries.isMinDailyRestMet ? toBool(restEntries.isMinDailyRestMet?.[dayKey]) : null,
            } as WorkDayType;
        });

        update({
            workDays: updatedWorkDays,
        });

        const finalTimesheet = useTimesheetStore.getState?.().timesheet;

        try {
            const response = await create(finalTimesheet);
            push("Timesheet créé avec succès !", "success");
            navigate(
                `/finance/employees/${response.employeeUuid}/timesheets/${response.uuid}`,
            )

        } catch (error : any) {
            if (error.response?.data?.violations) {
                const errors = parseValidationErrors(error.response.data.violations);

                const tableErrors: FieldErrors = {};

                for (const [propertyPath, message] of Object.entries(errors)) {
                    const parsed = parsePropertyPath(propertyPath);
                    if (parsed) {
                        const { dayIndex, field, subField } = parsed;
                        const errorKey = buildErrorKey(dayIndex, subField || field);
                        tableErrors[errorKey] = message;
                    }

                    push(message, "error");
                }

                setFieldErrors(tableErrors);
            } else {
                push("Une erreur est survenue lors de la création du timesheet", "error");
            }
        }

    }

    return (
        <Step2DetailsView
            current={current}
            onPrevious={onPrevious}
            columnsWithProject={columnsWithProject}
            rowsProject={rowsProject}
            projectEntries={projectEntries}
            changeProjects={changeProjects}
            columnsWithTotal={columnsWithTotal}
            hoursEntries={hoursEntries}
            changeHours={changeHours}
            totalsByDay={totalsByDay}
            totalsGlobalByDay={totalsGlobalByDay}
            rowsRest={rowsRest}
            restEntries={restEntries}
            changeRests={changeRests}
            rowsLocation={rowsLocation}
            locationEntries={locationEntries}
            changeLocations={changeLocations}
            onSubmit={onSubmit}
            fieldErrors={fieldErrors}
        />
    )
}