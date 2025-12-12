export type FlashMessageProps = {
    type?: "success" | "error" | "warning" | "info";
    message: string;
    onClose?: () => void;
    duration?: number; // ms – ex: 3000, optional
};