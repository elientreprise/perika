import {FlashContext} from "../contexts/FlashContext.tsx";
import {useCallback, useMemo, useState} from "react";
import type {FlashItem} from "../../shared/types/FlashItem.ts";


export const FlashProvider = ({children}) => {
    const [flashes, setFlashes] = useState<FlashItem[]>([]);

    const push = useCallback((message: string, type: FlashItem["type"] = "info") => {
        const id = crypto.randomUUID();

        setFlashes((prev) => [...prev, { id, message: message, type }]);

        return id;
    }, []);

    const remove = useCallback((id: string) => {
        setFlashes((prev) => prev.filter((f) => f.id !== id));
    }, []);

    const context = useMemo(() => ({flashes, remove, push}), [flashes, remove, push]);

    return (
        <FlashContext.Provider value={context}>
            {children}
        </FlashContext.Provider>
    );
};