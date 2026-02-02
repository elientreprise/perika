import SearchableSelect from "../../../../../shared/components/ui/SearchableSelect.tsx";
import DateInput from "../../../../../shared/components/ui/DateInput.tsx";
import {LoaderButton} from "../../../../../shared/components/ui/LoaderButton.tsx";
import TimesheetCommentForm from "../../comment/Form/TimesheetCommentForm.tsx";


type Step1PeriodViewProps = {
    current: boolean;
    employeeUuid: string;
    startPeriod?: string;
    endPeriod: string;
    errorEmployee?: string;
    periodError: string | null;
    loadingPeriod: boolean;
    loadingCheck: boolean;
    exists: boolean;
    onEmployeeChange: (value: string) => void;
    onPeriodChange: (value: string) => void;
    onValidate: () => void;
};

export function Step1PeriodView({
                                    current,
                                    startPeriod,
                                    endPeriod,
                                    employeeUuid,
                                    errorEmployee,
                                    periodError,
                                    loadingPeriod,
                                    loadingCheck,
                                    exists,
                                    onEmployeeChange,
                                    onPeriodChange,
                                    onValidate,
                                }: Readonly<Step1PeriodViewProps>) {
    const isLoading = loadingCheck || loadingPeriod;
    const isDisabled = isLoading || exists;

    return (
        <div className={"w-full"}>
            <div className={"w-1/2"}>
                <h3 className="text-xl font-semibold mb-4">Période</h3>
                <SearchableSelect
                    options={[
                        { label: "Néo WAGNER", value: "db9de620-4f56-45d8-8a74-ca0cdd88f42a" },
                        { label: "2", value: 2 }
                    ]}
                    value={employeeUuid}
                    onChange={(value) => onEmployeeChange(value)}
                    placeholder="Recherchez un utilisateur"
                    error={!!errorEmployee}
                    className={"border p-1 rounded"}
                />
                {errorEmployee && (
                    <p className="text-red-500 mt-2">{errorEmployee}</p>
                )}

                <div className="flex gap-3 mt-4 text-xs">
                    {startPeriod && (
                        <DateInput
                            label="Début de période"
                            value={startPeriod}
                            disabled
                        />
                    )}

                    <DateInput
                        label="Fin de période"
                        value={endPeriod}
                        onChange={(event) => onPeriodChange(event.target?.value)}
                        error={!!periodError}
                    />
                </div>

                {periodError && (
                    <p className="text-red-500 mt-2">{periodError}</p>
                )}

                {loadingPeriod && (
                    <p className="text-gray-500 mt-2">Calcul en cours...</p>
                )}
                {loadingCheck && (
                    <p className="text-gray-500 mt-2">Vérification de la période...</p>
                )}
            </div>

            <div className="w-full mt-5">
                {current && (

                    <div className="divider">
                        <button
                            type="button"
                            className="text-xs cursor-pointer text-white disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            disabled={isDisabled}
                            onClick={onValidate}
                        >
                            {isLoading ? (<LoaderButton/>) : (
                                <div className={"flex flex-col justify-center items-center"}>
                                    Continuer
                                    <svg className="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                         xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                         viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                              stroke-width="2" d="m8 10 4 4 4-4"/>
                                    </svg>
                                </div>
                            )}
                        </button>
                    </div>

                )}
            </div>
        </div>
    );
}