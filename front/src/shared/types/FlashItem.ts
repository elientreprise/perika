export type FlashItem = {
    id: string;
    type?: "success" | "error" | "warning" | "info";
    message: string;
};