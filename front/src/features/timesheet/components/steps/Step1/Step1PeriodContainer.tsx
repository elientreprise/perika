import { useState, useEffect, useCallback } from "react";
import {toSimpleDate} from "../../../../../shared/utils/DateFormatter.ts";
import {useEmployeeFinder} from "../../../../../shared/hooks/useEmployeeFinder.ts";
import {useFlash} from "../../../../../shared/hooks/useFlash.ts";
import {useAuth} from "../../../../auth/hooks/useAuth.ts";
import {calculatePeriod, timesheetCheckExist} from "../../../services/timesheet.ts";
import {CalculatePeriodResponseSchema} from "../../../types/CalculatePeriodResponse.ts";
import {CheckExistsResponseSchema} from "../../../types/CheckExistsResponse.ts";
import {Step1PeriodView} from "./Step1PeriodView.tsx";
import {useTimesheetStore} from "../../../store/useTimesheetStore.ts";


type Props = {
    onNext: () => void;
    current: boolean;
};

export default function Step1PeriodContainer({
                                                 onNext,
                                                 current,
                                             }: Readonly<Props>) {

    const { timesheet, update } = useTimesheetStore();

    const [endPeriod, setEndPeriod] = useState<string>(
        timesheet.endPeriod || toSimpleDate(new Date())
    );

    const [startPeriod, setStartPeriod] = useState<string>(timesheet.startPeriod || toSimpleDate(new Date()));
    const [loadingPeriod, setLoadingPeriod] = useState<boolean>(false);
    const [periodError, setPeriodError] = useState<string | null>(null);

    const [exists, setExists] = useState<boolean>(false);
    const [loadingCheck, setLoadingCheck] = useState<boolean>(false);
    const [employeeUuid, setEmployeeUuid] = useState<string>(timesheet.employee.uuid || "");

    const {
        employeeIri,
        buildEmployeeIri,
        error: errorEmployee,
        setError: setErrorEmployee
    } = useEmployeeFinder();

    const { push } = useFlash();
    const { user } = useAuth();

    const fetchPeriod = useCallback(async (date: string | undefined) => {
        if (!date) return;

        setPeriodError(null);
        setLoadingPeriod(true);

        try {
            const res = await calculatePeriod({ date });
            const parsed = CalculatePeriodResponseSchema.parse(res);
            const start = toSimpleDate(new Date(parsed.start));
            const end = toSimpleDate(new Date(parsed.end));

            setStartPeriod(start);
            setEndPeriod(end);
        } catch (err: any) {
            console.error(err);
            setPeriodError(err.message ?? "Erreur inconnue");
        } finally {
            setLoadingPeriod(false);
        }
    }, []);

    const checkTimesheetExist = useCallback(async (date: string, employee: string) => {
        setLoadingCheck(true);

        try {
            const res = await timesheetCheckExist({ employee, date });
            const parsed = CheckExistsResponseSchema.parse(res);
            setExists(parsed.exists);
            return parsed;
        } catch (err: any) {
            console.error(err);
            throw err;
        } finally {
            setLoadingCheck(false);
        }
    }, []);

    const handleEmployeeChange = useCallback((value: string) => {
        setEmployeeUuid(value)
        buildEmployeeIri(value);
    }, [buildEmployeeIri]);

    const handlePeriodChange = useCallback((value: string) => {
        fetchPeriod(value);
        setExists(false);
    }, [fetchPeriod]);

    const handleValidate = useCallback(async () => {
        if (!employeeIri) {
            setErrorEmployee('Veuillez sélectionner un utilisateur');
            return;
        }

        try {
            const checkResult = await checkTimesheetExist(endPeriod, employeeIri);

            if (checkResult.exists) {
                push(checkResult.message, 'error');
                return;
            }

            update({
                employee: employeeIri,
                startPeriod,
                endPeriod
            });

            onNext();
        } catch (error) {
            push("Une erreur est survenue lors de la validation", 'error');
        }
    }, [employeeIri, setErrorEmployee, checkTimesheetExist, endPeriod, update, startPeriod, onNext, push]);

    useEffect(() => {
        if (user?.uuid) {
            buildEmployeeIri(user.uuid);
        }
    }, [buildEmployeeIri, user?.uuid]);

    return (
        <Step1PeriodView
            current={current}
            employeeUuid={employeeUuid}
            startPeriod={startPeriod}
            endPeriod={endPeriod}
            errorEmployee={errorEmployee}
            periodError={periodError}
            loadingPeriod={loadingPeriod}
            loadingCheck={loadingCheck}
            exists={exists}
            onEmployeeChange={handleEmployeeChange}
            onPeriodChange={handlePeriodChange}
            onValidate={handleValidate}
        />
    );
}