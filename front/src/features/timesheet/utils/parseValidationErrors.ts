import type { ValidationError, FieldErrors } from "../types/ValidationError";

/**
 * Parse les violations API Platform en objet d'erreurs indexé par propertyPath
 */
export function parseValidationErrors(violations: ValidationError[]): FieldErrors {
    const errors: FieldErrors = {};

    for (const violation of violations) {
        errors[violation.propertyPath] = violation.message;
    }

    return errors;
}

/**
 * Extrait les informations d'une propertyPath
 * Ex: "workDays[5].location.am" => { dayIndex: 5, field: "location", subField: "am" }
 */
export function parsePropertyPath(propertyPath: string) {
    const workDayMatch = propertyPath.match(/workDays\[(\d+)\]\.(.+)/);

    if (!workDayMatch) {
        return null;
    }

    const dayIndex = parseInt(workDayMatch[1], 10);
    const fieldPath = workDayMatch[2];

    const [field, subField] = fieldPath.split('.');
    return { dayIndex, field, subField };
}

/**
 * Construit une clé d'erreur pour le tableau
 * Ex: (5, "am") => "am.5"
 */
export function buildErrorKey(dayIndex: number, field: string): string {
    return `${field}.${dayIndex}`;
}