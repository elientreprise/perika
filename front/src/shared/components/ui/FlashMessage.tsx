import type {FlashMessageProps} from "../../types/FlashMessageProps.ts";
import {useEffect} from "react";
import {X} from "lucide-react";

const typeClasses = {
    success: "alert-success",
    error: "alert-error",
    warning: "alert-warning",
    info: "alert-info",
};

export default function FlashMessage({
                                         type = "info",
                                         message,
                                         onClose,
                                         duration = 4000,
                                     }: Readonly<FlashMessageProps>) {

    useEffect(() => {
        if (!duration || !onClose) return;

        const timer = setTimeout(onClose, duration);
        return () => clearTimeout(timer);
    }, [duration, onClose]);

    return (
        <div
            role="alert"
            className={`alert ${typeClasses[type]} shadow-lg flex items-center justify-between`}
        >
            <span>{message}</span>

            {onClose && (
                <button
                    className="btn btn-sm btn-ghost"
                    onClick={onClose}
                    aria-label="Fermer"
                >
                    <X size={18} />
                </button>
            )}
        </div>
    );
}