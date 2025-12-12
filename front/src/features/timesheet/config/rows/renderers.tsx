import React from "react";

export const rowRenderers = {
    isMinDailyRestMet: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => {

        if (readonly) {
            return (
                <div>
                    {value}
                </div>
            )
        }

        return (
            <select
                value={value}
                onChange={(v) => onChange(rowKey, colKey, v.target.value)}
                className={`border rounded p-2 cursor-pointer flex justify-between items-center bg-base-100 ${error ? "border-error" : ""}`}
            >
                <option></option>
                <option value={"true"}>OUI</option>
                <option>NON</option>
                <option>N/A</option>
            </select>
        )
    },

    isWorkShiftValid: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => {

        if (readonly) {
            return (
                <div>
                    {value}
                </div>
            )
        }

        return (
            <select
                value={value}
                onChange={(v) => onChange(rowKey, colKey, v.target.value)}
                className={`border rounded p-2 cursor-pointer flex justify-between items-center bg-base-100 ${error ? "border-error" : ""}`}
            >
                <option></option>
                <option value={"true"}>OUI</option>
                <option>NON</option>
                <option>N/A</option>
            </select>
        )
    },

    workedMoreThanHalfDay: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => {

        if (readonly) {
            return (
                <div>
                    {value}
                </div>
            )
        }

        return (
            <select
                value={value}
                onChange={(v) => onChange(rowKey, colKey, v.target.value)}
                className={`border rounded p-2 cursor-pointer flex justify-between items-center bg-base-100 ${error ? "border-error" : ""}`}
            >
                <option></option>
                <option value={"true"}>OUI</option>
                <option>NON</option>
                <option>N/A</option>
            </select>
        )
    },

    am: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => {

        if (readonly) {
            return (
                <div>
                    {value}
                </div>
            )
        }

        return (
            <select
                value={value}
                onChange={(v) => onChange(rowKey, colKey, v.target.value)}
                className={`border rounded p-2 cursor-pointer flex justify-between items-center bg-base-100 ${error ? "border-error" : ""}`}
            >
                <option value={""}></option>
                <option value={"entreprise"}>Entreprise</option>
                <option>Site Client</option>
                <option value={"regular-tt"}>TT Regulier</option>
                <option>TT Ponctuel</option>
                <option>Vélo Site Client</option>
                <option>Vélo Entreprise</option>
                <option>N/A</option>
            </select>
        )
    },

    pm: (value: number, rowKey: string, colKey: string, onChange, error?: boolean, readonly?: boolean) => {

        if (readonly) {
            return (
                <div>
                    {value}
                </div>
            )
        }

        return (
            <select
                value={value}
                onChange={(v) => onChange(rowKey, colKey, v.target.value)}
                className={`border rounded p-2 cursor-pointer flex justify-between items-center bg-base-100 ${error ? "border-error" : ""}`}
            >
                <option value={""}></option>
                <option value={"entreprise"}>Entreprise</option>
                <option>Site Client</option>
                <option value={"regular-tt"}>TT Regulier</option>
                <option>TT Ponctuel</option>
                <option>Vélo Site Client</option>
                <option>Vélo Entreprise</option>
                <option>N/A</option>
            </select>
        )
    },
};