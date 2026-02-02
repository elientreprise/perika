import { useTableData } from "../../../shared/hooks/useTableData.ts";
import {columnsWithProject, columnsWithTotal, dayColumns} from "../config/columns/columns.tsx";
import {rowsLeaves, rowsLocation, rowsProject, rowsRest} from "../config/rows/rows.tsx";
import type {EntriesTable} from "../../../shared/types/EntriesTable.ts";
import {useEffect, useState} from "react";
import type {TimesheetType} from "../types/TimesheetType.ts";
import {roundFloat} from "../../../shared/utils/RoundNumber.ts";

type UseTimesheetFormDataOptions = {
    readonly?: boolean;
    timesheet?: TimesheetType;
};

export function useTimesheetFormData(options: UseTimesheetFormDataOptions = {}) {
    const { timesheet } = options;

    const [initialProjectData, setInitialProjectData] = useState<EntriesTable>({})
    const [initialRestData, setInitialRestData] = useState<EntriesTable>({})
    const [initialLocationData, setInitialLocationData] = useState<EntriesTable>({})
    const [initialLeavesData, setInitialLeavesData] = useState<EntriesTable>({})

    const projectTable = useTableData({ rows: rowsProject, columns: columnsWithProject, initialData: initialProjectData });
    const restTable = useTableData({ rows: rowsRest, columns: dayColumns, initialData: initialRestData });
    const locationTable = useTableData({ rows: rowsLocation, columns: dayColumns, initialData: initialLocationData });
    const leavesTable = useTableData({ rows: rowsLeaves, columns: columnsWithTotal, initialData: initialLeavesData });

    useEffect(() => {

        if (!timesheet?.workDays) return;

        const projectData: EntriesTable = { projectRow: {} };
        const restData: EntriesTable = {
            isWorkShiftValid: {},
            lunchBreak: {},
            workedMoreThanHalfDay: {},
            isMinDailyRestMet: {},
        };
        const locationData: EntriesTable = {
            am: {},
            pm: {},
        };

        for (const workDay of timesheet.workDays) {
            const dayKey = workDay.day;

            projectData.projectRow[dayKey] = workDay.projectTime || 0;

            restData.isWorkShiftValid[dayKey] = workDay.isWorkShiftValid;
            restData.lunchBreak[dayKey] = workDay.lunchBreak;
            restData.workedMoreThanHalfDay[dayKey] = workDay.workedMoreThanHalfDay;
            restData.isMinDailyRestMet[dayKey] = workDay.isMinDailyRestMet;

            locationData.am[dayKey] = workDay.location?.am;
            locationData.pm[dayKey] = workDay.location?.pm;
        }

        projectData.projectRow['total'] = timesheet.workDays.reduce(
            (sum, day) => roundFloat(sum + (day.projectTime || 0)),
            0
        );

        setInitialProjectData(projectData)
        setInitialRestData(restData)
        setInitialLocationData(locationData)
    }, [timesheet]);

    return {
        projectTable,
        restTable,
        locationTable,
        leavesTable,
    };
}