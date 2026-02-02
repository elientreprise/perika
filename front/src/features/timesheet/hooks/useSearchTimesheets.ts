import { useState } from "react";
import {searchTimesheets} from "../services/timesheet.ts";
import type {TimesheetType} from "../types/TimesheetType.ts";
import type {TimesheetSearchParameters} from "../types/TimesheetSearchParameters.ts";



export function useSearchTimesheets() {
    const [timesheets, setTimesheets] = useState<TimesheetType[]>([]);
    const [loading, setLoading] = useState<boolean>(false);
    const [error, setError] = useState<string | null>(null);

    async function search(
        employeeUuid,
        parameters: TimesheetSearchParameters = {}
    ) {
        setLoading(true);
        setError(null);
        try {
            const response = await searchTimesheets(employeeUuid, parameters);

            if (response) {
                setTimesheets(response);
                setLoading(false)
            }

        } catch (err: any) {
            console.error("Search error:", err);
            setError(err.message || "Erreur lors de la recherche");
            setTimesheets([]);
            setLoading(false)
        }
    }

    return {
        timesheets,
        search,
        loading,
        error };
}
