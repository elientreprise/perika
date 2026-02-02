import type {FlashItem} from "./FlashItem.ts";


export type FlashContextType = {
    flashes: FlashItem[];
    remove: (id: string) => void;
    push: (message: string, type: FlashItem["type"]) => void;
};