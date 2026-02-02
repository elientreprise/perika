import {createContext} from "react";
import type {FlashContextType} from "../../shared/types/FlashContextType.ts";

export const FlashContext = createContext<FlashContextType>({
    flashes: [],
    remove: () => {},
    push: () => {}
});


