import TimesheetValidateView from "./TimesheetValidateView.tsx";
import React from "react";
import {validateTimesheet} from "../../../services/timesheet.ts";
import {useFlash} from "../../../../../shared/hooks/useFlash.ts";
import type {ValidateTimesheetResponse} from "../../../types/ValidateTimesheetResponse.ts";


type Props = {
    timesheetUuid: string;
    isValidating: boolean;
    setIsValidating: (value: boolean) => void;
};
export default function TimesheetValidateContainer({
                                                    timesheetUuid,
                                                    isValidating,
                                                    setIsValidating
                                                 }: Readonly<Props>) {

    const { push } = useFlash();

    async function handleValidate() {
        try {

            setIsValidating(true);
            if (!isValidating) {
                const response: ValidateTimesheetResponse = await validateTimesheet(timesheetUuid)
                push(response.message, "success");
            }

        } catch (err: any) {
            console.error(err);
        }
        finally {
            setIsValidating(false)
        }

    }


    return (<TimesheetValidateView
            handleValidate={handleValidate}
            loading={isValidating}
        />
    );

}
