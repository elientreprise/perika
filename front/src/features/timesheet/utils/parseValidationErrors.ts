import type { ValidationError } from "../types/ValidationError";
import { DaysOfWeek } from "../../../shared/types/DaysOfWeekType";

export function parseValidationErrors(violations: ValidationError[]): Record<string, string> {
    const errors: Record<string, string> = {};

    for (const violation of violations) {

        const match = violation.propertyPath.match(/workDays\[(\d+)\]\.(.+)/);

        if (match) {
            const dayIndex = parseInt(match[1], 10);
            const fieldPath = match[2];


            const dayKey = DaysOfWeek[dayIndex]?.key;

            if (dayKey) {
                const fieldParts = fieldPath.split(".");
                const fieldKey = fieldParts[fieldParts.length - 1];
                const errorKey = `${fieldKey}.${dayKey}`;
                errors[errorKey] = violation.message;
            }
        }
    }

    return errors;
}