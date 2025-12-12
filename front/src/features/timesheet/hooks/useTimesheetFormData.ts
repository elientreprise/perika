import { useMemo, useEffect } from "react";
import { baseColumns, projectColumns, totalColumn } from "../config/columns/columns";
import { TimesheetLeaves } from "../types/TimesheetLeavesType";
import { buildEntries } from "../utils/buildEntries";
import { rowsLocation, rowsProject, rowsRest } from "../config/rows/rows";
import { DaysOfWeek } from "../../../shared/types/DaysOfWeekType";
import { useTimesheetEntries } from "./useTimesheetEntries";
import { useTotals } from "./useTotals";
import type {TimesheetType, WorkDayType} from "../types/TimesheetType.ts";
import type {EntriesTable} from "../../../shared/types/EntriesTable.ts";
import {roundFloat} from "../../../shared/utils/RoundNumber.ts";

type UseTimesheetFormDataOptions = {
    readonly?: boolean;
    initialData?: TimesheetType;
};

export function useTimesheetFormData(options: UseTimesheetFormDataOptions = {}) {
    const { readonly = false, initialData } = options;

    const columnsWithTotal = useMemo(() => [...baseColumns, totalColumn], []);
    const columnsWithProject = useMemo(() => [...projectColumns, ...columnsWithTotal], [columnsWithTotal]);

    const initialLeaves = useMemo(
        () => buildEntries(TimesheetLeaves, columnsWithTotal),
        [columnsWithTotal]
    );
    const initialProjects = useMemo(
        () => buildEntries(rowsProject, columnsWithProject),
        [columnsWithProject]
    );
    const initialRest = useMemo(
        () => buildEntries(rowsRest, DaysOfWeek),
        []
    );
    const initialLocation = useMemo(
        () => buildEntries(rowsLocation, DaysOfWeek),
        []
    );

    const { entries: hoursEntries, handleChange: changeHours, setEntries: setHoursEntries } = useTimesheetEntries(initialLeaves, DaysOfWeek);
    const { entries: projectEntries, handleChange: changeProjects, setEntries: setProjectEntries } = useTimesheetEntries(initialProjects, DaysOfWeek);
    const { entries: restEntries, handleChange: changeRests, setEntries: setRestEntries } = useTimesheetEntries(initialRest, DaysOfWeek);
    const { entries: locationEntries, handleChange: changeLocations, setEntries: setLocationEntries } = useTimesheetEntries(initialLocation, DaysOfWeek);

    useEffect(() => {
        if (!initialData?.workDays) return;

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

        for (const workDay of initialData.workDays) {
            const dayKey = workDay.day;

            projectData.projectRow[dayKey] = workDay.projectTime || 0;

            restData.isWorkShiftValid[dayKey] = workDay.isWorkShiftValid;
            restData.lunchBreak[dayKey] = workDay.lunchBreak;
            restData.workedMoreThanHalfDay[dayKey] = workDay.workedMoreThanHalfDay;
            restData.isMinDailyRestMet[dayKey] = workDay.isMinDailyRestMet;

            locationData.am[dayKey] = workDay.location?.am;
            locationData.pm[dayKey] = workDay.location?.pm;
        }

        projectData.projectRow['total'] = initialData.workDays.reduce(
            (sum, day) => roundFloat(sum + (day.projectTime || 0)),
            0
        );

        setProjectEntries(projectData);
        setRestEntries(restData);
        setLocationEntries(locationData)


    }, [initialData, setLocationEntries, setProjectEntries, setRestEntries]);

    const totalsByDay = useTotals([hoursEntries], columnsWithTotal);
    const totalsGlobalByDay = useTotals([hoursEntries, projectEntries], columnsWithTotal);

    return {
        columnsWithTotal,
        columnsWithProject,

        rowsProject,
        rowsRest,
        rowsLocation,

        hoursEntries,
        projectEntries,
        restEntries,
        locationEntries,

        changeHours: readonly ? undefined : changeHours,
        changeProjects: readonly ? undefined : changeProjects,
        changeRests: readonly ? undefined : changeRests,
        changeLocations: readonly ? undefined : changeLocations,

        totalsByDay,
        totalsGlobalByDay,
    };
}