import { useState, useEffect } from "react";
import type {TimesheetType} from "../types/TimesheetType.ts";
import {getTimesheetByEmployee} from "../services/timesheet.ts";
import {TimesheetSchema} from "../types/TimesheetType.ts";

export function useTimesheet(employeeUuid: string, timesheetUuid: string) {
    const [timesheet, setTimesheet] = useState<TimesheetType>();
    const [notFound, setNotFound] = useState<boolean>(false);

    useEffect(() => {

        async function load() {
            setNotFound(false)
            try {
                const response = await getTimesheetByEmployee(employeeUuid, timesheetUuid);
                if (response) {
                    const parsed = TimesheetSchema.parse(response)
                    setTimesheet(parsed)
                }

            } catch (err: any) {
                console.log(err)
                setNotFound(true)
            }
        }

        load();


    }, [employeeUuid, timesheetUuid]);

    return { timesheet, notFound };
}
