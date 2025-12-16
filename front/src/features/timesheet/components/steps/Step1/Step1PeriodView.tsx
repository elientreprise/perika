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

            <div className="w-full flex justify-end">
                {current && (
                    <button
                        type="button"
                        className="text-xs mt-4 px-4 py-2 bg-primary text-white rounded disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                        disabled={isDisabled}
                        onClick={onValidate}
                    >
                        Continuer
                        {isLoading && <LoaderButton />}
                    </button>
                )}
            </div>
        </div>
    );
}