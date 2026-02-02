import {useFlash} from "../../../../../shared/hooks/useFlash.ts";
import {create} from "../../../services/timesheet.ts";
import Step2DetailsView from "./Step2DetailsView.tsx";
import {useTimesheetStore} from "../../../store/useTimesheetStore.ts";
import {toBool} from "../../../../../shared/utils/ToBool.ts";
import {useNavigate} from "react-router-dom";
import {useTimesheetFormData} from "../../../hooks/useTimesheetFormData.ts";
import {useState} from "react";
import type {FieldErrors} from "../../../types/ValidationError.ts";
import { parseValidationErrors} from "../../../utils/parseValidationErrors.ts";
import type {WorkDayType} from "../../../types/TimesheetType.ts";

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
        projectTable,
        restTable,
        locationTable,
        leavesTable,
    } = useTimesheetFormData({ timesheet });


    const onSubmit = async () => {
        setFieldErrors({});

        const updatedWorkDays: WorkDayType[] = timesheet.workDays.map((workDay) => {
            const dayKey = workDay.day;

            return {
                ...workDay,
                projectTime: projectTable.data.projectRow?.[dayKey] || 0,
                isMinDailyRestMet: toBool(restTable.data.isMinDailyRestMet?.[dayKey]),
                isWorkShiftValid: toBool(restTable.data.isWorkShiftValid?.[dayKey]),
                workedMoreThanHalfDay: toBool(restTable.data.workedMoreThanHalfDay?.[dayKey]),
                lunchBreak: restTable.data.lunchBreak?.[dayKey] || null,
                location: {
                    am: locationTable.data.am?.[dayKey] || null,
                    pm: locationTable.data.pm?.[dayKey] || null,
                },
            } as WorkDayType;
        });

        update({ workDays: updatedWorkDays });
        
        try {
            const response = await create(useTimesheetStore.getState().timesheet);
            push("Timesheet créé avec succès !", "success");
            navigate(`/finance/employees/${response.employeeUuid}/timesheets/${response.uuid}`);
        } catch (error: any) {
            if (error.response?.data?.violations) {
                const parsedErrors = parseValidationErrors(error.response.data.violations);
                setFieldErrors(parsedErrors);

                for (const message of Object.values(parsedErrors)) {
                    push(message, "error");
                }
            }
        }
    };

    return (
        <Step2DetailsView
            current={current}
            onPrevious={onPrevious}
            errors={fieldErrors}
            locationTable={locationTable}
            projectTable={projectTable}
            restTable={restTable}
            leavesTable={leavesTable}
            onSubmit={onSubmit}
            readonly={false}
        />
    )
}