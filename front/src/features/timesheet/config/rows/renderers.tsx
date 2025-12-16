import React from "react";
import type {RenderParams} from "../../../../shared/types/RenderParams.ts";

export const rowRenderers = {
    isMinDailyRestMet: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => {

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
                onChange={(v) => onChange?.(rowKey, colKey, v.target.value)}
                className={`rounded text-xs cursor-pointer flex justify-between items-center bg-base-100 ${hasError ? "text-error" : ""}`}
            >
                <option></option>
                <option value={"true"}>OUI</option>
                <option>NON</option>
                <option>N/A</option>
            </select>
        )
    },

    isWorkShiftValid: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => {

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
                onChange={(v) => onChange?.(rowKey, colKey, v.target.value)}
                className={`rounded text-xs cursor-pointer flex justify-between items-center bg-base-100 ${hasError ? "text-error" : ""}`}
            >
                <option></option>
                <option value={"true"}>OUI</option>
                <option>NON</option>
                <option>N/A</option>
            </select>
        )
    },

    workedMoreThanHalfDay: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => {

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
                onChange={(v) => onChange?.(rowKey, colKey, v.target.value)}
                className={`rounded text-xs cursor-pointer flex justify-between items-center bg-base-100 ${hasError ? "text-error" : ""}`}
            >
                <option></option>
                <option value={"true"}>OUI</option>
                <option>NON</option>
                <option>N/A</option>
            </select>
        )
    },

    am: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => {

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
                onChange={(v) => onChange?.(rowKey, colKey, v.target.value)}
                className={`rounded text-xs cursor-pointer flex justify-between items-center bg-base-100 ${hasError ? "text-error" : ""}`}
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

    pm: ({value, rowKey, colKey, onChange, hasError, readonly}:Readonly<RenderParams>) => {

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
                onChange={(v) => onChange?.(rowKey, colKey, v.target.value)}
                className={`rounded text-xs cursor-pointer flex justify-between items-center bg-base-100 ${hasError ? "text-error" : ""}`}
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