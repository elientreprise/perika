import {useContext} from "react";
import type {FlashContextType} from "../types/FlashContextType.ts";
import {FlashContext} from "../../app/contexts/FlashContext.tsx";



export function useFlash(): FlashContextType {
    const context = useContext(FlashContext);
    if (!context) throw new Error("useFlash must be used inside <FlashProvider>");
    return context;
}
