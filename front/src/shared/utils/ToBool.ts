export const toBool = (v: unknown): boolean | null => {
    if (v === null || v === undefined) return null;
    if (typeof v === "boolean") return v;
    if (v === "1" || v === 1 || v === "true") return true;
    if (v === "0" || v === 0 || v === "false") return false;
    return null;
};