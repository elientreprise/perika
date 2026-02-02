export type ValidationError = {
    propertyPath: string;
    message: string;
};

export type FieldErrors = Record<string, string>;